# Multi-threading এবং Concurrency মডিউল সমূহের কমপ্লিট গাইড 

পাইথনে একসাথে একাধিক কাজ করার (Multi-threading বা Concurrency) জন্য বেশ কয়েকটি মডিউল রয়েছে। কোন মডিউলটি কখন ব্যবহার করবেন এবং কীভাবে করবেন, তা নিয়ে অনেক সময়ই কনফিউশন তৈরি হয়। 

এই টিউটোরিয়ালে আমরা বেসিক থেকে অ্যাডভান্সড পর্যন্ত সবগুলো মডিউলের কাজ এবং পার্থক্য দেখবো।

---

## 🟢 বেসিক কনসেপ্ট (Concurrency vs Parallelism)
* **Concurrency (Multi-threading):** ধরুন আপনি রান্না করছেন এবং একই সাথে ফোনে কথা বলছেন। অর্থাৎ একটি কাজ একটু করে আবার অন্য কাজটি একটু করছেন। পাইথনে ওয়েব স্ক্র্যাপিং বা ইন্টারনেট থেকে ফাইল ডাউনলোডের মতো কাজের জন্য এটি বেস্ট।
* **Parallelism (Multi-processing):** ধরুন আপনার বাসায় দুইজন বাবুর্চি আছে, একজন ভাত রান্না করছে আর অন্যজন মাংস রান্না করছে। পাইথনে ভারি ক্যালকুলেশন বা ভিডিও এডিটিং এর মতো কাজের জন্য এটি বেস্ট।

পাইথনে মাল্টি-থ্রেডিং বা কনকারেন্সির জন্য প্রধানত ৪টি মডিউল ব্যবহার করা হয়:
১. `threading` (বেসিক)
২. `concurrent.futures` (ইন্টারমিডিয়েট/অ্যাডভান্সড)
৩. `multiprocessing` (অ্যাডভান্সড)
৪. `asyncio` (মডার্ন ও সুপার ফাস্ট)

---

## ১. `threading` মডিউল (The Classic Way)
এটি পাইথনের সবচেয়ে পুরোনো এবং বেসিক মাল্টি-থ্রেডিং মডিউল। যখন আপনাকে ম্যানুয়ালি থ্রেড তৈরি করে সেগুলোকে কন্ট্রোল করতে হয়, তখন এটি কাজে লাগে।

**কীভাবে ব্যবহার করবেন?**
```python
import threading
import time

def download_file(filename):
    print(f"Downloading {filename}...")
    time.sleep(2)
    print(f"Finished {filename}!")

# দুটি আলাদা থ্রেড তৈরি করা
t1 = threading.Thread(target=download_file, args=("image1.jpg",))
t2 = threading.Thread(target=download_file, args=("image2.jpg",))

# কাজ শুরু করা
t1.start()
t2.start()

# থ্রেডের কাজ শেষ না হওয়া পর্যন্ত মূল প্রোগ্রামকে অপেক্ষা করানো
t1.join()
t2.join()
print("All downloads complete!")
```
**সীমাবদ্ধতা:** যখন অনেক বেশি (৫০-১০০টি) লিংক একসাথে রান করতে হয়, তখন লুপ দিয়ে এতো `Thread` তৈরি এবং `join()` করা খুব ঝামেলার।

---

## ২. `concurrent.futures` মডিউল (The Modern Way)
এটি `threading` এর বড় ভাই! বর্তমানে রিয়েল-লাইফ প্রজেক্টে (যেমন ওয়েব স্ক্র্যাপিং) এটিই সবচেয়ে বেশি ব্যবহৃত হয়। এটি মূলত একটি "Thread Pool" তৈরি করে। 

**কীভাবে ব্যবহার করবেন?**
```python
import concurrent.futures
import time

def scrape_data(url):
    time.sleep(1)
    return f"Data from {url}"

urls = ["site1.com", "site2.com", "site3.com", "site4.com", "site5.com"]

# একসাথে ৩টি করে থ্রেড কাজ করবে
with concurrent.futures.ThreadPoolExecutor(max_workers=3) as executor:
    # map ফাংশন নিজে থেকেই লুপ চালিয়ে সবগুলো থ্রেডকে কাজ বুঝিয়ে দিবে
    results = executor.map(scrape_data, urls)
    
    for result in results:
        print(result)
```
**সুবিধা:** কোড অনেক পরিষ্কার থাকে এবং `max_workers` দিয়ে আপনি খুব সহজেই স্পিড কন্ট্রোল করতে পারবেন।

