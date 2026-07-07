# Itertools (Zero to Hero) কমপ্লিট গাইড

পাইথনে যখন আমরা কোনো লিস্ট বা ডেটা স্ট্রাকচার নিয়ে `for loop` চালাই, তখন পাইথন সাধারণত পুরো লিস্টটিকে মেমোরিতে (RAM) লোড করে। যদি আপনার কাছে ১ কোটি ডেটা থাকে, তবে মেমোরি ফুল হয়ে কম্পিউটার ক্র্যাশ করতে পারে! 

এই মেমোরি ক্র্যাশের হাত থেকে বাঁচার জন্য এবং লুপ বা ইটারেশনকে (Iteration) আলোর গতিতে ফাস্ট করার জন্য পাইথনের একটি জাদুকরী লাইব্রেরি হলো **`itertools`**। এটি পাইথনের C ল্যাঙ্গুয়েজ ইমপ্লিমেন্টেশনের মাধ্যমে কাজ করে, তাই এটি সাধারণ পাইথন লুপের চেয়ে বহুগুণ ফাস্ট!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের অসীম লুপ (Infinite Loop) থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের `combinations`, `groupby` এবং মেমোরি সেভিং স্লাইসিং (`islice`) পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)
**Infinite Iterators (অসীম লুপ):**
`itertools` এর কিছু ফাংশন এমন আছে যেগুলো অনন্তকাল ধরে লুপ চালাতে পারে, কিন্তু মেমোরি একটুও নষ্ট করে না!

### ১. `count` (সীমাহীন গণনা)
আপনি যদি ১ থেকে শুরু করে অসীম পর্যন্ত নাম্বার তৈরি করতে চান, তবে `count` ব্যবহার করতে পারেন।

```python
import itertools

# ৫ থেকে শুরু হবে এবং ২ করে বাড়বে
for i in itertools.count(5, 2):
    print(i)
    if i >= 15: # আমরা জোর করে ১৫ তে গিয়ে লুপ থামিয়ে দিলাম
        break

# আউটপুট: 5, 7, 9, 11, 13, 15
```

### ২. `cycle` (একই প্যাটার্ন বারবার রিপিট করা)
ধরুন আপনার কাছে ৩টি সার্ভার আছে (A, B, C) এবং আপনি রিকোয়েস্টগুলোকে বারবার A, B, C, A, B, C এভাবে পাঠাতে চান।

```python
import itertools

servers = ['Server A', 'Server B', 'Server C']
counter = 0

for server in itertools.cycle(servers):
    print(f"Request sent to: {server}")
    counter += 1
    if counter == 5: # ৫ বার চালানোর পর থামিয়ে দিলাম
        break

# আউটপুট: A, B, C, A, B
```

### ৩. `repeat` (নির্দিষ্ট ভ্যালু বারবার তৈরি করা)
কোনো একটি নির্দিষ্ট ভ্যালুকে যদি নির্দিষ্ট কয়েকবার জেনারেট করতে হয়।
```python
import itertools

# "Hello" কে ৩ বার রিপিট করবে
for word in itertools.repeat("Hello", 3):
    print(word)

# আউটপুট: Hello, Hello, Hello
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)
**Combinatoric Generators (বিন্যাস ও সমাবেশ):**
অ্যালগরিদমের কঠিন কঠিন সব কাজ (যেমন: পাসওয়ার্ড ক্র্যাকিং বা হ্যাকিংয়ের কম্বিনেশন তৈরি) `itertools` মাত্র ১ লাইনে করে দেয়!

### ৪. `permutations` (বিন্যাস - অর্ডার ম্যাটার করে)
ধরি ৩ জন মানুষ আছে (A, B, C)। ২ জনকে কতভাবে সাজানো যায়, যেখানে সিরিয়াল বা অর্ডার গুরুত্বপূর্ণ? (অর্থাৎ AB এবং BA আলাদা)।

```python
import itertools

letters = ['A', 'B', 'C']
# ২ জনের জোড়া বানাতে চাই
perms = itertools.permutations(letters, 2)

print(list(perms))
# আউটপুট: [('A', 'B'), ('A', 'C'), ('B', 'A'), ('B', 'C'), ('C', 'A'), ('C', 'B')]
```

### ৫. `combinations` (সমাবেশ - অর্ডার ম্যাটার করে না)
এটি `permutations` এর মতোই, কিন্তু এখানে অর্ডার বা সিরিয়ালের কোনো দাম নেই। (অর্থাৎ AB আর BA একই জিনিস)। 

```python
import itertools

letters = ['A', 'B', 'C']
combs = itertools.combinations(letters, 2)

print(list(combs))
# আউটপুট: [('A', 'B'), ('A', 'C'), ('B', 'C')]
```

### ৬. `product` (Nested Loop এর বিকল্প / Cartesian Product)
ধরুন, আপনি ২টি আলাদা লিস্টের প্রতিটি উপাদানের সাথে অন্য লিস্টের উপাদানের জোড়া বানাতে চান। সাধারণ নিয়মে এর জন্য ২টি ফর লুপ (Nested Loop) লিখতে হয়। `product` এটি ১ লাইনেই করে দেয়!

```python
import itertools

colors = ['Red', 'Blue']
sizes = ['S', 'M', 'L']

