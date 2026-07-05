from pathlib import Path
from typing import Optional

from fastapi import FastAPI, File, UploadFile

app = FastAPI(title="BTMS AI Service", version="1.0.0")
MODEL_PATH = Path(__file__).with_name("best.pt")
_model = None
_model_error: Optional[str] = None


def get_model():
    global _model, _model_error
    if _model is not None or _model_error is not None:
        return _model
    try:
        from ultralytics import YOLO
        _model = YOLO(str(MODEL_PATH))
    except Exception as exc:  # service stays alive even when the model runtime is missing
        _model_error = str(exc)
    return _model


@app.get("/")
def read_root():
    return {"message": "Le serveur IA BTMS est operationnel", "model_present": MODEL_PATH.exists()}


@app.get("/health")
def health():
    return {"status": "ok", "model_present": MODEL_PATH.exists(), "model_error": _model_error}


@app.post("/detect")
async def detect(file: UploadFile = File(...)):
    image_bytes = await file.read()
    temp_path = Path(__file__).with_name("_last_upload.jpg")
    temp_path.write_bytes(image_bytes)

    model = get_model()
    if model is None:
        return {"success": False, "message": "Modele YOLOv8 indisponible", "error": _model_error}

    results = model(str(temp_path), conf=0.35)
    detections = []
    for result in results:
        names = result.names
        for box in result.boxes:
            cls = int(box.cls[0])
            detections.append({
                "classe": names.get(cls, str(cls)),
                "confiance": round(float(box.conf[0]), 4),
                "bbox": [round(float(x), 2) for x in box.xyxy[0].tolist()],
            })

    return {"success": True, "detections": detections}
