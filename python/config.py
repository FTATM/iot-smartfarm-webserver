from dotenv import load_dotenv
import os

load_dotenv()  # โหลด .env

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "port": os.getenv("DB_PORT"),
    "name": os.getenv("DB_NAME"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASS"),
}

CAMERAS = {
    os.getenv("CAMERA_1_NAME") : os.getenv("CAMERA_1_RTSP"),
}