# ২ লেয়ারের Nested Loop এর কাজ এক লাইনে!
combinations = itertools.product(colors, sizes)

print(list(combinations))
# আউটপুট: [('Red', 'S'), ('Red', 'M'), ('Red', 'L'), ('Blue', 'S'), ('Blue', 'M'), ('Blue', 'L')]
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)
**Terminating Iterators (মেমোরি সেভিং ম্যাজিক):**

### ৭. `chain` (একাধিক লিস্টকে জোড়া লাগানো)
৩-৪টি বিশাল লিস্টকে একসাথে করে একটি সিঙ্গেল লিস্ট বানাতে গেলে পাইথন মেমোরিতে নতুন আরেকটি বিশাল লিস্ট তৈরি করে। এতে র‍্যাম (RAM) ফুল হয়ে যায়। `chain` মেমোরিতে নতুন লিস্ট না বানিয়েই এগুলোকে লজিক্যালি একসাথে করে দেয়!

```python
import itertools

list1 = [1, 2, 3]
list2 = ['a', 'b', 'c']
list3 = [True, False]

# মেমোরি না বাড়িয়ে ৩টি লিস্টের ওপর একবারে লুপ চালানো
for item in itertools.chain(list1, list2, list3):
    print(item, end=" ")
    
# আউটপুট: 1 2 3 a b c True False 
```

### ৮. `groupby` (ডেটা ক্যাটাগরি করা)
Pandas বা SQL এর `GROUP BY` এর মতো পাইথন লিস্টের ডেটাগুলোকেও ক্যাটাগরি বা গ্রুপ করা যায়।
*(বিঃদ্রঃ `groupby` ব্যবহার করার আগে ডেটাকে অবশ্যই ওই 'কী' (key) অনুযায়ী সর্ট বা সাজিয়ে নিতে হবে!)*

```python
import itertools

# কিছু ইউজারের ডেটা (লিস্ট অফ ডিকশনারি)
users = [
    {'name': 'Rahim', 'role': 'Admin'},
    {'name': 'Karim', 'role': 'User'},
    {'name': 'Jamal', 'role': 'Admin'},
    {'name': 'Salam', 'role': 'User'}
]

# ১. আগে ডেটাকে 'role' অনুযায়ী সর্ট করতে হবে
users.sort(key=lambda x: x['role'])

# ২. এবার groupby দিয়ে গ্রুপ করা
grouped = itertools.groupby(users, key=lambda x: x['role'])

for role, group in grouped:
    print(f"\n--- {role} ---")
    for user in group:
        print(user['name'])
        
# আউটপুট:
# --- Admin ---
# Rahim
# Jamal
# --- User ---
# Karim
# Salam
```

### ৯. `islice` (লিস্ট স্লাইসিংয়ের বেস্ট উপায়)
ধরুন একটি ফাইলে বা জেনারেটরে ১০ লক্ষ ডেটা আছে। আপনি শুধু ১০ নাম্বার থেকে ২০ নাম্বার ডেটাগুলো দেখতে চান। আপনি যদি সাধারণ স্লাইসিং (`data[10:20]`) করেন, তবে পাইথন আগে ১০ লক্ষ ডেটা মেমোরিতে নিবে, তারপর স্লাইস করবে! 

কিন্তু `islice` ব্যবহার করলে পাইথন মেমোরিতে কিছু না নিয়েই সরাসরি ১০-২০ নাম্বার ডেটা বের করে দিবে।

```python
import itertools

# ১ থেকে ১০ লক্ষ পর্যন্ত নাম্বার
numbers = range(1, 1000000)

# আমরা শুধুমাত্র ১০ নাম্বার থেকে ১৫ নাম্বার ডেটা চাই (start=10, stop=15)
result = itertools.islice(numbers, 10, 15)

print(list(result))
# আউটপুট: [11, 12, 13, 14, 15] 
# (ম্যাজিকের মতো কোনো মেমোরি খরচ ছাড়াই সাথে সাথে রেজাল্ট চলে আসবে!)
```

### ১০. `accumulate` (রানিং টোটাল / Running Sum)
লিস্টের প্রতিটি উপাদানের সাথে আগের উপাদানগুলো যোগ করে করে রানিং টোটাল (Running Total) বের করা।

```python
import itertools
import operator

numbers = [1, 2, 3, 4, 5]

# রানিং টোটাল (Running Sum)
running_sum = itertools.accumulate(numbers)
print(list(running_sum)) 
# আউটপুট: [1, 3, 6, 10, 15] 

# চাইলে গুণফলও বের করা যায় (Running Product)
running_prod = itertools.accumulate(numbers, operator.mul)
print(list(running_prod))
# আউটপুট: [1, 2, 6, 24, 120]
```

### সারসংক্ষেপ (Conclusion)
কম্পিটিটিভ প্রোগ্রামিং (Competitive Programming), হ্যাকারর‍্যাঙ্ক (HackerRank) বা লিটকোড (LeetCode) সলভ করার সময় **`itertools`** হলো প্রো-কোডারদের গোপন অস্ত্র! এটি শুধু আপনার কোডকে ছোটই করে না, বরং আপনার প্রোগ্রামের মেমোরি কনজাম্পশন (Memory Consumption) এবং টাইম কমপ্লেক্সিটি (Time Complexity) জাদুকরীভাবে কমিয়ে দেয়!