---

## ৩. `multiprocessing` মডিউল (For Heavy CPU Tasks)
পাইথনে **GIL (Global Interpreter Lock)** নামের একটি জিনিস আছে, যার কারণে সাধারণ থ্রেডিং দিয়ে আপনি আপনার পিসির সবগুলো কোর (Core) একসাথে ব্যবহার করতে পারবেন না। 
কিন্তু আপনার কাজ যদি অনেক ভারি হয় (যেমন: বিশাল ডেটাবেস অ্যানালাইসিস বা মেশিন লার্নিং মডেল ট্রেইনিং), তখন `multiprocessing` ব্যবহার করতে হয়।

**কীভাবে ব্যবহার করবেন?**
```python
import multiprocessing
import time

def heavy_calculation(number):
    result = sum(i * i for i in range(number))
    print(f"Result: {result}")

if __name__ == '__main__':
    numbers = [10000000, 20000000, 30000000]
    
    # এটি থ্রেডের বদলে আপনার পিসির আলাদা আলাদা প্রসেসরে কাজ ভাগ করে দিবে
    with multiprocessing.Pool(processes=3) as pool:
        pool.map(heavy_calculation, numbers)
```
**সুবিধা:** পিসির ১০০% প্রসেসিং পাওয়ার ব্যবহার করা যায়। 
**অসুবিধা:** প্রচুর র‍্যাম (RAM) খরচ হয়, তাই ছোট কাজের জন্য এটি মোটেও উচিত নয়।

---

## ৪. `asyncio` মডিউল (The Super Fast Single Thread)
সবচেয়ে মডার্ন এবং ফাস্ট পদ্ধতি। এটি থ্রেড না বানিয়ে, একই থ্রেডের ভেতরে ইভেন্ট লুপ (Event Loop) ব্যবহার করে কাজ করে। বর্তমানে FastAPI বা মডার্ন বটের ব্যাকএন্ডে এটি ব্যবহৃত হয়।

**কীভাবে ব্যবহার করবেন?**
```python
import asyncio

async def fetch_data(id):
    print(f"Start fetching API {id}...")
    await asyncio.sleep(2) # এখানে অপেক্ষা না করে সে অন্য কাজে চলে যাবে
    print(f"Finished {id}!")

async def main():
    # ৩টি কাজ একসাথে ইভেন্ট লুপে পাঠানো
    await asyncio.gather(
        fetch_data(1),
        fetch_data(2),
        fetch_data(3)
    )

asyncio.run(main())
```

---

## 🏆 সারসংক্ষেপ (কখন কোনটি ব্যবহার করবেন?)

| মডিউলের নাম | কাজের ধরন (Use Case) | স্পিড | কোডের জটিলতা |
| :--- | :--- | :--- | :--- |
| **`threading`** | ২-৪ টি আলাদা টাস্ক একসাথে রান করাতে চাইলে। | মিডিয়াম | একটু জটিল |
| **`concurrent.futures`** | ওয়েব স্ক্র্যাপিং, API কল করা বা অনেক ফাইল একসাথে ডাউনলোড করতে। | হাই | খুব সহজ |
| **`multiprocessing`** | ডেটা অ্যানালাইসিস, ইমেজ প্রসেসিং, ম্যাথ ক্যালকুলেশন (CPU Heavy)। | সর্বোচ্চ (CPU) | সহজ |
| **`asyncio`** | লাখ লাখ নেটওয়ার্ক রিকোয়েস্ট, মডার্ন ওয়েব ফ্রেমওয়ার্ক বা চ্যাট বট বানাতে। | সর্বোচ্চ (I/O) | সবচেয়ে জটিল |

**বিগিনারদের জন্য পরামর্শ:** আপনি যেহেতু ডেটা এবং ওয়েব স্ক্র্যাপিং নিয়ে কাজ করছেন, আপনার জন্য **`concurrent.futures` (ThreadPoolExecutor)** হবে সবচেয়ে বেস্ট চয়েস!
