# Scikit-Learn (Zero to Hero) কমপ্লিট গাইড

ডেটা সায়েন্স এবং মেশিন লার্নিং (Machine Learning) শুনতে অনেক ভয়ংকর মনে হলেও, পাইথনে এই কাজটি সবচেয়ে সহজ করে দিয়েছে **`scikit-learn` (বা `sklearn`)** লাইব্রেরি। 

যত ধরনের ক্লাসিক মেশিন লার্নিং অ্যালগরিদম আছে (যেমন: Linear Regression, Random Forest, K-Means), তার সবকিছু এই একটি প্যাকেজের ভেতরে রেডিমেড দেওয়া আছে। আপনাকে কোনো ম্যাথ বা অ্যালগরিদম নিজে থেকে লিখতে হবে না!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ডেটাসেট লোডিং থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Model Tuning (`GridSearchCV`) পর্যন্ত একটি কমপ্লিট মেশিন লার্নিং প্রজেক্টের ধাপগুলো শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং রেডিমেড ডেটাসেট
প্রথমে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install scikit-learn pandas numpy
```

মেশিন লার্নিং প্র্যাকটিস করার জন্য `sklearn` এ আগে থেকেই কিছু ফেমাস ডেটাসেট (যেমন: Iris ফ্লাওয়ার, বস্টন হাউজিং) দেওয়া থাকে।
```python
from sklearn.datasets import load_iris
import pandas as pd

# ১. আইরিস (Iris) ফুলের ডেটাসেট লোড করা
iris = load_iris()

# ২. ডেটাকে প্যান্ডাস (Pandas) ডেটাফ্রেমে রূপান্তর করা (যাতে দেখতে সুন্দর লাগে)
# X হলো আমাদের ফিচার বা বৈশিষ্ট্য (পাপড়ির দৈর্ঘ্য, প্রস্থ ইত্যাদি)
X = pd.DataFrame(iris.data, columns=iris.feature_names)

# y হলো টার্গেট বা রেজাল্ট (ফুলটি কোন প্রজাতির)
y = pd.Series(iris.target)

print(X.head())
print("Target Categories:", iris.target_names)
```

### ২. Train-Test Split (ডেটা দুই ভাগ করা)
আমরা যদি মডেলকে ১০০% ডেটা দিয়েই ট্রেনিং করাই, তবে মডেলটি সব মুখস্থ করে ফেলবে! তাই আমরা সাধারণত ৮০% ডেটা দিয়ে ট্রেনিং করাই এবং বাকি ২০% ডেটা লুকিয়ে রাখি, মডেলের পরীক্ষা (Test) নেওয়ার জন্য।

```python
from sklearn.model_selection import train_test_split

# test_size=0.2 মানে ২০% ডেটা টেস্টিংয়ের জন্য রাখা হলো
# random_state=42 দিলে প্রতিবার রান করলে একই রকমভাবে ডেটা ভাগ হবে
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

print("Training Data Size:", len(X_train)) # 120
print("Testing Data Size:", len(X_test))   # 30
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. ডেটা স্কেলিং (Data Preprocessing)
মেশিন লার্নিং মডেল বড় সংখ্যা দেখলে ভয় পায় (বা তাকে বেশি গুরুত্ব দেয়)। যেমন: কারও বয়স ২৫ এবং বেতন ৫০০০০। মডেল ভাববে বেতন অনেক বেশি গুরুত্বপূর্ণ কারণ এটি বড় সংখ্যা! তাই সব ডেটাকে একটি নির্দিষ্ট রেঞ্জে (যেমন -৩ থেকে +৩) নিয়ে আসাকে Scaling বলে।

```python
from sklearn.preprocessing import StandardScaler

scaler = StandardScaler()

# ট্রেনিং ডেটার ওপর ভিত্তি করে স্কেল করা (fit_transform)
X_train_scaled = scaler.fit_transform(X_train)

# টেস্টিং ডেটাকে শুধু transform করতে হয় (fit করা যাবে না!)
X_test_scaled = scaler.transform(X_test)

print("Scaled Data (First Row):", X_train_scaled[0])
```

### ৪. মডেল তৈরি এবং ট্রেনিং করা (`fit` এবং `predict`)
Scikit-Learn এর সবচেয়ে বড় ম্যাজিক হলো—আপনি যেকোনো অ্যালগরিদম ব্যবহার করেন না কেন, সবার নিয়ম একই!
- `fit()` : মডেলকে শেখানো বা ট্রেনিং দেওয়া।
- `predict()` : মডেলকে দিয়ে পরীক্ষা করানো।

