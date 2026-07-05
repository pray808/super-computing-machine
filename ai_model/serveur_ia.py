from flask import Flask, Response
import cv2
from ultralytics import YOLO

app = Flask(__name__)

# Utilisation du modèle Nano pour la rapidité
model = YOLO("best.pt")

def generate_frames(url_ip):
    cap = cv2.VideoCapture(url_ip)
    frame_count = 0
    
    while True:
        success, frame = cap.read()
        if not success: 
            break
        
        frame_count += 1
        
        # OPTIMISATION : On n'analyse qu'une image sur 3
        if frame_count % 3 == 0:
            # Redimensionnement léger pour YOLOv8 (640px)
            frame_resized = cv2.resize(frame, (640, 480))
            results = model.track(frame_resized, persist=True, classes=[0])
            # Visualisation
            annotated_frame = results[0].plot()
            # On remet à la taille originale pour l'affichage
            annotated_frame = cv2.resize(annotated_frame, (frame.shape[1], frame.shape[0]))
        else:
            # Pour les frames sautées, on affiche juste l'image brute
            annotated_frame = frame
        
        # Encodage pour le web
        ret, buffer = cv2.imencode('.jpg', annotated_frame)
        if not ret:
            continue
            
        yield (b'--frame\r\n' b'Content-Type: image/jpeg\r\n\r\n' + buffer.tobytes() + b'\r\n')

@app.route('/video_feed/<path:url>')
def video_feed(url):
    return Response(generate_frames(url), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    # host='0.0.0.0' permet l'accès depuis d'autres appareils sur le réseau
    app.run(host='0.0.0.0', port=5000, threaded=True)