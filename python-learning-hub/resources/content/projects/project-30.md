## ২৯. লাইভ প্রজেক্ট: রিয়েল-এস্টেট প্রাইস প্রেডিক্টর (Machine Learning Model)

মেশিন লার্নিং (Machine Learning) এর দুনিয়ায় আপনাকে স্বাগতম! এই প্রজেক্টে আমরা একটি কৃত্রিম বুদ্ধিমত্তা (AI) বা মেশিন লার্নিং মডেল তৈরি করবো। ধরুন আপনি একটি বাড়ি কিনতে চান। আপনি যদি প্রোগ্রামটিকে বলেন যে বাড়িটির সাইজ কত স্কয়ার ফিট এবং কয়টি রুম আছে, তাহলে সে তার পূর্বের অভিজ্ঞতা (ডেটা) থেকে হিসাব করে বলে দিবে বাড়িটির দাম আনুমানিক কত হওয়া উচিত!

### কীভাবে কাজ করে? (How it works):
এই প্রজেক্টে আমরা `Linear Regression` নামের একটি জনপ্রিয় মেশিন লার্নিং অ্যালগরিদম ব্যবহার করবো। প্রথমে আমরা মডেলটিকে কিছু ডামি ডেটা (যেমন: ১০০০ স্কয়ার ফিট ২ রুমের দাম ৫০ লাখ, ১২০০ স্কয়ার ফিট ৩ রুমের দাম ৬০ লাখ) দিয়ে ট্রেনিং করাবো। মডেলটি তখন সাইজ, রুম এবং দামের মধ্যে একটি গাণিতিক সম্পর্ক (Pattern) খুঁজে বের করবে। এরপর যখন আমরা নতুন কোনো সাইজ বা রুমের সংখ্যা ইনপুট দিবো, সে ওই প্যাটার্ন ব্যবহার করে নিখুঁতভাবে দাম প্রেডিক্ট (Predict) বা অনুমান করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের `scikit-learn` (মেশিন লার্নিংয়ের জন্য) এবং `pandas` (ডেটা প্রসেসিংয়ের জন্য) লাইব্রেরি লাগবে।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install scikit-learn pandas
```

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে সেভ করুন এবং রান করুন। 

```python
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression

def train_and_predict_price():
    print("=== Real-Estate Price Predictor ===\n")
    
    # ১. ডেটাসেট তৈরি করা (বাস্তবে আমরা এক্সেল বা CSV ফাইল থেকে ডেটা নিয়ে থাকি)
    # এখানে সাইজ (Square Feet), রুম (Bedrooms) এবং দাম (Price in Lakh Taka) দেওয়া হলো
    data = {
        'Square_Feet': [800, 1000, 1200, 1500, 1800, 2000, 2200, 2500],
        'Bedrooms': [2, 2, 3, 3, 4, 4, 4, 5],
        'Price_Lakhs': [40, 50, 60, 75, 90, 100, 110, 125]
    }
    
    df = pd.DataFrame(data)
    
    # ২. ফিচার (Features) এবং টার্গেট (Target) আলাদা করা
    # Features (X): যার উপর ভিত্তি করে দাম হবে (সাইজ এবং রুম)
    # Target (y): যা আমরা প্রেডিক্ট করতে চাই (দাম)
    X = df[['Square_Feet', 'Bedrooms']]
    y = df['Price_Lakhs']
    
    # ৩. ডেটাকে ট্রেনিং এবং টেস্টিং সেটে ভাগ করা (৮০% ট্রেনিং, ২০% টেস্টিং)
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # ৪. মেশিন লার্নিং মডেল তৈরি করা (Linear Regression)
    print("Training the Machine Learning Model...")
    model = LinearRegression()
    
    # মডেলকে ডেটা দিয়ে ট্রেনিং করানো
    model.fit(X_train, y_train)
    print("Model Training Completed!\n")
    
    # ৫. নতুন ডেটার দাম প্রেডিক্ট করা
    try:
        print("Let's predict a house price!")
        sq_ft = float(input("Enter House Size (Square Feet): "))
        rooms = int(input("Enter Number of Bedrooms: "))
        
        # মডেলকে ইনপুট দেওয়া (একটি 2D Array হিসেবে দিতে হয়)
        new_house = pd.DataFrame({'Square_Feet': [sq_ft], 'Bedrooms': [rooms]})
        
        # প্রেডিকশন করা
        predicted_price = model.predict(new_house)
        
        print(f"\nEstimated House Price: {predicted_price[0]:.2f} Lakh Taka")
        
    except ValueError:
        print("Invalid input! Please enter numeric values only.")

if __name__ == "__main__":
    train_and_predict_price()
```

> [!TIP]
> **টিপস:** কোডটি রান করার পর ইনপুট হিসেবে ১৩০০ স্কয়ার ফিট এবং ৩ রুম দিয়ে দেখুন। মডেলটি আগের ডেটাগুলো (যেমন ১২০০ স্কয়ার ফিটের দাম ৬০ লাখ এবং ১৫০০ স্কয়ার ফিটের দাম ৭৫ লাখ) থেকে প্যাটার্ন বুঝতে পেরে আনুমানিক ৬৫-৭০ লাখ টাকার কাছাকাছি একটি দাম আপনাকে দেখাবে!

### কোডটি কীভাবে শিখবেন?
1. **Features & Target (X and y):** মেশিন লার্নিংয়ের প্রথম রুল হলো ডেটাকে দুইভাগে ভাগ করা। `X` হলো ফিচার (যেসব তথ্য দেওয়া থাকবে) এবং `y` হলো টার্গেট (যা বের করতে হবে)। এই কনসেপ্টটি আপনি প্র্যাকটিক্যালি দেখতে পাবেন।
2. **Train-Test Split:** একটি মডেলকে সবসময় কিছু আনদেখা (Unseen) ডেটা দিয়ে টেস্ট করতে হয়। `train_test_split` ফাংশন দিয়ে কীভাবে ডেটাসেটকে ৮০% ট্রেনিং এবং ২০% টেস্টিংয়ের জন্য ভাগ করতে হয় তা আয়ত্ত করতে পারবেন।
3. **Linear Regression:** কীভাবে `model.fit()` ব্যবহার করে মডেলকে ট্রেনিং দিতে হয় এবং `model.predict()` ব্যবহার করে নতুন ডেটার রেজাল্ট বের করতে হয়, সেটিই হলো এই প্রজেক্টের মূল শিক্ষা।

---