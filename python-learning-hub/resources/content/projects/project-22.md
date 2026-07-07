## ২১. লাইভ প্রজেক্ট: স্মার্ট হ্যান্ড-জেসচার ভলিউম কন্ট্রোলার

কম্পিউটার ভিশনের জগতে এটি একটি খুবই জনপ্রিয় এবং ইন্টারেক্টিভ প্রজেক্ট। এই প্রজেক্টে আপনার ওয়েবক্যাম চালু হবে এবং এটি আপনার হাতের আঙুল ট্র্যাক করবে। আপনি আপনার বৃদ্ধাঙ্গুলি (Thumb) এবং তর্জনী (Index finger) কাছাকাছি আনলে কম্পিউটারের সাউন্ড কমে যাবে, আর দূরে সরালে সাউন্ড বেড়ে যাবে ঠিক সাইন্স ফিকশন মুভির মতো!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের বেশ কয়েকটি পাওয়ারফুল লাইব্রেরি লাগবে:
1. **opencv-python:** ওয়েবক্যাম চালু করে ভিডিও ফ্রেম পড়ার জন্য।
2. **mediapipe:** গুগলের তৈরি এই লাইব্রেরিটি হাতের আঙুল ট্র্যাক করতে পারে।
3. **pycaw:** উইন্ডোজের অডিও (ভলিউম) কন্ট্রোল করার জন্য।
4. **comtypes:** pycaw এর সাথে উইন্ডোজের ইন্টারঅ্যাকশনের জন্য।
5. **math:** আঙুলের মাঝের দূরত্ব মাপার জন্য (পাইথনের বিল্ট-ইন)।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install opencv-python mediapipe pycaw comtypes
```

### প্রজেক্টের কোড:

এই প্রোগ্রামে আমরা `mediapipe` দিয়ে হাতের ৪নং (বৃদ্ধাঙ্গুলির মাথা) এবং ৮নং (তর্জনীর মাথা) পয়েন্ট দুটি ট্র্যাক করে তাদের মাঝের দূরত্ব বের করবো এবং সেই অনুযায়ী উইন্ডোজের ভলিউম সেট করবো।

```python
import cv2
import mediapipe as mp
import math
import numpy as np
from ctypes import cast, POINTER
from comtypes import CLSCTX_ALL
from pycaw.pycaw import AudioUtilities, IAudioEndpointVolume

# ওয়েবক্যাম চালু করা
cap = cv2.VideoCapture(0)

# MediaPipe এর হ্যান্ড ট্র্যাকিং মডিউল সেটআপ করা
mpHands = mp.solutions.hands
hands = mpHands.Hands(min_detection_confidence=0.7)
mpDraw = mp.solutions.drawing_utils

# PyCaw ব্যবহার করে উইন্ডোজ অডিও কন্ট্রোলার সেটআপ
devices = AudioUtilities.GetSpeakers()
interface = devices.Activate(IAudioEndpointVolume._iid_, CLSCTX_ALL, None)
volume = cast(interface, POINTER(IAudioEndpointVolume))

# ভলিউম রেঞ্জ বের করা (-65.25 থেকে 0.0 পর্যন্ত)
volRange = volume.GetVolumeRange()
minVol = volRange[0]
maxVol = volRange[1]

while True:
    success, img = cap.read()
    if not success:
        break
        
    # ছবিকে RGB তে কনভার্ট করা (যেহেতু MediaPipe RGB সাপোর্ট করে)
    imgRGB = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    results = hands.process(imgRGB)
    
    if results.multi_hand_landmarks:
        for handLms in results.multi_hand_landmarks:
            # বৃদ্ধাঙ্গুলির মাথা (Point 4) এবং তর্জনীর মাথা (Point 8) এর অবস্থান বের করা
            lmList = []
            for id, lm in enumerate(handLms.landmark):
                h, w, c = img.shape
                cx, cy = int(lm.x * w), int(lm.y * h)
                lmList.append([id, cx, cy])
            
            if len(lmList) != 0:
                x1, y1 = lmList[4][1], lmList[4][2] # Thumb
                x2, y2 = lmList[8][1], lmList[8][2] # Index Finger
                
                # আঙুলের মাথায় বৃত্ত আঁকা
                cv2.circle(img, (x1, y1), 10, (255, 0, 255), cv2.FILLED)
                cv2.circle(img, (x2, y2), 10, (255, 0, 255), cv2.FILLED)
                # দুই আঙুলের মাঝে একটি লাইন টানা
                cv2.line(img, (x1, y1), (x2, y2), (255, 0, 255), 3)
                
                # দুই আঙুলের মাঝের দূরত্ব মাপা
                length = math.hypot(x2 - x1, y2 - y1)
                
                # দূরত্ব অনুযায়ী ভলিউম সেট করা
                # আঙুলের দূরত্ব সাধারণত 20 থেকে 200 এর মধ্যে হয়
                vol = np.interp(length, [20, 200], [minVol, maxVol])
                volume.SetMasterVolumeLevel(vol, None)
                
                # খুব কাছাকাছি আনলে বৃত্তের রঙ সবুজ করে দেওয়া
                if length < 30:
                    cv2.circle(img, ((x1+x2)//2, (y1+y2)//2), 10, (0, 255, 0), cv2.FILLED)

            # হাতের সব জয়েন্টগুলো স্ক্রিনে আঁকা
            mpDraw.draw_landmarks(img, handLms, mpHands.HAND_CONNECTIONS)

    # ভিডিও দেখানো
    cv2.imshow("Hand Gesture Volume Control", img)
    
    # 'q' চাপলে প্রোগ্রাম বন্ধ হবে
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
```

> [!TIP] 
> **বিঃদ্রঃ** কোডটি রান করলে আপনার ওয়েবক্যাম অন হবে। এবার আপনার হাত ক্যামেরার সামনে এনে তর্জনী (Index finger) এবং বৃদ্ধাঙ্গুলি (Thumb) কাছাকাছি আনলে দেখবেন কম্পিউটারের সাউন্ড কমে যাচ্ছে, আর দূরে সরালে সাউন্ড ফুল হয়ে যাচ্ছে!

---