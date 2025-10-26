import pickle

# Load trained model and vectorizer
with open("chatbot_model.pkl", "rb") as f:
    model = pickle.load(f)

with open("vectorizer.pkl", "rb") as f:
    vectorizer = pickle.load(f)

print("🤖 MedBot: Hello! Tell me your symptoms. Type 'bye' to exit.\n")

while True:
    user_input = input("You: ")
    
    if user_input.lower() == "bye":
        print("🤖 MedBot: Goodbye! Take care.")
        break

    # Convert input into vector
    X = vectorizer.transform([user_input])
    
    # Predict disease
    prediction = model.predict(X)[0]
    
    print(f"🤖 MedBot: Based on your symptoms, you may have **{prediction}**. Please consult a doctor for confirmation.\n")
