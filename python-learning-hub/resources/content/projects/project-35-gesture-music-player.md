# ৩৫. ইশারা দিয়ে মিউজিক প্লেয়ার কন্ট্রোল (Gesture Music Player)

ভেবে দেখুন তো, আপনি কিবোর্ড বা মাউস না ধরে শুধু ওয়েবক্যামের সামনে হাত নেড়েই কম্পিউটারের গান প্লে, পজ বা নেক্সট করতে পারছেন! সায়েন্স ফিকশন মুভির মতো এই বিষয়টি আমরা পাইথন দিয়ে খুব সহজেই তৈরি করতে পারি। 

এই প্রজেক্টে আমরা গুগলের তৈরি **MediaPipe** (হাতের ইশারা ট্র্যাক করার জন্য) এবং **OpenCV** (ক্যামেরা অন করার জন্য) ব্যবহার করবো। আর গান বাজানোর জন্য ব্যবহার করবো **Pygame** লাইব্রেরি।

### কীভাবে কাজ করে? (How it works):
1. **Hand Tracking:** প্রথমে ওয়েবক্যাম চালু করে `MediaPipe` এর মাধ্যমে আমাদের হাতের ২১টি পয়েন্ট বা জয়েন্ট (Landmarks) ট্র্যাক করা হবে।
2. **Gesture Recognition:** আমরা লজিক সেট করবো যে, তর্জনী (Index Finger) এবং বুড়ো আঙুল (Thumb) একসাথে জোড়া লাগালে গান প্লে বা পজ হবে। আর হাত ডানে বা বামে সরালে নেক্সট বা প্রিভিয়াস গানে চলে যাবে।
3. **Music Control:** হাতের পজিশন বা ইশারার ওপর ভিত্তি করে `Pygame` এর মাধ্যমে মিউজিক কন্ট্রোল করা হবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:

```bash
pip install opencv-python mediapipe pygame
```

### প্রজেক্টের কোড:

ধরে নিচ্ছি আপনার প্রজেক্ট ফোল্ডারে `song.mp3` নামে একটি গানের অডিও ফাইল আছে। নিচের কোডটি রান করলে আপনার ওয়েবক্যাম ওপেন হবে:

```python
import cv2
import mediapipe as mp
import pygame
import math
import time

def gesture_music_player():
    print("Initializing Gesture Music Player...")
    
    # গান প্লে করার জন্য Pygame মিক্সার চালু করা
    pygame.mixer.init()
    try:
        pygame.mixer.music.load("song.mp3")
        pygame.mixer.music.play()
        print("Music Started! Show your hand to the camera.")
    except Exception as e:
        print("Error loading music. Ensure 'song.mp3' exists in the folder.")
        return

    # ওয়েবক্যাম এবং মিডিয়াপাইপ চালু করা
    cap = cv2.VideoCapture(0)
    mp_hands = mp.solutions.hands
    hands = mp_hands.Hands(min_detection_confidence=0.7)
    mp_draw = mp.solutions.drawing_utils
    
    is_playing = True
    last_action_time = time.time()

    while True:
        success, img = cap.read()
        if not success:
            break
            
        # ইমেজকে মিডিয়াপাইপ বোঝার জন্য RGB কালারে কনভার্ট করা
        img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        result = hands.process(img_rgb)
        
        if result.multi_hand_landmarks:
            for hand_landmarks in result.multi_hand_landmarks:
                # হাতের ল্যান্ডমার্কগুলো ড্র করা (আঁকা)
                mp_draw.draw_landmarks(img, hand_landmarks, mp_hands.HAND_CONNECTIONS)
                
                # বুড়ো আঙুল (৪ নং পয়েন্ট) এবং তর্জনীর (৮ নং পয়েন্ট) পজিশন বের করা
                thumb_tip = hand_landmarks.landmark[4]
                index_tip = hand_landmarks.landmark[8]
                
                h, w, c = img.shape
                # পিক্সেল কোঅর্ডিনেট বের করা
                tx, ty = int(thumb_tip.x * w), int(thumb_tip.y * h)
                ix, iy = int(index_tip.x * w), int(index_tip.y * h)
                
                # দুই আঙুলের মাঝের দূরত্ব মাপা
                distance = math.hypot(tx - ix, ty - iy)
                
                # যদি দূরত্ব খুব কম হয় (আঙুল জোড়া লাগানো থাকে) এবং আগের অ্যাকশনের পর ২ সেকেন্ড পার হয়
                current_time = time.time()
                if distance < 40 and (current_time - last_action_time > 2.0):
                    if is_playing:
                        pygame.mixer.music.pause()
                        print("Gesture Detected: Music PAUSED")
                        is_playing = False
                    else:
                        pygame.mixer.music.unpause()
                        print("Gesture Detected: Music PLAYED")
                        is_playing = True
                    
                    last_action_time = current_time

        # স্ক্রিনে ভিডিও দেখানো
        cv2.imshow("Gesture Music Player", img)
        
        # 'q' চাপলে প্রোগ্রাম বন্ধ হবে
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    # প্রোগ্রাম বন্ধ করার সময় সব কিছু ক্লিন করা
    cap.release()
    cv2.destroyAllWindows()
    pygame.mixer.quit()

if __name__ == "__main__":
    gesture_music_player()
```

### কোডটি কীভাবে শিখবেন?
1. **Pygame Mixer:** গেম বানানোর পাশাপাশি `pygame.mixer` পাইথনের অন্যতম সেরা অডিও প্লেয়ার লাইব্রেরি। এটি দিয়ে খুব সহজেই যেকোনো অডিও ফাইল লোড, প্লে এবং পজ করা যায়।
2. **MediaPipe Landmarks:** মিডিয়াপাইপ আমাদের হাতের কবজি থেকে শুরু করে আঙুলের মাথা পর্যন্ত মোট ২১টি পয়েন্ট (০ থেকে ২০) ট্র্যাক করে। এখানে আমরা `৪` (বুড়ো আঙুলের মাথা) এবং `৮` (তর্জনীর মাথা) ব্যবহার করেছি।
3. **Math Hypot (পিথাগোরাসের সূত্র):** দুই আঙুলের মাথার কোঅর্ডিনেট (x, y) বের করার পর তাদের মধ্যকার দূরত্ব মাপার জন্য `math.hypot()` বা পিথাগোরাসের সূত্র ব্যবহার করা হয়েছে। দূরত্ব ৪০ পিক্সেলের কম হলেই আমরা বুঝতে পারি আঙুলগুলো জোড়া লেগেছে (Click gesture)।
4. **Action Delay:** `time.time()` ব্যবহার করে একটি লজিক দেওয়া হয়েছে যেন একবার প্লে/পজ হওয়ার পর অন্তত ২ সেকেন্ড অপেক্ষা করে। নাহলে আঙুল জোড়া অবস্থায় থাকলে প্রতি মিলি-সেকেন্ডে প্লে-পজ হতে থাকবে!
