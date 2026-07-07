# Multiprocessing (Zero to Hero) কমপ্লিট গাইড

পাইথনের একটি কুখ্যাত বদনাম আছে—**GIL (Global Interpreter Lock)**। এর কারণে, আপনি যদি সাধারণ `threading` ব্যবহার করেন, তবুও পাইথন একসাথে একাধিক কোর (CPU Core) ব্যবহার করতে পারে না! 

যদি আপনার কাছে এমন কোনো কাজ থাকে যেখানে প্রচুর গাণিতিক হিসাব বা প্রসেসরের জোর লাগে (CPU-Bound Task, যেমন: ভিডিও রেন্ডারিং, ইমেজ প্রসেসিং, মেশিন লার্নিং), তবে `threading` বা `asyncio` কোনো কাজেই আসবে না। 

এই সমস্যার একমাত্র সমাধান হলো **`multiprocessing`** মডিউল। এটি আপনার কম্পিউটারের সবগুলো কোরকে (Core) একসাথে কাজে লাগিয়ে প্রোগ্রামকে আলোর গতিতে রান করতে পারে!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Shared Memory, Queues এবং Process Synchronization পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. সাধারণ প্রসেস তৈরি করা (`Process`)
প্রথমে চলুন দেখি কীভাবে একটি সাধারণ কাজকে আলাদা একটি প্রসেসরে পাঠানো যায়।

*(বিঃদ্রঃ উইন্ডোজে মাল্টিপ্রসেসিং রান করার সময় সব কোড অবশ্যই `if __name__ == '__main__':` ব্লকের ভেতরে লিখতে হয়, নাহলে প্রোগ্রাম ক্র্যাশ করবে বা অনন্তকাল লুপে আটকে যাবে!)*

```python
import multiprocessing
import time

def heavy_task(name):
    print(f"Task {name} started...")
    time.sleep(2) # ধরি এটি একটি ভারী কাজ
    print(f"Task {name} finished!")

if __name__ == '__main__':
    # আপনার কম্পিউটারে কয়টি কোর (Processor Core) আছে?
    print(f"My CPU Cores: {multiprocessing.cpu_count()}")
    
    start_time = time.time()
    
    # দুটি আলাদা প্রসেস তৈরি করা হলো
    p1 = multiprocessing.Process(target=heavy_task, args=("A",))
    p2 = multiprocessing.Process(target=heavy_task, args=("B",))
    
    # প্রসেসগুলো চালু করা (এগুলো আলাদা আলাদা কোর-এ রান হবে)
    p1.start()
    p2.start()
    
    # join() মানে হলো প্রসেসগুলোর কাজ শেষ না হওয়া পর্যন্ত মেইন প্রোগ্রাম ওয়েট করবে
    p1.join()
    p2.join()
    
    # ২ সেকেন্ডের দুটি কাজ একসাথে রান হওয়ায় মোট সময় মাত্র ২ সেকেন্ডই লাগবে!
    print(f"Total time taken: {time.time() - start_time:.2f} seconds")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ২. `Pool` (সবচেয়ে সহজ প্যারালাল প্রসেসিং)
সাধারণত আমাদের কাছে একটি বিশাল লিস্ট থাকে এবং আমরা চাই লিস্টের ডেটাগুলো সবগুলো কোরের মধ্যে সমানভাগে ভাগ হয়ে প্রসেস হোক। এই কাজের জন্য `Pool` হলো সবচেয়ে বেস্ট অপশন!

```python
import multiprocessing
import time

def square_number(n):
    # ধরি এখানে অনেক ভারী কোনো হিসাব হচ্ছে
    return n * n

if __name__ == '__main__':
    numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    
    # Pool তৈরি করা (এটি অটোমেটিক আপনার সব কোর ব্যবহার করবে)
    with multiprocessing.Pool() as pool:
        # map ফাংশন লিস্টের প্রতিটি ডেটাকে প্রসেসরে পাঠিয়ে রেজাল্ট আনবে
        results = pool.map(square_number, numbers)
        
    print("Results:", results)
```

### ৩. শেয়ার্ড মেমোরি (`Value` এবং `Array`)
প্রতিটি প্রসেস যেহেতু আলাদা কোর-এ রান হয়, তাই তাদের মেমোরিও আলাদা হয়। একটি প্রসেসে কোনো ভ্যারিয়েবল চেঞ্জ করলে অন্য প্রসেস তা দেখতে পায় না। প্রসেসগুলোর মধ্যে মেমোরি শেয়ার করার জন্য `Value` (সিঙ্গেল ভ্যালুর জন্য) এবং `Array` (লিস্টের জন্য) ব্যবহার করা হয়।

```python
import multiprocessing

def update_shared_memory(shared_num, shared_arr):
    # শেয়ার করা ভ্যালু পরিবর্তন করা (value অ্যাট্রিবিউট ব্যবহার করতে হয়)
    shared_num.value = 3.14
    
    # শেয়ার করা অ্যারে পরিবর্তন করা
    for i in range(len(shared_arr)):
        shared_arr[i] = shared_arr[i] * 2

