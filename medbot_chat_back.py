import sys
import pickle
import os

# Load model + vectorizer
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
with open(os.path.join(BASE_DIR, "chatbot_model.pkl"), "rb") as f:
    model = pickle.load(f)

with open(os.path.join(BASE_DIR, "vectorizer.pkl"), "rb") as f:
    vectorizer = pickle.load(f)

# Get input from PHP
user_input = " ".join(sys.argv[1:]).lower()

if user_input == "bye":
    print("Goodbye! Take care.")
else:
    # Split by commas/spaces, remove extra spaces
    symptoms = [s.strip() for s in user_input.replace(",", " ").split() if s.strip()]

    # Transform and predict
    X = vectorizer.transform([" ".join(symptoms)])
    prediction = model.predict(X)[0]

    print(f"Based on your symptoms, you may have ({prediction}). Please consult a doctor.")