```python
from sklearn.ensemble import RandomForestClassifier

# ১. মডেল তৈরি করা (এখানে আমরা Random Forest ব্যবহার করছি)
model = RandomForestClassifier(random_state=42)

# ২. মডেলকে ডেটা দিয়ে ট্রেনিং দেওয়া (Learning Phase)
model.fit(X_train_scaled, y_train)
print("Model Training Completed!")

# ৩. টেস্টিং ডেটা (যেটা মডেল আগে কখনো দেখেনি) দিয়ে প্রেডিক্ট বা অনুমান করা
predictions = model.predict(X_test_scaled)

print("Model's Predictions:", predictions)
print("Actual Answers:   ", y_test.values)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. মডেলের রেজাল্ট চেক করা (Evaluation)
মডেলটি কত পারসেন্ট সঠিক উত্তর দিয়েছে? এবং কোন জায়গায় বেশি ভুল করেছে?

```python
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report

# ১. একুরেসি (Accuracy) চেক করা
acc = accuracy_score(y_test, predictions)
print(f"Model Accuracy: {acc * 100:.2f}%") # যেমন: 100.00%

# ২. কনফিউশন ম্যাট্রিক্স (Confusion Matrix)
# এটি দেখায় কোন ফুলকে সে ভুল করে অন্য ফুল ভেবেছে
print("\nConfusion Matrix:\n", confusion_matrix(y_test, predictions))

# ৩. বিস্তারিত রিপোর্ট (Precision, Recall, F1-Score)
print("\nClassification Report:\n", classification_report(y_test, predictions, target_names=iris.target_names))
```

### ৬. হাইপারপ্যারামিটার টিউনিং (`GridSearchCV`)
মডেল তৈরি করার সময় আমরা কিছু সেটিং (যেমন: কয়টি গাছ থাকবে `n_estimators`, গাছের গভীরতা কত হবে `max_depth`) পরিবর্তন করে মডেলের রেজাল্ট আরও ভালো করতে পারি। এগুলোকে বলে হাইপারপ্যারামিটার।

সবগুলো সেটিং নিজে নিজে চেক করা বিরক্তিকর। `GridSearchCV` অটোমেটিকভাবে সব কম্বিনেশন চেক করে সবচেয়ে বেস্ট মডেলটি খুঁজে বের করে!

```python
from sklearn.model_selection import GridSearchCV

# ১. মডেলের জন্য বিভিন্ন অপশন বা প্যারামিটার সেট করা
param_grid = {
    'n_estimators': [50, 100, 200], # Random Forest এ কয়টি গাছ থাকবে
    'max_depth': [None, 5, 10],     # গাছের সর্বোচ্চ গভীরতা
}

base_model = RandomForestClassifier(random_state=42)

# ২. Grid Search তৈরি করা (cv=5 মানে Cross-Validation)
grid_search = GridSearchCV(base_model, param_grid, cv=5)

# ৩. সবগুলো অপশন চেক করার জন্য ট্রেনিং শুরু করা (একটু সময় নিবে)
grid_search.fit(X_train_scaled, y_train)

# ৪. সবচেয়ে বেস্ট সেটিং কোনটি?
print("Best Parameters:", grid_search.best_params_)

# ৫. বেস্ট মডেল দিয়ে অটোমেটিক প্রেডিক্ট করা
best_predictions = grid_search.predict(X_test_scaled)
print(f"Optimized Accuracy: {accuracy_score(y_test, best_predictions) * 100:.2f}%")
```

### সারসংক্ষেপ (Conclusion)
আপনি যদি ডেটা সায়েন্টিস্ট বা মেশিন লার্নিং ইঞ্জিনিয়ার হতে চান, তবে **`scikit-learn`** হলো আপনার প্রথম এবং প্রধান ধাপ। এর কনসিস্টেন্ট (Consistent) API—`fit()` এবং `predict()`—এতোটাই সুন্দরভাবে ডিজাইন করা যে, অন্যান্য মডার্ন ফ্রেমওয়ার্কগুলোও (যেমন XGBoost বা LightGBM) এই একই ডিজাইন ফলো করে!
