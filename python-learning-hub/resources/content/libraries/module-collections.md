# Collections (Zero to Hero) কমপ্লিট গাইড

পাইথনে সাধারণত আমরা ডেটা রাখার জন্য `list`, `dict` (ডিকশনারি) বা `tuple` ব্যবহার করি। প্রতিদিনের সাধারণ কাজের জন্য এগুলো ঠিক আছে। 

কিন্তু যখন আপনি অনেক বড় ডেটা নিয়ে কাজ করবেন, অথবা অনেক স্পেসিফিক কোনো লজিক ইমপ্লিমেন্ট করবেন—তখন সাধারণ ডেটাস্ট্রাকচারগুলো অনেক স্লো কাজ করে এবং কোড অনেক বড় হয়ে যায়। এই সমস্যা সমাধানের জন্যই পাইথনের বিল্ট-ইন **`collections`** মডিউলে কিছু **অ্যাডভান্সড ডেটাস্ট্রাকচার (Advanced Data Structures)** দেওয়া আছে!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের `ChainMap` এবং `UserDict` পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. `Counter` (জাদুকরী কাউন্টার)
সাধারণত কোনো লিস্ট বা স্ট্রিংয়ে কোন আইটেম কতবার আছে, তা গোনার জন্য আমরা একটি ফাঁকা ডিকশনারি নিই, লুপ চালাই এবং ভ্যালু বাড়াই। কিন্তু `Counter` এই কাজটি মাত্র ১ লাইনে করে দেয়!

```python
from collections import Counter

# ১. লিস্ট থেকে কাউন্ট করা
votes = ['Alice', 'Bob', 'Alice', 'Charlie', 'Bob', 'Alice']
vote_count = Counter(votes)

print("ভোটের হিসাব:", vote_count) 
# আউটপুট: Counter({'Alice': 3, 'Bob': 2, 'Charlie': 1})

# ২. সবচেয়ে বেশি ভোট কে পেয়েছে? (Most Common)
winner = vote_count.most_common(1) # ১ মানে টপ ১ জন
print("বিজয়ী:", winner) # [('Alice', 3)]

# ৩. স্ট্রিং থেকে লেটার কাউন্ট করা
word = "mississippi"
letter_count = Counter(word)
print("অক্ষরের হিসাব:", letter_count)
# আউটপুট: Counter({'i': 4, 's': 4, 'p': 2, 'm': 1})
```

### ২. `namedtuple` (নামযুক্ত টাপল)
সাধারণ `tuple` এ আমরা ইনডেক্স (`[0], [1]`) দিয়ে ডেটা বের করি, যা বোঝা বেশ কঠিন যে কোনটি কীসের ডেটা। `namedtuple` এর মাধ্যমে আমরা টাপলকে ক্লাসের অবজেক্টের মতো নাম দিয়ে এক্সেস করতে পারি!

```python
from collections import namedtuple

# ১. সাধারণ টাপল
student_normal = ("Rahim", 20, "CS")
print(student_normal[0]) # Rahim (কীসের 0 ইনডেক্স বোঝা কঠিন)

# ২. namedtuple তৈরি করা (নাম: Student, প্রপার্টি: name, age, major)
Student = namedtuple('Student', ['name', 'age', 'major'])

student_1 = Student(name="Rahim", age=20, major="CS")
student_2 = Student(name="Karim", age=22, major="BBA")

# ৩. অবজেক্টের মতো নাম দিয়ে এক্সেস করা (খুবই রিডেবল কোড!)
print(student_1.name)  # Rahim
print(student_2.major) # BBA
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. `defaultdict` (এরর-ফ্রি ডিকশনারি)
সাধারণ ডিকশনারিতে এমন কোনো 'কী (Key)' খুঁজতে গেলে যা সেখানে নেই, পাইথন সাথে সাথে `KeyError` দিয়ে প্রোগ্রাম ক্র্যাশ করে দেয়। `defaultdict` এই সমস্যার চমৎকার সমাধান। যদি 'কী (Key)' না থাকে, তবে সে এরর না দিয়ে নিজে নিজেই একটি ডিফল্ট ভ্যালু তৈরি করে নেয়!

```python
from collections import defaultdict

