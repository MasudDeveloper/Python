# asyncio (Zero to Hero) কমপ্লিট গাইড

পাইথনে সাধারণত কোড উপর থেকে নিচে লাইন বাই লাইন রান হয়। অর্থাৎ, এক লাইনের কাজ শেষ না হওয়া পর্যন্ত পরের লাইন শুরু হতে পারে না। একে বলা হয় **Synchronous (সিনক্রোনাস)** প্রোগ্রামিং।

কিন্তু এমন অনেক কাজ আছে (যেমন: ইন্টারনেট থেকে ডেটা ডাউনলোড করা, ডেটাবেস থেকে রেসপন্স আসা) যেগুলোতে অনেক সময় লাগে। পাইথন যদি এসব কাজের জন্য বসে থাকে, তবে আপনার প্রোগ্রামটি অনেক স্লো হয়ে যাবে। 

এই সমস্যার সমাধান হলো **Asynchronous (অ্যাসিনক্রোনাস)** প্রোগ্রামিং, আর পাইথনে এই কাজটি করার জন্যই তৈরি করা হয়েছে **`asyncio`** মডিউল। এটি কোনো থ্রেড (Thread) তৈরি না করেই নন-ব্লকিং কোড লিখতে সাহায্য করে।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. Synchronous বনাম Asynchronous
প্রথমে চলুন দেখি নরমাল পাইথন কোড কীভাবে কাজ করে:

**সাধারণ কোড (Synchronous):**
```python
import time

def make_tea():
    print("চা বানানো শুরু...")
    time.sleep(3)  # ৩ সেকেন্ড সময় লাগলো
    print("চা রেডি!")

def make_toast():
    print("টোস্ট বানানো শুরু...")
    time.sleep(2)  # ২ সেকেন্ড সময় লাগলো
    print("টোস্ট রেডি!")

start = time.time()
make_tea()
make_toast()
print(f"মোট সময় লেগেছে: {time.time() - start:.2f} সেকেন্ড")
# আউটপুট: মোট সময় ৫ সেকেন্ড (কারণ একটার পর একটা হয়েছে)
```

**অ্যাসিনক্রোনাস কোড (Asynchronous):**
এখন আমরা `asyncio` ব্যবহার করে দুটি কাজ একসাথে শুরু করবো!

```python
import asyncio
import time

# ১. সাধারণ def এর আগে 'async' লিখতে হয়
async def make_tea():
    print("চা বানানো শুরু...")
    # ২. time.sleep এর বদলে asyncio.sleep ব্যবহার করতে হয় এবং আগে 'await' দিতে হয়
    await asyncio.sleep(3) 
    print("চা রেডি!")

async def make_toast():
    print("টোস্ট বানানো শুরু...")
    await asyncio.sleep(2)
    print("টোস্ট রেডি!")

# ৩. মেইন ফাংশনটিও async হবে
async def main():
    start = time.time()
    
    # ৪. দুটি কাজ একবারে (Concurrently) চালানোর জন্য gather ব্যবহার করা হয়
    await asyncio.gather(make_tea(), make_toast())
    
    print(f"মোট সময় লেগেছে: {time.time() - start:.2f} সেকেন্ড")
    # আউটপুট: মোট সময় ৩ সেকেন্ড! (কারণ দুটি কাজ একসাথে হয়েছে)

# ৫. async ফাংশনকে রান করার নিয়ম
asyncio.run(main())
```

### ২. Coroutine এবং `await` কী?
* **Coroutine:** যখন কোনো ফাংশনের আগে `async def` লেখা হয়, তখন সেটি আর সাধারণ ফাংশন থাকে না, সেটি একটি Coroutine (কোরুটিন) হয়ে যায়।
* **`await`:** এটি পাইথনকে বলে, "এই কাজটিতে একটু সময় লাগবে। তুমি এখানে বসে না থেকে অন্য কোনো কাজ করে আসো।"

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. Tasks তৈরি করা (`asyncio.create_task`)
কখনো কখনো আমরা চাই না `gather` দিয়ে সবকিছু একসাথে রান হোক। আমরা চাই কাজ শুরু হয়ে যাক, আমরা পরে কোনো একসময় তার রেজাল্ট নিবো। এজন্য `create_task` ব্যবহার করা হয়।

