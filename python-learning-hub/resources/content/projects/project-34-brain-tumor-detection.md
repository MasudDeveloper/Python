# ৩৪. ব্রেইন টিউমার ডিটেকশন (Medical Imaging AI)

আর্টিফিশিয়াল ইন্টেলিজেন্স বা এআই (AI) শুধু চ্যাটবট বানানোর কাজেই সীমাবদ্ধ নেই, বর্তমানে চিকিৎসাবিজ্ঞানে এর ব্যবহার অবাক করার মতো! এই প্রজেক্টে আমরা **ডিপ লার্নিং (Deep Learning)** এবং **CNN (Convolutional Neural Network)** ব্যবহার করে এমন একটি এআই মডেল তৈরি করবো, যাকে মানুষের ব্রেইনের এমআরআই (MRI) স্ক্যানার ইমেজ দিলে সে বলে দিতে পারবে ব্রেইনে টিউমার আছে কি নেই। 

এটি কম্পিউটার ভিশন (Computer Vision) এবং হেলথটেকের একটি চমৎকার প্রজেক্ট। 

### কীভাবে কাজ করে? (How it works):
1. **Data Collection:** প্রথমে আমাদের অনেকগুলো এমআরআই (MRI) ইমেজের ডাটাবেস লাগবে, যেখানে কিছু ইমেজে টিউমার আছে এবং কিছু ইমেজে নেই (Kaggle থেকে সহজেই নামানো যায়)। 
2. **CNN Model (সিএনএন):** এরপর আমরা একটি Convolutional Neural Network তৈরি করবো। সিএনএন হলো এমন এক ধরনের এআই মডেল যা মানুষের চোখের মতো ছবি দেখে ছবির ভেতরের প্যাটার্ন (Pattern) চিনতে পারে।
3. **Training:** এআই মডেলটিকে ওই হাজার হাজার ছবি দেখিয়ে ট্রেনিং করানো হবে। 
4. **Prediction:** ট্রেনিং শেষে নতুন কোনো এমআরআই ইমেজ ইনপুট দিলে সে প্রেডিক্ট করে বলে দিবে যে টিউমারটি `Positive` নাকি `Negative`।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের গুগলের তৈরি `TensorFlow` লাইব্রেরি লাগবে। টার্মিনালে নিচের কমান্ডটি লিখে ইনস্টল করে নিন:

```bash
pip install tensorflow numpy pillow
```

### প্রজেক্টের কোড:

এই প্রজেক্টটি রান করার জন্য আপনার কম্পিউটারে ইমেজের একটি ডেটাসেট থাকতে হবে। ধরে নিচ্ছি আপনার কাছে ট্রেনিং ডেটা আছে। নিচের কোডটি এআই মডেল তৈরি এবং ইমেজ প্রেডিক্ট করার বেসিক ফ্রেমওয়ার্ক:

```python
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.preprocessing import image
import numpy as np

def build_brain_tumor_model():
    print("Building CNN Model for Brain Tumor Detection...")
    
    # এআই মডেল (CNN) তৈরি করা
    model = Sequential([
        # প্রথম লেয়ার (ছবি স্ক্যান করার জন্য)
        Conv2D(32, (3, 3), activation='relu', input_shape=(64, 64, 3)),
        MaxPooling2D(pool_size=(2, 2)),
        
        # দ্বিতীয় লেয়ার
        Conv2D(64, (3, 3), activation='relu'),
        MaxPooling2D(pool_size=(2, 2)),
        
        # ছবিকে ম্যাট্রিক্স থেকে সোজা ভেক্টরে রূপান্তর করা
        Flatten(),
        
        # হিডেন নিউরাল নেটওয়ার্ক
        Dense(128, activation='relu'),
        
        # আউটপুট লেয়ার (Yes বা No, তাই সিগময়েড ফাংশন)
        Dense(1, activation='sigmoid')
    ])
    
    # মডেলটিকে কম্পাইল করা
    model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])
    return model

def predict_mri(model, img_path):
    print(f"Analyzing MRI Scan: {img_path}")
    
    # ইমেজ লোড এবং সাইজ ঠিক করা
    test_image = image.load_img(img_path, target_size=(64, 64))
    test_image = image.img_to_array(test_image)
    test_image = np.expand_dims(test_image, axis=0)
    
    # এআইকে দিয়ে প্রেডিক্ট করানো
    result = model.predict(test_image)
    
    if result[0][0] == 1:
        print("⚠️ Prediction: Brain Tumor DETECTED (Positive)")
    else:
        print("✅ Prediction: No Tumor Detected (Negative/Healthy)")

if __name__ == "__main__":
    # ১. মডেল তৈরি করা
    ai_model = build_brain_tumor_model()
    
    # (বাস্তবে এখানে model.fit() ব্যবহার করে হাজার হাজার ছবি দিয়ে ট্রেনিং করাতে হয়)
    print("\nModel is ready for training! (Dataset required)")
    print("-" * 50)
    
    # ২. নতুন এমআরআই ইমেজ টেস্ট করা
    # ধরে নিচ্ছি 'sample_mri.jpg' নামে একটি স্ক্যান ফাইল আপনার ফোল্ডারে আছে
    test_image_path = 'sample_mri.jpg'
    
    try:
        predict_mri(ai_model, test_image_path)
    except FileNotFoundError:
        print("Error: 'sample_mri.jpg' image not found. Please provide an MRI image to test.")
```

### কোডটি কীভাবে শিখবেন?
1. **Convolutional Neural Network (CNN):** `Conv2D` এবং `MaxPooling2D` হলো সিএনএন (CNN) এর প্রধান অংশ। এগুলো মূলত একটি ইমেজের ওপর ফিল্টার বসিয়ে ইমেজের বর্ডার, শেপ (Shape) এবং কালার প্যাটার্ন আলাদা করে বুঝতে সাহায্য করে।
2. **Dense Layer:** `Flatten()` করার পর ডেটাগুলো `Dense` লেয়ার বা সাধারণ নিউরাল নেটওয়ার্কে যায়। এখানেই মূলত সিদ্ধান্ত নেওয়া হয় যে ইমেজে টিউমারের বৈশিষ্ট্য আছে কি নেই। 
3. **Activation Function:** শেষ লেয়ারে `sigmoid` ব্যবহার করা হয়েছে। কারণ আমাদের উত্তর দরকার শুধু দুটি—হয় টিউমার আছে (১), নাহলে নেই (০)। সিগময়েড ফাংশন সব রেজাল্টকে ০ এবং ১ এর মাঝে নিয়ে আসে। 
4. **Data Preprocessing:** এআই মডেল সব ছবি এক সাইজে প্রসেস করতে পছন্দ করে। তাই `image.load_img(..., target_size=(64, 64))` দিয়ে আমরা যেকোনো সাইজের ছবিকে 64x64 পিক্সেল সাইজে কনভার্ট করে নিচ্ছি।
