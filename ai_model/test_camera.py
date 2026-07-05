import cv2

for i in range(10):
    cap = cv2.VideoCapture(1)

    if cap.isOpened():
        print(f"Caméra trouvée à l'index : {i}")
        cap.release()
    else:
        print(f"Aucune caméra à l'index : {i}")