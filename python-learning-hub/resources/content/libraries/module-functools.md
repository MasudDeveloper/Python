# Functools (Zero to Hero) কমপ্লিট গাইড

পাইথনে ফাংশন হলো ফার্স্ট-ক্লাস সিটিজেন, অর্থাৎ আপনি চাইলে ফাংশনকে ভ্যারিয়েবলের ভেতর রাখতে পারেন বা অন্য ফাংশনের আর্গুমেন্ট হিসেবে পাস করতে পারেন। 

আর এই ফাংশনগুলোকে আরও পাওয়ারফুল এবং অপ্টিমাইজড (Optimized) করার জন্য পাইথনের বিল্ট-ইন লাইব্রেরি হলো **`functools`** (Higher-order functions and operations on callable objects)।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Function Overloading (`singledispatch`) এবং Caching পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. `partial` (আর্গুমেন্ট আগে থেকেই ফিক্স করে রাখা)
ধরুন আপনার কাছে একটি ফাংশন আছে যা ৩টি প্যারামিটার নেয়। কিন্তু আপনি চাচ্ছেন এমন একটি নতুন ফাংশন বানাতে যার প্রথম ২টি প্যারামিটার আগে থেকেই ফিক্সড থাকবে, আপনাকে বারবার লিখতে হবে না।

```python
from functools import partial

# সাধারণ একটি ফাংশন
def multiply(x, y, z):
    return x * y * z

print(multiply(2, 3, 4)) # 24

# partial দিয়ে আমরা x=2 এবং y=3 ফিক্সড করে দিলাম
double_and_triple = partial(multiply, 2, 3)

# এখন নতুন ফাংশনে শুধু z এর ভ্যালু (4) দিলেই হবে!
print(double_and_triple(4)) # 24
```
*এটি ডেটা সায়েন্স বা মেশিন লার্নিংয়ের অনেক বড় বড় ফাংশনকে ছোট করার জন্য খুব কাজে লাগে।*

### ২. `reduce` (লিস্টকে ছোট করে একটি রেজাল্টে আনা)
লিস্টের সবগুলো ডেটাকে গুণ করে একটি রেজাল্ট বের করার জন্য আমরা সাধারণত `for loop` চালাই। কিন্তু `reduce` এই কাজটি মাত্র এক লাইনে করে দেয়!

```python
from functools import reduce

numbers = [1, 2, 3, 4, 5]

# reduce একটি ফাংশন এবং একটি লিস্ট নেয়। 
# এটি লিস্টের প্রথম দুটি উপাদান গুণ করবে, তারপর সেই রেজাল্টের সাথে পরেরটি গুণ করবে...
product = reduce(lambda x, y: x * y, numbers)

print("Product of all numbers:", product) # 1 * 2 * 3 * 4 * 5 = 120
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. `lru_cache` (ম্যাজিকের মতো স্পিড বাড়ানো!)
ধরুন আপনি একটি API থেকে ডেটা ফেচ করছেন, অথবা এমন কোনো গাণিতিক ফাংশন রান করছেন যাতে অনেক সময় লাগে (যেমন: ফিবোনাচি সিরিজ)। 

একই ইনপুট দিয়ে যদি ফাংশনটি বারবার কল করা হয়, তবে পাইথন বারবার কষ্ট করে হিসাব করবে। কিন্তু **`@lru_cache`** (Least Recently Used Cache) ডেকোরেটর ব্যবহার করলে সে রেজাল্টটি মেমোরিতে সেভ করে রাখবে এবং পরেরবার হিসাব না করেই সাথে সাথে রেজাল্ট দিয়ে দিবে!

```python
from functools import lru_cache
import time

# maxsize=None দিলে আনলিমিটেড ডেটা সেভ রাখবে
@lru_cache(maxsize=None)
def heavy_computation(n):
    print(f"Calculating for {n}...")
    time.sleep(2) # ধরি হিসাব করতে ২ সেকেন্ড লাগে
    return n * n

start = time.time()
print(heavy_computation(10)) # ২ সেকেন্ড সময় নিবে
print(f"Time 1: {time.time() - start:.2f}s\n")

start = time.time()
# এবার আর ২ সেকেন্ড লাগবে না, কারণ এটি Cache থেকে ডেটা দিবে! (০ সেকেন্ড লাগবে)
print(heavy_computation(10)) 
print(f"Time 2: {time.time() - start:.5f}s\n")
```

### ৪. `wraps` (ডেকোরেটরের আসল পরিচয় ধরে রাখা)
যখন আমরা পাইথনে নিজেরা ডেকোরেটর (Decorator) বানাই, তখন অরিজিনাল ফাংশনটি তার নাম (name) এবং ডকুমেন্টেশন (docstring) হারিয়ে ফেলে। এই সমস্যা সমাধানের জন্যই `@wraps` ব্যবহৃত হয়।

```python
from functools import wraps

