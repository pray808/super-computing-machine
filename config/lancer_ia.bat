@echo off
cd ai_model
python -m uvicorn main:app --reload
pause