# সাধারণ ডিকশনারি
normal_dict = {'a': 1}
# print(normal_dict['b']) # KeyError দিবে প্রোগ্রাম ক্র্যাশ করবে!

# defaultdict তৈরি করা (ডিফল্ট ভ্যালু হিসেবে int বা 0 দেওয়া হলো)
error_free_dict = defaultdict(int)
error_free_dict['a'] = 1

# 'b' এর কোনো ভ্যালু দেওয়া হয়নি, কিন্তু সে এরর দিবে না, 0 প্রিন্ট করবে!
print(error_free_dict['b']) # 0

# চমৎকার ব্যবহার (লিস্ট গ্রুপিং)
students = [('Class A', 'Rahim'), ('Class B', 'Karim'), ('Class A', 'Jamal')]

# ডিফল্ট ভ্যালু হিসেবে list বা [] দেওয়া হলো
class_group = defaultdict(list)

for cls, name in students:
    class_group[cls].append(name) # 'Class A' না থাকলেও সে নিজে একটি লিস্ট বানিয়ে এপেন্ড করবে!

print(dict(class_group))
# আউটপুট: {'Class A': ['Rahim', 'Jamal'], 'Class B': ['Karim']}
```

### ৪. `deque` (সুপারফাস্ট লিস্ট)
`deque` (উচ্চারণ: ডেক - Double-ended queue) হলো লিস্টের অ্যাডভান্সড ভার্সন। 

**কেন `deque` ব্যবহার করবেন?**
সাধারণ পাইথন লিস্টের শেষের দিকে ডেটা যোগ করা (append) খুব ফাস্ট। কিন্তু লিস্টের একদম শুরুতে (`index 0`) কোনো ডেটা যোগ বা ডিলিট করতে গেলে, পাইথনকে পেছনের সব ডেটা এক ঘর করে সরাতে হয়। লক্ষ ডেটা থাকলে এটি প্রচুর সময় (O(n)) নেয়! কিন্তু `deque` এর শুরু বা শেষ—যেকোনো জায়গায় ডেটা অ্যাড বা রিমুভ করতে একই সময় (O(1)) লাগে!

```python
from collections import deque

# deque তৈরি করা
queue = deque(["Bob", "Charlie", "David"])

# ১. শেষে ডেটা যোগ করা (লিস্টের মতোই ফাস্ট)
queue.append("Eve")

# ২. একদম শুরুতে ডেটা যোগ করা (লিস্টের চেয়ে হাজার গুণ ফাস্ট!)
queue.appendleft("Alice")

print(queue) # deque(['Alice', 'Bob', 'Charlie', 'David', 'Eve'])

# ৩. শেষ থেকে ডিলিট করা
queue.pop() # Eve ডিলিট হলো

# ৪. একদম শুরু থেকে ডিলিট করা
queue.popleft() # Alice ডিলিট হলো

print(queue) # deque(['Bob', 'Charlie', 'David'])
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. `OrderedDict` (সিরিয়াল মেইনটেইন করা)
পাইথন ৩.৭ এর আগে সাধারণ ডিকশনারি ডেটা ঢোকানোর সিরিয়াল মনে রাখতো না। যদিও পাইথন ৩.৭ এর পর সাধারণ ডিকশনারিও সিরিয়াল মনে রাখে, তবুও `OrderedDict` এ কিছু এক্সট্রা মেথড থাকে যা সাধারণ ডিকশনারিতে থাকে না।

```python
from collections import OrderedDict

ordered = OrderedDict()
ordered['a'] = 1
ordered['b'] = 2
ordered['c'] = 3

print("Original:", ordered)

# কোনো আইটেমকে একদম শেষে বা শুরুতে পাঠিয়ে দেওয়া!
ordered.move_to_end('a') 
print("After moving 'a' to end:", ordered)
# আউটপুট: OrderedDict([('b', 2), ('c', 3), ('a', 1)])

# একদম শুরুতে পাঠাতে হলে last=False দিতে হয়
ordered.move_to_end('c', last=False)
```

