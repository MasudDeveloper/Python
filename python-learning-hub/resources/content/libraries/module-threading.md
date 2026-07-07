# Threading (Zero to Hero) কমপ্লিট গাইড

পাইথনে সাধারণত কোড এক লাইন এক লাইন করে রান হয়। অর্থাৎ, একটি লাইনের কাজ শেষ না হওয়া পর্যন্ত পরের লাইন কাজ শুরু করতে পারে না। 

ধরুন, আপনি ইন্টারনেট থেকে ১০টি বড় সাইজের ছবি ডাউনলোড করার একটি কোড লিখেছেন এবং প্রতিটি ছবি নামতে ৫ সেকেন্ড সময় লাগে। সাধারণ কোডে ১০টি ছবি নামতে ৫০ সেকেন্ড সময় লাগবে! 
কিন্তু আপনি যদি **`threading`** ব্যবহার করেন, তবে ১০টি ছবিই একসাথে ডাউনলোড হওয়া শুরু হবে এবং কাজ শেষ হবে মাত্র ৫ সেকেন্ডে!

এটি সাধারণত I/O Bound কাজের জন্য (যেমন: ইন্টারনেট থেকে ডেটা আনা, ফাইলে ডেটা লেখা) সবচেয়ে বেস্ট। 

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের ThreadPoolExecutor পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. সাধারণ থ্রেড তৈরি করা (`Thread`)
প্রথমে আমরা দেখবো কীভাবে দুটি কাজকে একসাথে (প্যারালালি) রান করানো যায়।

```python
import threading
import time

def download_file(filename):
    print(f"Downloading {filename}...")
    time.sleep(3) # ধরি, ডাউনলোড হতে ৩ সেকেন্ড লাগে
    print(f"{filename} download finished!")

# সাধারণ পদ্ধতিতে করলে মোট ৬ সেকেন্ড লাগতো। 
# কিন্তু থ্রেড ব্যবহার করায় দুটি কাজ একসাথেই শুরু হবে!

# ১. থ্রেড তৈরি করা (ফাংশনের নাম এবং আর্গুমেন্ট দিতে হয়)
t1 = threading.Thread(target=download_file, args=("movie1.mp4",))
t2 = threading.Thread(target=download_file, args=("movie2.mp4",))

# ২. থ্রেড চালু করা (start)
t1.start()
t2.start()

# ৩. মেইন প্রোগ্রামকে অপেক্ষা করানো (নাহলে থ্রেডের কাজ শেষ হওয়ার আগেই কোড শেষ হয়ে যাবে)
t1.join()
t2.join()

print("All downloads completed!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ২. রেস কন্ডিশন (Race Condition) এবং লক (`Lock`)
অনেকগুলো থ্রেড যখন একসাথে রান হয় এবং সবাই মিলে একই ভ্যারিয়েবলের মান পরিবর্তন করতে যায়, তখন ডেটা লস বা জগাখিচুড়ি হয়ে যায়। একে বলে Race Condition। 

এই সমস্যা সমাধানে আমরা `Lock` ব্যবহার করি। লক করা থাকলে একটি থ্রেড কাজ শেষ না করা পর্যন্ত অন্য থ্রেড ওই ভ্যারিয়েবল ধরতে পারবে না।

```python
import threading
import time

# একটি শেয়ার করা ব্যাংক ব্যালেন্স
bank_balance = 1000

# একটি লক তৈরি করা
lock = threading.Lock()

def withdraw_money(amount):
    global bank_balance
    
    # লক করা (এখন অন্য কোনো থ্রেড এখানে ঢুকতে পারবে না)
    lock.acquire()
    try:
        print(f"Checking balance... (Trying to withdraw {amount})")
        time.sleep(1) # ব্যাংকের সার্ভার স্লো!
        
        if bank_balance >= amount:
            bank_balance -= amount
            print(f"Withdraw Successful! Remaining: {bank_balance}")
        else:
            print("Insufficient Balance!")
    finally:
        # কাজ শেষে লক খুলে দেওয়া (যাতে অন্যরা কাজ করতে পারে)
        lock.release()

# দুটি থ্রেড একসাথে টাকা তোলার চেষ্টা করছে!
t1 = threading.Thread(target=withdraw_money, args=(800,))
t2 = threading.Thread(target=withdraw_money, args=(800,))

t1.start()
t2.start()

t1.join()
t2.join()
```
*(যদি লক না দিতাম, তবে দুটি থ্রেডই ভাবতো ব্যালেন্স ১০০০ আছে এবং তারা ১৬০০ টাকা তুলে মাইনাস ব্যালেন্স করে দিতো!)*

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৩. ThreadPoolExecutor (মডার্ন থ্রেডিং)
পাইথন ৩.২ এর পর থেকে ১০-২০টি থ্রেড ম্যানুয়ালি তৈরি করে `start()` আর `join()` লেখাটা অনেক সেকেলে হয়ে গেছে। 

এখন প্রফেশনাল লেভেলের কাজের জন্য `concurrent.futures` মডিউলের **`ThreadPoolExecutor`** ব্যবহার করা হয়। এটি অটোমেটিক থ্রেড তৈরি করে এবং কাজ শেষে থ্রেডগুলো বন্ধ করে দেয়। বিশাল লিস্ট নিয়ে কাজ করার জন্য এটি বেস্ট!

```python
import time
from concurrent.futures import ThreadPoolExecutor

def process_image(image_id):
    print(f"Applying filter to image {image_id}...")
    time.sleep(2)
    return f"Image {image_id} is ready!"

image_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

start_time = time.time()

# ThreadPoolExecutor তৈরি করা (max_workers=5 মানে সে একসাথে ৫টি থ্রেড রান করবে)
with ThreadPoolExecutor(max_workers=5) as executor:
    
    # map ফাংশনটি পুরো লিস্টের ডেটাগুলোকে থ্রেডের মধ্যে ভাগ করে দিবে!
    results = executor.map(process_image, image_ids)
    
    # রেজাল্টগুলো প্রিন্ট করা
    for result in results:
        print("Result:", result)

print(f"Total time taken: {time.time() - start_time:.2f} seconds")
```
*(সাধারণ নিয়মে ১০টি ইমেজ প্রসেস করতে ২০ সেকেন্ড লাগতো, কিন্তু ৫টি করে থ্রেড ব্যবহার করায় মাত্র ৪ সেকেন্ড লাগবে!)*

### সারসংক্ষেপ (Conclusion)
যদিও গাণিতিক হিসাব বা CPU-Bound কাজের জন্য পাইথনে `multiprocessing` ব্যবহার করা হয়, কিন্তু নেটওয়ার্কিং (API Calls), ওয়েব স্ক্র্যাপিং বা ফাইল ডাউনলোডের মতো I/O-Bound কাজের জন্য **`threading`** (বিশেষ করে **`ThreadPoolExecutor`**) হলো সবচেয়ে পাওয়ারফুল হাতিয়ার! এটি আপনার স্ক্রিপ্টের স্পিড নিমেষেই ২০-৩০ গুণ বাড়িয়ে দিতে পারে।