if __name__ == '__main__':
    # 'd' মানে ফ্লোট বা Double (C ল্যাঙ্গুয়েজের টাইপ)
    shared_num = multiprocessing.Value('d', 0.0) 
    
    # 'i' মানে Integer 
    shared_arr = multiprocessing.Array('i', [1, 2, 3]) 
    
    p = multiprocessing.Process(target=update_shared_memory, args=(shared_num, shared_arr))
    p.start()
    p.join()
    
    print("Updated Value:", shared_num.value) # 3.14
    print("Updated Array:", shared_arr[:])    # [2, 4, 6]
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৪. প্রসেসগুলোর মধ্যে কথা বলা (`Queue`)
একটি প্রসেস ডেটা তৈরি করবে (Producer) এবং আরেকটি প্রসেস সেই ডেটা নিয়ে কাজ করবে (Consumer)। এই ধরনের প্রোডাকশন-লেভেলের কাজে ডেটা আদান-প্রদানের জন্য `multiprocessing.Queue` ব্যবহার করা হয়। এটি থ্রেড এবং প্রসেস সেফ (Safe)।

```python
import multiprocessing
import time

# এটি প্রডিউসার (যে ডেটা বানাবে)
def producer(queue):
    for i in range(1, 4):
        print(f"Producer put: {i}")
        queue.put(i)
        time.sleep(1)

# এটি কনজ্যুমার (যে ডেটা রিসিভ করবে)
def consumer(queue):
    while True:
        item = queue.get()
        if item is None: # None পেলে প্রসেস বন্ধ হয়ে যাবে
            break
        print(f"--- Consumer got: {item}")

if __name__ == '__main__':
    # একটি শেয়ার্ড Queue তৈরি করা
    queue = multiprocessing.Queue()
    
    p1 = multiprocessing.Process(target=producer, args=(queue,))
    p2 = multiprocessing.Process(target=consumer, args=(queue,))
    
    p1.start()
    p2.start()
    
    p1.join() # প্রডিউসারের কাজ শেষ হওয়া পর্যন্ত ওয়েট করা
    queue.put(None) # কনজ্যুমারকে সিগন্যাল দেওয়া যে আর ডেটা নেই
    p2.join() # কনজ্যুমার প্রসেস বন্ধ হওয়ার ওয়েট করা
```

### ৫. প্রসেস লক (`Lock` দিয়ে রেস কন্ডিশন ঠেকানো)
ধরি, আমাদের দুটি প্রসেস একসাথে একটি ফাইলে ডেটা সেভ করছে বা স্ক্রিনে প্রিন্ট করছে। যেহেতু তারা প্যারালাল চলছে, তাই একটার ডেটার ওপরে আরেকটা ডেটা ওভাররাইট হয়ে জগাখিচুড়ি (Race Condition) পাকিয়ে যেতে পারে! 

এই সমস্যা সমাধানে `Lock` ব্যবহার করা হয়। এটি নিশ্চিত করে যে একটি প্রসেস কাজ শেষ না করা পর্যন্ত অন্য প্রসেস অপেক্ষা করবে।

```python
import multiprocessing
import time

def print_numbers(lock, process_name):
    # লক করা (অন্য কেউ এখন স্ক্রিনে প্রিন্ট করতে পারবে না)
    lock.acquire() 
    try:
        print(f"{process_name} is printing:")
        for i in range(3):
            print(i, end=" ")
            time.sleep(0.1)
        print("\n")
    finally:
        # কাজ শেষে আনলক করা (যাতে অন্যরা কাজ করতে পারে)
        lock.release()

if __name__ == '__main__':
    lock = multiprocessing.Lock()
    
    p1 = multiprocessing.Process(target=print_numbers, args=(lock, "Process 1"))
    p2 = multiprocessing.Process(target=print_numbers, args=(lock, "Process 2"))
    
    # যদিও তারা একসাথে স্টার্ট হয়েছে, কিন্তু লকের কারণে একটার পর একটা প্রিন্ট হবে!
    p1.start()
    p2.start()
    
    p1.join()
    p2.join()
```

### ৬. `Pipe` (দুটি প্রসেসের মধ্যে প্রাইভেট চ্যাট!)
`Queue` তে অনেকগুলো প্রসেস ডেটা আদান-প্রদান করতে পারে, কিন্তু `Pipe` শুধুমাত্র দুটি প্রসেসের মধ্যে ডেটা আদান-প্রদানের জন্য ব্যবহৃত হয়। এটি `Queue` এর চেয়ে অনেক বেশি ফাস্ট!

```python
import multiprocessing

def sender(conn):
    conn.send("Hello from sender!")
    conn.close()

if __name__ == '__main__':
    # Pipe দুটি কানেকশন অবজেক্ট রিটার্ন করে (দুটি মাথার মতো)
    parent_conn, child_conn = multiprocessing.Pipe()
    
    # চাইল্ড কানেকশনটি অন্য প্রসেসকে দিয়ে দেওয়া হলো
    p = multiprocessing.Process(target=sender, args=(child_conn,))
    p.start()
    
    # প্যারেন্ট কানেকশন দিয়ে মেসেজ রিসিভ করা হলো
    print("Received:", parent_conn.recv()) 
    
    p.join()
```

### সারসংক্ষেপ (Conclusion)
ডেটা সায়েন্স, মেশিন লার্নিং, বা যেকোনো ভারী কম্পিউটেশনাল কাজের জন্য পাইথনের **GIL** এর সীমাবদ্ধতা কাটানোর একমাত্র অস্ত্র হলো **`multiprocessing`**। বিশেষ করে এর **`Pool`** ক্লাসটি এতোটাই পাওয়ারফুল আর সহজ যে, মাত্র দুই লাইন কোড চেঞ্জ করেই আপনি আপনার প্রোগ্রামের স্পিড আপনার প্রসেসরের কোর অনুযায়ী ৮ গুণ বা ১৬ গুণ বাড়িয়ে ফেলতে পারেন!
