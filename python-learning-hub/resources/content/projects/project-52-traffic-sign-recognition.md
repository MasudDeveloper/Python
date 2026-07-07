# ৫২. ট্রাফিক সাইন রিকগনিশন (Self-Driving Car Tech)

টেসলা (Tesla) বা গুগলের ওয়েমো (Waymo) এর মতো সেলফ ড্রাইভিং গাড়িগুলো রাস্তায় চলার সময় স্পিড লিমিট, স্টপ সাইন বা ইউ-টার্নের সাইনবোর্ড দেখে কীভাবে বুঝতে পারে যে তাকে এখন কী করতে হবে? 

এই ম্যাজিকটি করা হয় **কম্পিউটার ভিশন (Computer Vision)** এবং **ডিপ লার্নিং (Deep Learning)** এর মাধ্যমে। এই প্রজেক্টে আমরা ঠিক সেরকমই একটি এআই মডেল তৈরি করবো, যা রাস্তার যেকোনো ট্রাফিক সাইনের ছবি দেখে বলে দিতে পারবে সেটি আসলে কীসের সাইন!

### কীভাবে কাজ করে? (How it works):
1. **Dataset (GTSRB):** ইন্টারনেটে "German Traffic Sign Recognition Benchmark (GTSRB)" নামে একটি ডেটাসেট আছে, যেখানে হাজার হাজার ট্রাফিক সাইনের ছবি দেওয়া আছে। আমরা সেটি দিয়ে এআইকে ট্রেন করাবো।
2. **CNN Model:** Convolutional Neural Network (CNN) ব্যবহার করে ছবিগুলো স্ক্যান করা হবে। সিএনএন মূলত ছবির বর্ডার, লাল রং এবং জ্যামিতিক আকার (যেমন: ত্রিভুজ, গোলক) বিশ্লেষণ করে।
3. **Image Classification:** ট্রেনিং শেষে নতুন কোনো সাইনবোর্ডের ছবি দিলে মডেলটি ক্লাসিফিকেশন করে তার নাম (যেমন: "Speed Limit 60 km/h" বা "Stop") আউটপুট দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install tensorflow opencv-python pandas numpy
```

### প্রজেক্টের কোড:
নিচের কোডটি হলো ট্রাফিক সাইন রিকগনিশনের জন্য সিএনএন (CNN) মডেলের কোর আর্কিটেকচার:

```python
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense, Dropout
import cv2
import numpy as np

def build_traffic_sign_model():
    print("=== Traffic Sign Recognition AI (Self-Driving Tech) ===\n")
    
    print("[1] Building CNN Architecture...")
    # ৪৩ ধরনের ট্রাফিক সাইন (ক্লাস) আছে ডেটাসেটে
    num_classes = 43 
    
    # এআই মডেল তৈরি করা
    model = Sequential([
        # প্রথম কনভোলিউশনাল লেয়ার (Feature Extraction)
        Conv2D(32, (3, 3), activation='relu', input_shape=(30, 30, 3)),
        MaxPooling2D(pool_size=(2, 2)),
        Dropout(0.25),
        
        # দ্বিতীয় লেয়ার
        Conv2D(64, (3, 3), activation='relu'),
        MaxPooling2D(pool_size=(2, 2)),
        Dropout(0.25),
        
        # ছবিকে ম্যাট্রিক্স থেকে ভেক্টরে রূপান্তর করা
        Flatten(),
        
        # ফুল্লি কানেক্টেড নিউরাল নেটওয়ার্ক
        Dense(256, activation='relu'),
        Dropout(0.5),
        
        # আউটপুট লেয়ার (৪৩টি ক্লাসের মধ্যে একটি সিলেক্ট করবে)
        Dense(num_classes, activation='softmax')
    ])
    
    # মডেল কম্পাইল করা
    model.compile(optimizer='adam', loss='categorical_crossentropy', metrics=['accuracy'])
    print("✅ Model compiled successfully!\n")
    
    model.summary()
    return model

def predict_sign(model, img_path):
    """নতুন ছবি দেখে ট্রাফিক সাইন চেনা"""
    print(f"\n[2] Analyzing Image: {img_path}")
    try:
        # ছবি লোড করা এবং মডেলের সাইজ অনুযায়ী (30x30) রিসাইজ করা
        image = cv2.imread(img_path)
        image = cv2.resize(image, (30, 30))
        # ছবিকে নরমাল বা স্কেল করা (0 থেকে 1 এর মধ্যে)
        image = np.array(image) / 255.0
        image = np.expand_dims(image, axis=0)
        
        # এআই প্রেডিকশন
        prediction = model.predict(image)
        predicted_class = np.argmax(prediction, axis=1)[0]
        
        print(f"🎯 AI Prediction Class ID: {predicted_class}")
        # বাস্তবে এখানে একটি ডিকশনারি থাকে যা ID কে নামে রূপান্তর করে (যেমন: 14 = Stop Sign)
        
    except Exception as e:
        print(f"Error loading image: {e}")

if __name__ == "__main__":
    ai_model = build_traffic_sign_model()
    print("\n[!] The model is ready. (Needs model.fit() with GTSRB dataset for actual training)")
    
    # ডেমো প্রেডিকশন (আপনার ফোল্ডারে stop_sign.jpg থাকলে কাজ করবে)
    # predict_sign(ai_model, "stop_sign.jpg")
```

### কোডটি কীভাবে শিখবেন?
1. **CNN Architecture:** এই মডেলটি সাধারণ নিউরাল নেটওয়ার্কের চেয়ে শক্তিশালী। `Conv2D` লেয়ারগুলো ইমেজের ওপর বিভিন্ন ফিল্টার বসিয়ে ইমেজের ফিচার (যেমন: স্টপ সাইনের আট-কোণা বর্ডার) এক্সট্র্যাক্ট করে। আর `MaxPooling` ছবির অপ্রয়োজনীয় পিক্সেল বাদ দিয়ে ডেটার সাইজ ছোট করে আনে।
2. **Softmax Activation:** মডেলের শেষ লেয়ারে `softmax` ব্যবহার করা হয়েছে। কারণ এখানে উত্তর হ্যাঁ/না (Sigmoid) নয়, বরং ৪৩টি ভিন্ন ভিন্ন ট্রাফিক সাইনের অপশন আছে। `softmax` এই ৪৩টি অপশনের প্রতিটির জন্য একটি করে সম্ভাবনার পার্সেন্টেজ (Probability) বের করে, যার মধ্যে সবচেয়ে বেশি পার্সেন্টেজ পাওয়া সাইনটিই হয় রেজাল্ট।
3. **Image Normalization:** `image / 255.0` লাইনটি ইমেজের পিক্সেল ভ্যালুগুলোকে ০ থেকে ২৫৫ এর বদলে ০ থেকে ১ এর মধ্যে নিয়ে আসে। এটি এআইকে দ্রুত শিখতে সাহায্য করে!
