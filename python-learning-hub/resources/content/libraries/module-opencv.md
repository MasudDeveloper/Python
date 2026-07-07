# OpenCV (Zero to Hero) কমপ্লিট গাইড

রোবট কীভাবে রাস্তা চেনে? টেসলা (Tesla) গাড়ি কীভাবে বুঝে সামনে মানুষ আছে নাকি গাড়ি? সিকিউরিটি ক্যামেরা কীভাবে মানুষের চেহারা ডিটেক্ট করে? 

এই সমস্ত জাদুকরী কাজ (যাকে Computer Vision বলা হয়) করার জন্য পৃথিবীর সবচেয়ে পাওয়ারফুল এবং পপুলার লাইব্রেরি হলো **OpenCV** (Open Source Computer Vision)। এটি মূলত C++ এ তৈরি, তবে পাইথনে আমরা মাত্র কয়েক লাইন কোড লিখেই যেকোনো ভিডিও বা ছবি প্রসেস করতে পারি!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ছবি লোড করা থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের ওয়েবক্যাম দিয়ে লাইভ ফেস ডিটেকশন (Face Detection) এবং অবজেক্ট ট্র্যাকিং (Object Tracking) পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং ছবি পড়া (Read & Show Image)
প্রথমে লাইব্রেরিটি ইনস্টল করে নিতে হবে:
```bash
pip install opencv-python
```
*(বিঃদ্রঃ প্যাকেজের নাম opencv-python হলেও কোড লেখার সময় একে `cv2` নামে ইমপোর্ট করতে হয়।)*

```python
import cv2

# ১. ছবি লোড করা (ফোল্ডারে 'photo.jpg' নামে একটি ছবি থাকতে হবে)
# OpenCV ছবিকে NumPy অ্যারে (Matrix) হিসেবে রিড করে!
img = cv2.imread('photo.jpg')

# ছবির সাইজ এবং ডাইমেনশন দেখা
print("Image Shape:", img.shape) # (Height, Width, Channels)

# ২. ছবি স্ক্রিনে দেখানো ('Window Name' হলো উইন্ডোর টাইটেল)
cv2.imshow('My Image', img)

# ৩. ইউজারের কোনো বাটন প্রেস করা পর্যন্ত ওয়েট করা (0 মানে অনন্তকাল)
cv2.waitKey(0)

# ৪. কাজ শেষে উইন্ডো ক্লোজ করা
cv2.destroyAllWindows()
```

### ২. নতুন ছবি সেভ করা (Write Image)
আপনি চাইলে ছবির কালার বা সাইজ পরিবর্তন করে নতুন ফাইল হিসেবে সেভ করতে পারেন।

```python
import cv2

img = cv2.imread('photo.jpg')

# ছবিটিকে PNG ফরমেটে সেভ করা
cv2.imwrite('new_photo.png', img)
print("Image saved successfully!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. রিসাইজ এবং ক্রপ করা (Resize & Crop)
যেহেতু OpenCV তে ছবি মূলত একটি NumPy অ্যারে, তাই আমরা লিস্ট স্লাইসিং (Slicing) করেই ছবি ক্রপ করতে পারি!

```python
import cv2

img = cv2.imread('photo.jpg')

# ১. রিসাইজ (Resize) করা (Width 500, Height 300)
resized_img = cv2.resize(img, (500, 300))
cv2.imshow('Resized', resized_img)

# ২. ক্রপ (Crop) করা 
# যেহেতু এটি ম্যাট্রিক্স, তাই [Start_Row:End_Row, Start_Col:End_Col] বা [y1:y2, x1:x2]
cropped_img = img[50:200, 100:300]
cv2.imshow('Cropped', cropped_img)

cv2.waitKey(0)
cv2.destroyAllWindows()
```

### ৪. ব্ল্যাক এন্ড হোয়াইট এবং এজ ডিটেকশন (Grayscale & Edges)
এআই (AI) মডেলকে ছবি বোঝানোর জন্য কালার ছবির চেয়ে ব্ল্যাক এন্ড হোয়াইট ছবি দেওয়া ভালো, এতে প্রসেসিং ফাস্ট হয়। আর Edge (কিনারা) ডিটেকশন সেলফ-ড্রাইভিং গাড়ির জন্য খুব জরুরি।

```python
import cv2

img = cv2.imread('photo.jpg')

# ১. ছবিকে Grayscale বা সাদাকালোতে কনভার্ট করা (OpenCV তে কালার BGR সিরিয়ালে থাকে, RGB তে নয়)
gray_img = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

# ২. ছবি ঘোলা বা ব্লার (Blur) করা (নয়েজ কমানোর জন্য)
blur_img = cv2.GaussianBlur(gray_img, (7, 7), 0)

# ৩. ছবির এজ (Edge) বা কিনারার লাইনগুলো বের করা (Canny Edge Detection)
# ১০০ এবং ২০০ হলো থ্রেশহোল্ড ভ্যালু
edges = cv2.Canny(blur_img, 100, 200)

cv2.imshow('Edges', edges)
cv2.waitKey(0)
```

### ৫. ছবির ওপর আঁকাআঁকি করা (Draw & Text)
মেশিন লার্নিং মডেলে যখন কোনো অবজেক্ট ডিটেক্ট হয়, তখন তার চারপাশে আমরা একটি বক্স আঁকি এবং নাম লিখি।

```python
import cv2
import numpy as np

# একটি সম্পূর্ণ কালো ছবি (500x500) তৈরি করা (NumPy দিয়ে)
img = np.zeros((500, 500, 3), dtype='uint8')

