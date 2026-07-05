from ultralytics import YOLO
import cv2

model = YOLO("best.pt")

cap = cv2.VideoCapture(1)

while True:

    success, frame = cap.read()

    if not success:
        break

    results = model(frame)

    annotated_frame = results[0].plot()

    cv2.imshow(
        "BTMS Detection",
        annotated_frame
    )

    if cv2.waitKey(1) == 27:
        break

cap.release()
cv2.destroyAllWindows()