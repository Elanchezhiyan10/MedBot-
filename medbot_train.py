import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.svm import LinearSVC
import pickle
from sklearn.metric.pairwise import cosine_similarity
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report

# Load dataset
data = pd.read_csv("S:\MEDBOT PROJECT\chatbotmodel\dataset.csv")

# Combine multiple symptom columns into one
if 'Symptom_2' in data.columns:  
    data['all_symptoms'] = data[['Symptom_1','Symptom_2','Symptom_3']].fillna('').agg(' '.join, axis=1)
    X = data['all_symptoms']
else:
    X = data['Symptom']  # single symptom column

y = data['Disease']



# Convert text to vectors
vectorizer = TfidfVectorizer(ngram_range=(1,2))
X_vec = vectorizer.fit_transform(X)

#Training the Datasets
X_train, X_test, y_train, y_test = train_test_split(X_vec, y, test_size=0.2, random_state=42)

# Train Naïve Bayes model
nb_model = MultinomialNB()
nb_model.fit(X_train, y_train)

# Train SVM model
svm_model = LinearSVC()
svm_model.fit(X_train, y_train)

#Predict
y_pred_nb = nb_model.predict(X_test)
y_pred_svm = svm_model.predict(X_test)

#Accuracy
print("Naïve Bayes Accuracy:", accuracy_score(y_test, y_pred_nb))
print("SVM Accuracy:", accuracy_score(y_test, y_pred_svm))

# Save both models and vectorizer
with open("chatbot_model_nb.pkl", "wb") as f:
    pickle.dump(nb_model, f)

with open("chatbot_model_svm.pkl", "wb") as f:
    pickle.dump(svm_model, f)

with open("vectorizer.pkl", "wb") as f:
    pickle.dump(vectorizer, f)

print("✅ Both Naïve Bayes and SVM models trained and saved!")

# -----------------------------
# Example: Simple ensemble prediction function
# -----------------------------
from sklearn.metrics.pairwise import cosine_similarity

def predict_disease(symptom_text):
    symptom_text = symptom_text.lower().strip()
    
    # Step 1: Handle greetings
    greetings = ["hi", "hello", "hey", "good morning", "good evening"]
    if symptom_text in greetings:
        return "Hello! I am MedBot 🤖. Please type your symptoms for analysis."



    # Step 3: Check similarity with known symptoms
   

    vec = vectorizer.transform([symptom_text])
    similarities = cosine_similarity(input_vec, X_vec)  # compare with ALL training symptom vectors
    max_sim = similarities.max()
    if max_sim < 0.2:   # 0.2 is an example threshold
        return "I'm not sure what disease this relates to. Please describe your symptoms."
  # threshold for unrelated input
        return "I'm not sure what disease this relates to. Please describe your symptoms."

    # Step 4: Ensemble prediction
    nb_pred = nb_model.predict(vec)[0]
    svm_pred = svm_model.predict(vec)[0]
    
    if nb_pred == svm_pred:
        return nb_pred
    else:
        return svm_pred  # SVM preference

# -----------------------------
# Ensemble prediction on test set
# -----------------------------
def ensemble_predict(X_input):
    nb_pred = nb_model.predict(X_input)
    svm_pred = svm_model.predict(X_input)
    
    ensemble_pred = []
    for i in range(len(nb_pred)):
        if nb_pred[i] == svm_pred[i]:
            ensemble_pred.append(nb_pred[i])
        else:
            ensemble_pred.append(svm_pred[i])  # SVM preference
    return ensemble_pred

# Predict on test set
y_pred_ensemble = ensemble_predict(X_test)

# Accuracy for ensemble
print("Ensemble Accuracy:", accuracy_score(y_test, y_pred_ensemble))

# Detailed metrics for ensemble
print("\nEnsemble Classification Report:\n", classification_report(y_test, y_pred_ensemble))

# Test prediction
