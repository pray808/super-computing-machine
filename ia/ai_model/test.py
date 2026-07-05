import cv2
from ultralytics import YOLO

# 1. Charger le modèle entraîné pour le projet BTMS
model = YOLO("best.pt")

# 2. Configurer le flux IP (vu dans image_c857da.jpg)
stream_url = "http://172.16.3.23:8080/video"
cap = cv2.VideoCapture(stream_url)

# Paramètres de comptage
line_y = 300  # Position horizontale de la ligne de comptage
count = 0
detected_ids = set()

print("Système BTMS actif. Supervision en cours...")

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # 3. Inférence YOLOv8
    # On filtre pour ne détecter que la classe 'voiture' (ajustez l'index selon votre data.yaml)
    results = model.track(frame, persist=True, classes=[0]) 

    # 4. Logique de comptage simple
    if results[0].boxes.id is not None:
        boxes = results[0].boxes.xyxy.cpu().numpy().astype(int)
        ids = results[0].boxes.id.cpu().numpy().astype(int)

        for box, obj_id in zip(boxes, ids):
            x1, y1, x2, y2 = box
            center_y = (y1 + y2) // 2

            # Vérifier si l'objet traverse la ligne
            if line_y - 10 < center_y < line_y + 10:
                if obj_id not in detected_ids:
                    count += 1
                    detected_ids.add(obj_id)
                    print(f"Véhicule détecté ! Total : {count}")

    # 5. Visualisation
    annotated_frame = results[0].plot()
    cv2.line(annotated_frame, (0, line_y), (1280, line_y), (0, 255, 0), 2)
    cv2.putText(annotated_frame, f"Comptage: {count}", (50, 50), 
                cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)

    cv2.imshow("BTMS Traffic Command Center", annotated_frame)

    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()