```python
import asyncio

async def download_file(filename, delay):
    print(f"Downloading {filename}...")
    await asyncio.sleep(delay)
    print(f"Finished {filename}!")
    return f"{filename} Data"

async def main():
    # টাস্ক তৈরি করা (এগুলো ব্যাকগ্রাউন্ডে সাথে সাথে রান হওয়া শুরু করবে)
    task1 = asyncio.create_task(download_file("Movie.mp4", 4))
    task2 = asyncio.create_task(download_file("Song.mp3", 2))
    
    print("অন্যান্য কাজ করছি...")
    await asyncio.sleep(1) # অন্য কাজ
    print("এখনো ফাইল ডাউনলোড হচ্ছে...")

    # টাস্কগুলোর রেজাল্ট পাওয়ার জন্য অপেক্ষা করা
    res1 = await task1
    res2 = await task2
    
    print("রেজাল্ট:", res1, res2)

asyncio.run(main())
```

### ৪. রিয়েল লাইফ উদাহরণ (`aiohttp` দিয়ে ওয়েব স্ক্র্যাপিং)
সাধারণ `requests` লাইব্রেরি অ্যাসিনক্রোনাস নয়, এটি কোড ব্লক করে দেয়। তাই `asyncio` এর সাথে ওয়েবসাইট থেকে ডেটা আনতে `aiohttp` লাইব্রেরি ব্যবহৃত হয়।
*(রান করার আগে `pip install aiohttp` দিয়ে ইন্সটল করে নিন)*

```python
import asyncio
import aiohttp
import time

async def fetch_data(url):
    async with aiohttp.ClientSession() as session:
        # নন-ব্লকিং HTTP রিকোয়েস্ট
        async with session.get(url) as response:
            return await response.text()

async def main():
    urls = [
        "https://httpbin.org/delay/2",
        "https://httpbin.org/delay/2",
        "https://httpbin.org/delay/2"
    ]
    
    start = time.time()
    
    # সবগুলো লিংকে একসাথে রিকোয়েস্ট পাঠানো
    tasks = [fetch_data(url) for url in urls]
    results = await asyncio.gather(*tasks)
    
    # ৩টি রিকোয়েস্টে ২ সেকেন্ড করে লাগলেও মোট সময় মাত্র ২ সেকেন্ডই লাগবে!
    print(f"ডাউনলোড শেষ! সময় লেগেছে: {time.time() - start:.2f} সেকেন্ড")

# asyncio.run(main())
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. ব্লকিং কোডকে নন-ব্লকিং করা (`asyncio.to_thread`)
অনেক সময় আমাদের এমন কিছু কাজ করতে হয় যেগুলো `await` সাপোর্ট করে না (যেমন: `requests` মডিউল বা বিশাল গাণিতিক হিসাব)। এগুলো মেইন ইভেন্ট লুপকে ব্লক করে দেয়। তখন আমরা সেগুলোকে আলাদা থ্রেডে পাঠিয়ে দিতে পারি!

```python
import asyncio
import time

# এটি একটি সাধারণ ব্লকিং ফাংশন (যেখানে await নেই)
def heavy_computation():
    print("ভারী কাজ শুরু...")
    time.sleep(3) # এটি পুরো প্রোগ্রাম হ্যাং করে দিবে!
    print("ভারী কাজ শেষ!")
    return 42

async def main():
    print("মেইন প্রোগ্রাম শুরু...")
    
    # ব্লকিং কাজটিকে ব্যাকগ্রাউন্ড থ্রেডে পাঠিয়ে দেওয়া হলো!
    task = asyncio.to_thread(heavy_computation)
    
    print("মেইন প্রোগ্রাম বসে নেই, সে তার কাজ করছে...")
    await asyncio.sleep(1)
    
    # এবার ভারী কাজের রেজাল্ট নেওয়া
    result = await task
    print("ফাইনাল রেজাল্ট:", result)

