import cv2
import asyncio
import websockets
import base64
import json
import time
import numpy as np
from inference_sdk import InferenceHTTPClient
from config import CAMERAS

# ======================
# CONFIG
# ======================
client = InferenceHTTPClient(
    api_key="86OlAwsdbNJi9B662Pct", api_url="http://localhost:9001"
)

WS_URI = "ws://localhost:8765"

cam_list = CAMERAS

FRAME_INTERVAL = 0.03
JPEG_QUALITY = 30
RESIZE_W = 640
RESIZE_H = 360

# IR → °C
# SCALE = 0.06
# OFFSET = 25

SCALE = 0.16
OFFSET = 0

latest_frames = {}  # {cam_name: frame}
ws_lock = asyncio.Lock()


# ======================
# CAMERA GRABBER + THERMAL MAP
# ======================

async def grabber(ws, cam_name, rtsp_url):

    cap = None

    while True:

        # -------------------
        # CONNECT
        # -------------------
        if cap is None or not cap.isOpened():
            print(f"{cam_name} connecting...")
            cap = cv2.VideoCapture(rtsp_url, cv2.CAP_FFMPEG,
            [
                cv2.CAP_PROP_OPEN_TIMEOUT_MSEC, 5000,
                cv2.CAP_PROP_READ_TIMEOUT_MSEC, 5000,
            ],
            )
            cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)

            if not cap.isOpened():
                print(f"{cam_name} cannot open stream → retry in 5 sec")
                await asyncio.sleep(5)
                print()
                continue

            payload = {
                "type": "setCamera",
                "data": cam_list
            }

            async with ws_lock:
                await ws.send(json.dumps(payload))
            print(f"{cam_name} connected.")

        # -------------------
        # READ FRAME
        # -------------------
        ret, frame = cap.read()

        if not ret:
            print(f"{cam_name} read failed → reconnecting")
            cap.release()
            cap = None
            await asyncio.sleep(1)
            continue

        frame = cv2.resize(frame, (RESIZE_W, RESIZE_H))
        latest_frames[cam_name] = frame

        await asyncio.sleep(0)


# ======================
# OPTIONAL: INFERENCE
# ======================
async def infer_and_send(ws, cam_name):
    loop = asyncio.get_running_loop()
    last_send = 0
    print(f"{cam_name} infer task started")
    
    while True:
        frame = latest_frames.get(cam_name)
        if frame is None:
            await asyncio.sleep(0.05)
            continue

        now = time.time()
        if now - last_send >= FRAME_INTERVAL:

            # สร้าง thermal map ของ crop
            temp_norm = cv2.normalize(frame, None, 0, 255, cv2.NORM_MINMAX).astype(
                np.uint8
            )
            thermal_frame = cv2.applyColorMap(temp_norm, cv2.COLORMAP_JET)
            # ---------- INFERENCE ----------
            try:
                results = await loop.run_in_executor(
                    None,
                    lambda: client.infer(
                        thermal_frame, model_id="poultry-thermal-new/1"
                    ),
                )
            except Exception as e:
                print(f"{cam_name} inference error:", e)
                await asyncio.sleep(0.1)
                continue

            crops_data = []  # เก็บข้อมูล temp + crop image

            if results and "predictions" in results:
                for pred in results["predictions"]:
                    cx, cy = int(pred["x"]), int(pred["y"])
                    bw, bh = int(pred["width"]), int(pred["height"])
                    x1 = max(0, cx - bw // 2)
                    y1 = max(0, cy - bh // 2)
                    x2 = min(RESIZE_W - 1, cx + bw // 2)
                    y2 = min(RESIZE_H - 1, cy + bh // 2)
                    crop = frame[y1:y2, x1:x2]
                    gray = (
                        cv2.cvtColor(crop, cv2.COLOR_BGR2GRAY)
                        if len(crop.shape) == 3
                        else crop
                    )
                    temp = gray.astype(np.float32) * SCALE + OFFSET

                    max_temp = np.max(temp)
                    min_temp = np.min(temp)
                    avg_temp = np.mean(temp)

                    cv2.rectangle(thermal_frame, (x1, y1), (x2, y2), (255, 255, 255), 2)
                    cv2.putText(
                        thermal_frame,
                        "Chicken" + f" {round(float(max_temp),2)}C",
                        (x1, max(20, y1 - 10)),
                        cv2.FONT_HERSHEY_SIMPLEX,
                        0.7,
                        (255, 255, 255),
                        2,
                    )

                    crops_data.append(
                        {
                            "bbox": [x1, y1, x2, y2],
                            "class": "chicken",
                            "max_temp": float(max_temp),
                            "min_temp": float(min_temp),
                            "avg_temp": float(avg_temp),
                        }
                    )
            ok, buf = cv2.imencode(
                ".jpg", thermal_frame, [cv2.IMWRITE_JPEG_QUALITY, JPEG_QUALITY]
            )
            # ส่ง WS ทั้งหมด
            if ok:
                payload = {
                    "type": "image",
                    "room": cam_name,
                    "data": base64.b64encode(buf).decode("utf-8"),
                    "crops": crops_data,
                }
                try:
                    async with ws_lock:
                        await ws.send(json.dumps(payload))
                except:
                    pass

            last_send = now

        await asyncio.sleep(0.01)


# ======================
# MAIN
# ======================
async def main():
    async with websockets.connect(
        WS_URI, max_size=10_000_000, ping_interval=20, ping_timeout=20
    ) as ws:
        # join ทุก room
        for cam in cam_list:
            await ws.send(json.dumps({"type": "joinroom", "data": cam}))
            print(f"Joined room {cam}")

        payload = {
            "type": "setCamera",
            "data": cam_list
        }

        async with ws_lock:
            await ws.send(json.dumps(payload))

        tasks = []
        for cam_name, rtsp_url in cam_list.items():
            print(f"====== start {cam_name} ========")
            print(f"{cam_name} : {rtsp_url}")
            print("\n\n")
            tasks.append(asyncio.create_task(grabber(ws, cam_name, rtsp_url)))
            tasks.append(asyncio.create_task(infer_and_send(ws, cam_name)))
            
        await asyncio.gather(*tasks)

    print("ws : disconnected")


# ======================
# RUN
# ======================
asyncio.run(main())