### ৬. `ChainMap` (মাল্টিপল ডিকশনারি কম্বাইন করা)
ধরুন আপনার কাছে ৩-৪টি আলাদা ডিকশনারি আছে (যেমন: ইউজারের সেটিং, ডিফল্ট সেটিং, সিস্টেম সেটিং)। আপনি এগুলোকে একসাথে করে খুঁজতে চান, কিন্তু মেমোরিতে নতুন ডিকশনারি বানিয়ে কপি করে মেমোরি নষ্ট করতে চান না। `ChainMap` ঠিক এই কাজটিই করে!

```python
from collections import ChainMap

# ৩টি আলাদা ডিকশনারি
user_settings = {'theme': 'dark'}
env_settings = {'theme': 'light', 'language': 'en'}
default_settings = {'theme': 'blue', 'language': 'bn', 'font': 'Arial'}

# ChainMap দিয়ে লজিক্যালি কম্বাইন করা (মেমোরিতে কপি হবে না)
# যে আগে থাকবে তার প্রায়োরিটি বেশি!
combined_settings = ChainMap(user_settings, env_settings, default_settings)

# এটি প্রথমে user_settings এ খুঁজবে, পেলে দিয়ে দিবে
print("Theme:", combined_settings['theme']) # dark

# এটি user_settings এ পাবে না, তাই env_settings এ খুঁজবে
print("Language:", combined_settings['language']) # en

# এটি প্রথম দুটিতে পাবে না, তাই default_settings থেকে দিবে
print("Font:", combined_settings['font']) # Arial
```

### ৭. `UserDict`, `UserList`, `UserString` (কাস্টম ডেটা টাইপ)
ধরুন আপনি চাচ্ছেন এমন একটি লিস্ট বানাতে, যার ভেতর ডেটা ঢুকানোর সাথে সাথে ডেটাটি ডেটাবেসে সেভ হয়ে যাবে। আপনি সাধারণ `list` ক্লাসকে ইনহেরিট (Inherit) করে এটি করতে গেলে অনেক ঝামেলায় পড়বেন (C-লেভেলের ইমপ্লিমেন্টেশনের কারণে)। 

এজন্য পাইথন `UserList` দিয়েছে, যাকে ইনহেরিট করে আপনি নিজের ইচ্ছামতো লিস্টের বিহেভিয়ার চেঞ্জ করতে পারেন!

```python
from collections import UserList

# নিজের ইচ্ছামতো কাস্টম লিস্ট ক্লাস তৈরি
class DatabaseList(UserList):
    
    # আমরা append ফাংশনটিকে ওভাররাইট (Overwrite) করছি
    def append(self, item):
        print(f"[Database Log] Saving '{item}' to the database...")
        # তারপর অরিজিনাল লিস্টে ডেটা যোগ করছি
        super().append(item)
        print("[Database Log] Saved successfully!")

# আমাদের কাস্টম লিস্ট ব্যবহার করা
my_list = DatabaseList([1, 2, 3])

# আমরা সাধারণ লিস্টের মতোই কাজ করবো, কিন্তু ব্যাকগ্রাউন্ডে আমাদের কাস্টম কোড রান হবে!
my_list.append(4)

print("\nFinal List:", my_list.data) # ডেটা দেখতে .data ব্যবহার করতে হয়
```

### সারসংক্ষেপ (Conclusion)
কোম্পানির ইন্টারভিউ বা লিটকোড (LeetCode) এর মতো কম্পিটিটিভ প্রোগ্রামিংয়ে ভালো করার অন্যতম প্রধান হাতিয়ার হলো **`collections`** মডিউল। বিশেষ করে `Counter`, `defaultdict`, এবং `deque` এর ব্যবহার জানা থাকলে আপনি অন্যদের চেয়ে অনেক কম কোড লিখে খুব ফাস্ট প্রোগ্রাম বানাতে পারবেন!