asyncio.run(main())
```

### ৬. সেমাফোর (Semaphore) - লিমিট সেট করা
ধরুন আপনি একসাথে ১০০টি ওয়েবসাইট স্ক্র্যাপ করতে চান। আপনি যদি `gather` দিয়ে ১০০টি রিকোয়েস্ট একসাথে পাঠান, তবে সার্ভার ক্র্যাশ করতে পারে বা আপনাকে ব্লক করে দিতে পারে। 

এজন্য `Semaphore` ব্যবহার করা হয়, যা নির্ধারণ করে দেয় একবারে সর্বোচ্চ কয়টি টাস্ক চলবে।

```python
import asyncio

# একবারে সর্বোচ্চ ৩টি কাজ একসাথে হতে পারবে
sem = asyncio.Semaphore(3)

async def download_worker(id):
    # সেমাফোর দিয়ে লক করা
    async with sem:
        print(f"Worker {id} ডাউনলোড শুরু করলো...")
        await asyncio.sleep(2)
        print(f"Worker {id} কাজ শেষ করেছে!")

async def main():
    # একসাথে ১০টি টাস্ক তৈরি করা
    tasks = [download_worker(i) for i in range(1, 11)]
    
    # ১০টি টাস্ক থাকলেও একবারে ৩টির বেশি রান হবে না!
    await asyncio.gather(*tasks)

asyncio.run(main())
```

### ৭. Asyncio Queue (প্রোডিউসার-কনজ্যুমার মডেল)
বড় স্কেলের প্রোজেক্টে একদিক থেকে ডেটা আসে (Producer) এবং আরেকদিক থেকে সেগুলো প্রসেস করা হয় (Consumer)। এর জন্য `asyncio.Queue` বেস্ট।

```python
import asyncio
import random

async def producer(queue):
    for i in range(5):
        # রেন্ডম ডেটা তৈরি করে কিউতে (Queue) রাখা
        item = f"Item-{i}"
        await queue.put(item)
        print(f"প্রোডিউসার তৈরি করলো: {item}")
        await asyncio.sleep(random.uniform(0.1, 0.5))

async def consumer(queue):
    while True:
        # কিউ থেকে ডেটা নেওয়া
        item = await queue.get()
        print(f"--- কনজ্যুমার প্রসেস করলো: {item}")
        
        # কিউকে জানানো যে কাজ শেষ
        queue.task_done()

async def main():
    queue = asyncio.Queue()
    
    # প্রোডিউসার টাস্ক রান করা (যে ডেটা বানাবে)
    prod_task = asyncio.create_task(producer(queue))
    
    # কনজ্যুমার টাস্ক রান করা (যে ডেটা প্রসেস করবে)
    cons_task = asyncio.create_task(consumer(queue))
    
    # প্রোডিউসারের কাজ শেষ হওয়া পর্যন্ত ওয়েট করা
    await prod_task
    
    # কিউ খালি হওয়া পর্যন্ত ওয়েট করা
    await queue.join()
    
    # কনজ্যুমারকে বন্ধ করে দেওয়া (কারণ সে while True তে আটকা ছিল)
    cons_task.cancel()

asyncio.run(main())
```

### সারসংক্ষেপ (Conclusion)
FastAPI, Discord/Telegram Bot, ওয়েব স্ক্র্যাপিং বা মাইক্রোসার্ভিস—যেখানেই পারফরম্যান্স এবং স্পিডের প্রশ্ন আসে, সেখানেই **`asyncio`** এর রাজত্ব! 

এটি ব্যবহার করার গোল্ডেন রুল হলো: **"I/O বাউন্ড কাজ (নেটওয়ার্ক, ডেটাবেস, ফাইল রিড) হলে `await` ব্যবহার করো, আর CPU বাউন্ড কাজ (হিসাব-নিকাশ) হলে `to_thread` বা Multiprocessing ব্যবহার করো।"**