# ১. চারকোনা বক্স বা Rectangle আঁকা
# প্যারামিটার: ছবি, শুরুর পয়েন্ট (x, y), শেষের পয়েন্ট, কালার (B, G, R), থিকনেস (পূরক করতে -1)
cv2.rectangle(img, (100, 100), (300, 300), (0, 255, 0), 3)

# ২. বৃত্ত বা Circle আঁকা
# প্যারামিটার: ছবি, সেন্টার পয়েন্ট, ব্যাসার্ধ, কালার, থিকনেস
cv2.circle(img, (250, 250), 50, (0, 0, 255), -1)

# ৩. টেক্সট লেখা
cv2.putText(img, "OpenCV is Awesome!", (50, 50), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 255), 2)

cv2.imshow('Drawing', img)
cv2.waitKey(0)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. ওয়েবক্যাম দিয়ে লাইভ ভিডিও দেখা (Live Video Capture)
OpenCV দিয়ে ভিডিও প্রসেস করা মানে হলো একটি `while` লুপের ভেতর খুব দ্রুত একটার পর একটা ছবি (Frame) দেখানো!

```python
import cv2

# 0 মানে কম্পিউটারের ডিফল্ট ওয়েবক্যাম, 1 মানে এক্সটারনাল ক্যামেরা
# ভিডিও ফাইলের ক্ষেত্রে 0 এর বদলে ফাইলের নাম ('movie.mp4') দিতে হয়
cap = cv2.VideoCapture(0)

while True:
    # ক্যামেরা থেকে একটি ফ্রেম (ছবি) রিড করা
    success, frame = cap.read()
    
    if not success:
        break
        
    # ফ্রেমটি স্ক্রিনে দেখানো
    cv2.imshow('Live Camera', frame)
    
    # 'q' বাটন প্রেস করলে লুপ বা ক্যামেরা বন্ধ হয়ে যাবে
    # cv2.waitKey(1) মানে ১ মিলি-সেকেন্ড অপেক্ষা করে পরের ফ্রেমে যাওয়া
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# কাজ শেষে ক্যামেরা রিলিজ করে দেওয়া
cap.release()
cv2.destroyAllWindows()
```

### ৭. কালার ট্র্যাকিং (Color Tracking Object Detection)
ধরি, আপনি এমন একটি রোবট বানাতে চান যা শুধু লাল রঙের বলকে ফলো করবে। এই কাজে HSV কালার স্পেস ব্যবহার করা হয়।

```python
import cv2
import numpy as np

cap = cv2.VideoCapture(0)

while True:
    _, frame = cap.read()
    
    # কালার ডিটেক্ট করার জন্য BGR থেকে HSV (Hue, Saturation, Value) তে কনভার্ট করতে হয়
    hsv = cv2.cvtColor(frame, cv2.COLOR_BGR2HSV)
    
    # নীল রঙের লিমিট (Lower & Upper) সেট করা
    lower_blue = np.array([90, 50, 50])
    upper_blue = np.array([130, 255, 255])
    
    # মাস্ক (Mask) তৈরি করা (শুধু নীল রঙের জিনিসগুলো সাদা দেখাবে, বাকি সব কালো)
    mask = cv2.inRange(hsv, lower_blue, upper_blue)
    
    # মাস্কটিকে মূল ছবির ওপর বসিয়ে দেওয়া
    result = cv2.bitwise_and(frame, frame, mask=mask)
    
    cv2.imshow('Original', frame)
    cv2.imshow('Mask', mask)
    cv2.imshow('Color Tracker', result)
    
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
```

### ৮. লাইভ ফেস ডিটেকশন (Haar Cascade Face Detection)
OpenCV তে আগে থেকেই মানুষের চেহারা, চোখ বা গাড়ি চেনার জন্য কিছু প্রি-ট্রেইনড মডেল দেওয়া থাকে, যাদেরকে **Haar Cascades** বলা হয়। চলুন আপনার ওয়েবক্যাম দিয়ে ফেস ডিটেক্ট করি!

```python
import cv2

# ফেস ডিটেক্ট করার রেডিমেড মডেলটি (XML ফাইল) লোড করা
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

cap = cv2.VideoCapture(0)

while True:
    success, img = cap.read()
    
    # ফেস ডিটেকশনের জন্য ছবিটিকে প্রথমে সাদাকালো (Grayscale) করতে হয়
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    
    # ছবি থেকে সবগুলো ফেস খুঁজে বের করা
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=4)
    
    # প্রতিটি ফেসের চারদিকে একটি চারকোনা বক্স (Rectangle) আঁকা
    for (x, y, w, h) in faces:
        # (0, 255, 0) মানে সবুজ রঙের বক্স
        cv2.rectangle(img, (x, y), (x + w, y + h), (0, 255, 0), 2)
        
        # ফেসের ওপর টেক্সট লেখা
        cv2.putText(img, "Human Face", (x, y-10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
        
    cv2.imshow('AI Face Detection', img)
    
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
```

### সারসংক্ষেপ (Conclusion)
যদিও বর্তমানে ডিপ লার্নিং (YOLO, TensorFlow) দিয়ে ইমেজ প্রসেসিংয়ের অনেক কঠিন কাজ করা হয়, কিন্তু যেকোনো ভিডিও বা ইমেজকে রিড করা, ক্রপ করা, বা কালার স্পেস পরিবর্তন করার জন্য পুরো দুনিয়ার ডেটা সায়েন্টিস্টরা এই **OpenCV**-ই ব্যবহার করেন। এটি ছাড়া কম্পিউটার ভিশন বা এআই (AI) এর কথা চিন্তাও করা যায় না!
