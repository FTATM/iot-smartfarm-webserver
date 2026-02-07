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

def load_cameras():
    cams = {}
    raw = os.getenv("CAMERAS")

    if raw:
        pairs = raw.split(";")
        for pair in pairs:
            name, url = pair.split(",")
            cams[name.strip()] = url.strip()

    return cams

CAMERAS = load_cameras()