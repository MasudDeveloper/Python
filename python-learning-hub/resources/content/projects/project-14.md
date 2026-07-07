## ১৩. লাইভ প্রজেক্ট: ফেস রিকগনিশন অ্যাটেনডেন্স সিস্টেম

এটি একটি খুবই অ্যাডভান্সড এবং জনপ্রিয় প্রজেক্ট। কম্পিউটারের ওয়েবক্যাম দিয়ে মানুষের মুখ শনাক্ত করে কে উপস্থিত আছে তা রিয়েল-টাইমে একটি ফাইলে (CSV) সেভ করাই হলো এই প্রজেক্টের কাজ। এটি স্কুল বা অফিসে হাজিরা নেওয়ার জন্য ব্যবহার করা যায়।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ফেস রিকগনিশন এবং কম্পিউটার ভিশনের লাইব্রেরি লাগবে:
1. **opencv-python:** ওয়েবক্যাম চালু করা এবং ইমেজ প্রসেস করার জন্য।
2. **face_recognition:** মানুষের মুখ শনাক্ত করার জন্য (সবচেয়ে সহজ এবং শক্তিশালী লাইব্রেরি)।
3. **numpy:** ইমেজের ম্যাট্রিক্স ক্যালকুলেশন দ্রুত করার জন্য।
4. **datetime:** কখন হাজিরা দেওয়া হয়েছে সেই সময়টি বের করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install opencv-python face_recognition numpy
```

> [!WARNING] 
> **নোট:** `face_recognition` লাইব্রেরিটি উইন্ডোজে ইনস্টল করতে হলে আপনার পিসিতে **C++ Build Tools** বা **Visual Studio** ইনস্টল করা থাকতে হবে। এছাড়া `dlib` লাইব্রেরি আগে ইনস্টল করে নিতে হয়। এটি ইনস্টল করতে সমস্যা হলে ইউটিউবে "How to install face_recognition in python windows" লিখে ভিডিও দেখে নিতে পারেন।

### প্রজেক্টের কোড:

প্রথমে প্রজেক্টের একই ফোল্ডারে `images` নামে একটি ফোল্ডার তৈরি করুন এবং সেখানে যাদের মুখ চিনতে হবে তাদের পরিষ্কার ছবি রাখুন (যেমন: `rahim.jpg`, `karim.jpg`)।

```python
import cv2
import numpy as np
import face_recognition
import os
from datetime import datetime

# যে ফোল্ডারে ছবিগুলো আছে তার নাম
path = 'images'
images = []
classNames = []
myList = os.listdir(path)

# ফোল্ডার থেকে সব ছবি পড়া এবং নামগুলো আলাদা করা
for cl in myList:
    curImg = cv2.imread(f'{path}/{cl}')
    images.append(curImg)
    classNames.append(os.path.splitext(cl)[0]) # .jpg অংশটুকু বাদ দেওয়া

def findEncodings(images):
    """ছবিগুলো থেকে মুখের ফিচার বা এনকোডিং বের করার ফাংশন"""
    encodeList = []
    for img in images:
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        encode = face_recognition.face_encodings(img)[0]
        encodeList.append(encode)
    return encodeList

def markAttendance(name):
    """হাজিরা একটি CSV ফাইলে সেভ করার ফাংশন"""
    with open('Attendance.csv', 'r+') as f:
        myDataList = f.readlines()
        nameList = []
        for line in myDataList:
            entry = line.split(',')
            nameList.append(entry[0])
            
        # যদি আজকের দিনে আগে হাজিরা না দিয়ে থাকে
        if name not in nameList:
            now = datetime.now()
            dtString = now.strftime('%H:%M:%S')
            f.writelines(f'\n{name},{dtString}')

# সেভ করা ছবিগুলোর ফিচার বের করা
encodeListKnown = findEncodings(images)
print('Encoding Complete! Starting Webcam...')

# ওয়েবক্যাম চালু করা (0 মানে ডিফল্ট ক্যামেরা)
cap = cv2.VideoCapture(0)

while True:
    success, img = cap.read()
    # ভিডিওকে ছোট করে নেওয়া যাতে প্রসেসিং ফাস্ট হয়
    imgS = cv2.resize(img, (0, 0), None, 0.25, 0.25)
    imgS = cv2.cvtColor(imgS, cv2.COLOR_BGR2RGB)

    # বর্তমান ফ্রেমে মুখ খোঁজা
    facesCurFrame = face_recognition.face_locations(imgS)
    encodesCurFrame = face_recognition.face_encodings(imgS, facesCurFrame)

    for encodeFace, faceLoc in zip(encodesCurFrame, facesCurFrame):
        # ডাটাবেসের মুখের সাথে বর্তমান ফ্রেমের মুখের মিল খোঁজা
        matches = face_recognition.compare_faces(encodeListKnown, encodeFace)
        faceDis = face_recognition.face_distance(encodeListKnown, encodeFace)
        
        # যার সাথে সবচেয়ে বেশি মিল পাওয়া গেছে তার ইনডেক্স বের করা
        matchIndex = np.argmin(faceDis)

        if matches[matchIndex]:
            name = classNames[matchIndex].upper()
            
            # মুখের চারপাশে একটি বক্স আঁকা
            y1, x2, y2, x1 = faceLoc
            y1, x2, y2, x1 = y1 * 4, x2 * 4, y2 * 4, x1 * 4
            cv2.rectangle(img, (x1, y1), (x2, y2), (0, 255, 0), 2)
            cv2.rectangle(img, (x1, y2 - 35), (x2, y2), (0, 255, 0), cv2.FILLED)
            cv2.putText(img, name, (x1 + 6, y2 - 6), cv2.FONT_HERSHEY_COMPLEX, 1, (255, 255, 255), 2)
            
            # হাজিরা মার্ক করা
            markAttendance(name)

    # স্ক্রিনে ভিডিও দেখানো
    cv2.imshow('Webcam', img)
    # 'q' চাপলে প্রোগ্রাম বন্ধ হবে
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break
        
cap.release()
cv2.destroyAllWindows()
```

---