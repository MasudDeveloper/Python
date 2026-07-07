# ৪২. রিয়েল-টাইম ফেস রিকগনিশন সিকিউরিটি (Real-time Face Recognition)

স্মার্টফোন আনলক করা থেকে শুরু করে এয়ারপোর্টের ইমিগ্রেশন—সবখানেই এখন ফেস রিকগনিশন (Face Recognition) বা চেহারা শনাক্তকরণ প্রযুক্তির ব্যবহার হচ্ছে। 

এই প্রজেক্টে আমরা পাইথনের `OpenCV` এবং `DeepFace` লাইব্রেরি ব্যবহার করে একটি স্মার্ট সিকিউরিটি ক্যামেরা বানাবো। এটি আপনার ওয়েবক্যামের সামনে আসা মানুষের চেহারা স্ক্যান করবে। যদি সে আপনাকে চেনে (অর্থাৎ ডেটাবেসে আপনার ছবি থাকে), তবে স্ক্রিনে আপনার নাম দেখাবে। আর যদি অচেনা কেউ হয়, তবে সাথে সাথে অ্যালার্ম (Alarm) বাজিয়ে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **Reference Image:** প্রথমে আপনার একটি পরিষ্কার ছবি (যেমন `my_photo.jpg`) একটি ফোল্ডারে রাখতে হবে, যা এআই এর কাছে ডেটাবেস বা রেফারেন্স হিসেবে কাজ করবে।
2. **Webcam Feed:** `OpenCV` ব্যবহার করে আমরা রিয়েল-টাইমে ল্যাপটপের ওয়েবক্যাম থেকে ভিডিও ক্যাপচার করবো।
3. **Face Matching:** ভিডিওর প্রতিটি ফ্রেম (Frame) বা ছবিকে `DeepFace` এর কাছে পাঠানো হবে। সে আপনার রেফারেন্স ছবির সাথে ওয়েবক্যামের ছবি মেলাবে (Verification)।
4. **Action:** চেহারা মিলে গেলে সবুজ কালারে "MATCHED" দেখাবে। না মিললে লাল কালারে "UNKNOWN" দেখাবে এবং কম্পিউটারের স্পিকার দিয়ে একটি বিকট সাইরেন বা বিপ (Beep) সাউন্ড বাজাবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:

```bash
pip install opencv-python deepface
```
*(বিঃদ্রঃ অ্যালার্ম বাজানোর জন্য আমরা উইন্ডোজের ডিফল্ট `winsound` লাইব্রেরি ব্যবহার করবো, তাই এক্সট্রা কিছু ইনস্টল করতে হবে না।)*

### প্রজেক্টের কোড:

ধরে নিচ্ছি আপনার প্রজেক্ট ফোল্ডারে `my_photo.jpg` নামে আপনার একটি রেফারেন্স ছবি আছে। নিচের কোডটি কপি করে রান করুন।