# একটি কাস্টম ডেকোরেটর
def my_decorator(func):
    @wraps(func) # এটি না দিলে আসল ফাংশনের নাম হারিয়ে যাবে!
    def wrapper(*args, **kwargs):
        print("Function is starting...")
        return func(*args, **kwargs)
    return wrapper

@my_decorator
def say_hello():
    """This function says hello"""
    print("Hello!")

say_hello()

# @wraps ব্যবহার করায় ফাংশনের আসল নাম এবং ডকস্ট্রিং ঠিক আছে
print("\nFunction Name:", say_hello.__name__) # say_hello
print("Docstring:", say_hello.__doc__)        # This function says hello
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. `singledispatch` (Function Overloading)
জাভা (Java) বা সি++ (C++) এ Function Overloading আছে (অর্থাৎ একই নামের ফাংশন ইনপুটের টাইপ অনুযায়ী আলাদা আচরণ করে)। পাইথনে বাই-ডিফল্ট এটি নেই। কিন্তু `singledispatch` দিয়ে আমরা পাইথনেও এটি করতে পারি!

```python
from functools import singledispatch

# মেইন বা ডিফল্ট ফাংশন (যখন টাইপ ম্যাচ করবে না তখন এটি রান হবে)
@singledispatch
def process_data(data):
    print("Default processing:", data)

# যদি ডেটা int (Number) হয়, তবে এই ফাংশন রান হবে
@process_data.register(int)
def _(data):
    print("Processing an Integer:", data * 2)

# যদি ডেটা list হয়, তবে এই ফাংশন রান হবে
@process_data.register(list)
def _(data):
    print("Processing a List, length is:", len(data))

# একই নামের ফাংশন, কিন্তু কাজ করছে আলাদা!
process_data("Hello")  # Default processing: Hello
process_data(10)       # Processing an Integer: 20
process_data([1,2,3])  # Processing a List, length is: 3
```

### ৬. `cached_property` (OOP অপ্টিমাইজেশন)
ক্লাসের (Class) ভেতরে যদি এমন কোনো প্রপার্টি থাকে যা বারবার কল হয় কিন্তু তার ভ্যালু চেঞ্জ হয় না (যেমন: বিশাল কোনো ডেটাবেস কোয়েরি), তবে তাকে ক্যাশে (Cache) করার জন্য এটি ব্যবহৃত হয়।

```python
from functools import cached_property
import time

class Dataset:
    def __init__(self):
        self.name = "My Dataset"
        
    @cached_property
    def calculate_heavy_stats(self):
        print("Calculating heavy stats...")
        time.sleep(2) # ২ সেকেন্ড সময় লাগছে
        return 99.99

data = Dataset()

# প্রথমবার ২ সেকেন্ড সময় নিবে
print(data.calculate_heavy_stats)

# দ্বিতীয়বার সাথে সাথে রেজাল্ট দিয়ে দিবে, কারণ এটি Cache হয়ে প্রপার্টি হয়ে গেছে!
print(data.calculate_heavy_stats) 
```

### ৭. `total_ordering` (সহজে ক্লাস কম্পেয়ার করা)
আপনি যদি একটি ক্লাসের দুটি অবজেক্টের মধ্যে `>`, `<`, `>=`, `<=` ইত্যাদি চেক করতে চান, তবে আপনাকে সবগুলো ম্যাজিক মেথড লিখতে হবে, যা অনেক ঝামেলার। `total_ordering` আপনাকে শুধু `__eq__` (সমান) এবং অন্য যেকোনো একটি (যেমন: `__lt__` মানে ছোট) লিখতে বলে, বাকিগুলো সে নিজে নিজেই বানিয়ে নিবে!

```python
from functools import total_ordering

@total_ordering
class Student:
    def __init__(self, name, marks):
        self.name = name
        self.marks = marks
        
    # সমান কি না চেক করা (==)
    def __eq__(self, other):
        return self.marks == other.marks
        
    # ছোট কি না চেক করা (<)
    def __lt__(self, other):
        return self.marks < other.marks

s1 = Student("Rahim", 80)
s2 = Student("Karim", 90)

# আমরা > বা >= এর কোড লিখিনি, কিন্তু total_ordering এর কারণে এগুলো নিজে থেকেই কাজ করবে!
print("Is s2 greater than s1?", s2 > s1)   # True
print("Is s1 less or equal s2?", s1 <= s2) # True
```

### সারসংক্ষেপ (Conclusion)
আপনি যখন প্রফেশনাল লেভেলের কোনো লাইব্রেরি বা ফ্রেমওয়ার্ক (যেমন: Django, FastAPI) বানাবেন, তখন আপনার কোডকে ফাস্ট এবং ডাইনামিক করার জন্য **`functools`** মডিউলটি আপনার সবচেয়ে ভালো বন্ধু হবে। বিশেষ করে `@lru_cache` বা `@wraps` এর ব্যবহার জানলে আপনার কোডের পারফরম্যান্স অন্যদের চেয়ে বহুগুণ বেড়ে যাবে!