```python
import cv2
import threading
from deepface import DeepFace
import winsound  # শুধুমাত্র উইন্ডোজের জন্য

# গ্লোবাল ভ্যারিয়েবল
face_match = False
check_in_progress = False

# আপনার রেফারেন্স ছবির পাথ
REFERENCE_IMG_PATH = "my_photo.jpg"
reference_img = cv2.imread(REFERENCE_IMG_PATH)

if reference_img is None:
    print(f"Error: Cannot find '{REFERENCE_IMG_PATH}'. Please put a reference image in the folder.")
    exit()

def check_face(frame):
    """DeepFace ব্যবহার করে ফেস চেক করার ব্যাকগ্রাউন্ড ফাংশন"""
    global face_match, check_in_progress
    try:
        # VGG-Face বা Facenet মডেল ব্যবহার করে চেহারা মেলানো হচ্ছে
        result = DeepFace.verify(frame, reference_img.copy(), enforce_detection=False)
        face_match = result['verified']
    except ValueError:
        face_match = False
    finally:
        check_in_progress = False

def face_recognition_system():
    global check_in_progress, face_match
    print("=== Starting Smart Face Recognition Security ===")
    
    # ওয়েবক্যাম চালু করা
    cap = cv2.VideoCapture(0)
    # রেজ্যুলেশন কমানো হচ্ছে যাতে এআই দ্রুত প্রসেস করতে পারে
    cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    # ফেস ডিটেক্ট করার জন্য OpenCV এর বিল্ট-ইন হাআরক্যাসকেড (HaarCascade) মডেল
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

    while True:
        success, frame = cap.read()
        if not success:
            break
            
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        
        # ক্যামেরায় কোনো ফেস আছে কি না তা আগে চেক করা
        faces = face_cascade.detectMultiScale(gray, 1.1, 4)

        if len(faces) > 0:
            # যদি ক্যামেরায় ফেস পাওয়া যায় এবং আগে থেকে কোনো চেকিং না চলতে থাকে
            if not check_in_progress:
                check_in_progress = True
                # থ্রেডিং ব্যবহার করা হচ্ছে যাতে ভিডিও আটকে (Lag) না যায়
                threading.Thread(target=check_face, args=(frame.copy(),)).start()

            # ফেসের চারপাশে বক্স ড্র করা
            for (x, y, w, h) in faces:
                if face_match:
                    # চেনা মানুষ হলে সবুজ বক্স এবং নাম
                    cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
                    cv2.putText(frame, "MATCHED: OWNER", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)
                else:
                    # অচেনা মানুষ হলে লাল বক্স, ওয়ার্নিং এবং অ্যালার্ম
                    cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 0, 255), 2)
                    cv2.putText(frame, "UNKNOWN! ALARM!", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 0, 255), 2)
                    
                    # অ্যালার্ম সাউন্ড (Beep) বাজানো
                    winsound.Beep(2000, 200) # (Frequency: 2000Hz, Duration: 200ms)

        # স্ক্রিনে ভিডিও দেখানো
        cv2.imshow("Smart Security Camera", frame)
        
        # 'q' চাপলে প্রোগ্রাম বন্ধ হবে
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    face_recognition_system()
```

### কোডটি কীভাবে শিখবেন?
1. **HaarCascade (ফেস ডিটেকশন):** এআইকে পুরো স্ক্রিন জুড়ে চেহারা খুঁজতে দিলে অনেক সময় লাগবে। তাই আমরা প্রথমে OpenCV এর হালকা (Lightweight) `HaarCascade` মডেল দিয়ে শুধু খুঁজে বের করেছি স্ক্রিনের কোথায় ফেসটি আছে। 
2. **DeepFace.verify:** এটি হলো আসল ম্যাজিক! এটি দুটি ছবি (ক্যামেরার ফ্রেম এবং রেফারেন্স ছবি) ইনপুট নেয় এবং একটি নিউরাল নেটওয়ার্কের মাধ্যমে চেক করে যে দুটি ছবির মানুষ একই কি না।
3. **Threading (থ্রেডিং):** `DeepFace` যখন ছবি মেলায়, তখন প্রসেসরকে কিছুটা সময় নিতে হয়। আমরা যদি থ্রেডিং ব্যবহার না করতাম, তবে প্রতিবার চেহারা মেলানোর সময় আপনার ক্যামেরার ভিডিও হ্যাং বা ফ্রিজ হয়ে যেত। `threading.Thread` ব্যবহার করায় এটি ব্যাকগ্রাউন্ডে চেক করতে থাকে, আর ভিডিও একদম স্মুথলি চলতে থাকে!
4. **winsound.Beep:** উইন্ডোজের স্পিকারকে সরাসরি কমান্ড দিয়ে বিপ সাউন্ড তৈরি করার জন্য এটি ব্যবহৃত হয়, যা এই প্রজেক্টে অ্যালার্ম হিসেবে কাজ করছে।
