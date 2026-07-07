# Sys (Zero to Hero) কমপ্লিট গাইড

পাইথনে `os` মডিউল দিয়ে আমরা অপারেটিং সিস্টেমকে (Operating System) কন্ট্রোল করি। কিন্তু পাইথন প্রোগ্রামটি যে ইন্টারপ্রেটারের (Interpreter) মাধ্যমে রান হচ্ছে (অর্থাৎ পাইথন নিজে), তাকে কন্ট্রোল করার জন্য **`sys` (System-specific parameters and functions)** মডিউল ব্যবহার করা হয়।

কোড রান হওয়ার সময় টার্মিনাল থেকে ডেটা পাঠানো, পাইথনের ভার্সন চেক করা বা প্রোগ্রামকে মাঝপথেই বন্ধ করে দেওয়ার মতো মারাত্মক কাজগুলো এই মডিউল দিয়ে করা হয়!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ভার্সন চেক থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Command Line Arguments এবং Memory Management পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. পাইথনের ভার্সন এবং প্ল্যাটফর্ম দেখা
আপনার কোডটি কি পাইথন ৩.৮ এ রান হচ্ছে নাকি ৩.১২ তে? এবং এটি কি উইন্ডোজে রান হচ্ছে নাকি লিনাক্সে?

```python
import sys

# ১. পাইথনের ডিটেইলস ভার্সন
print("Python Version:\n", sys.version)
# আউটপুট: 3.12.3 (tags/v3.12.3:...)

# শুধু মেইন ভার্সন নম্বর দেখতে চাইলে (যেমন: 3, 12, 3)
print("Version Info:", sys.version_info)

# ২. কোন অপারেটিং সিস্টেমে কোডটি রান হচ্ছে?
# win32 = Windows, linux = Linux, darwin = Mac
print("Platform:", sys.platform)
```

### ২. প্রোগ্রাম জোর করে বন্ধ করা (`sys.exit`)
কখনো কখনো কোনো এরর হলে বা কন্ডিশন না মিললে আমরা চাই পাইথন প্রোগ্রামটি যেন ওখানেই বন্ধ হয়ে যায় এবং পরের লাইনগুলো আর রান না করে।

```python
import sys

user_age = 15

if user_age < 18:
    print("You are not allowed to use this app!")
    # প্রোগ্রামটি এখানেই বন্ধ হয়ে যাবে!
    # (1 বা যেকোনো নম্বর দিলে অপারেটিং সিস্টেম বুঝবে যে প্রোগ্রামটি এরর খেয়ে বন্ধ হয়েছে)
    sys.exit(1)

# ওপরের exit() কল হওয়ার কারণে এই লাইনটি আর কখনোই প্রিন্ট হবে না!
print("Welcome to the main application!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. টার্মিনাল থেকে ডেটা পাঠানো (`sys.argv`)
আপনি যদি টার্মিনাল থেকে পাইথন ফাইল রান করার সময় (যেমন: `python script.py hello 10`) ফাইলের নামের সাথে কোনো ডেটা বা কমান্ড পাস করেন, তবে পাইথন সেগুলোকে `sys.argv` (Argument Variables) নামের একটি লিস্টের ভেতর সেভ করে রাখে।

ধরি আমাদের একটি ফাইল আছে `downloader.py`:
```python
# downloader.py ফাইলের কোড
import sys

# argv হলো একটি লিস্ট। এর 0 নাম্বার ইনডেক্সে ফাইলের নিজের নাম থাকে!
print("All Arguments:", sys.argv)

if len(sys.argv) > 1:
    # 1 নাম্বার ইনডেক্সে ইউজারের প্রথম ডেটা থাকে
    video_url = sys.argv[1]
    print(f"Downloading from: {video_url}")
else:
    print("Please provide a URL! Example: python downloader.py https://youtube.com/...")
```
টার্মিনালে রান করার নিয়ম: `python downloader.py https://example.com`

### ৪. পাইথন কোথা থেকে প্যাকেজ ইমপোর্ট করে? (`sys.path`)
আপনি যখন কোডে `import pandas` লেখেন, তখন পাইথন কম্পিউটারের কোথায় কোথায় এই 'pandas' প্যাকেজটি খোঁজে? তার একটি লিস্ট সেভ করা থাকে `sys.path` এ।

```python
import sys

print("Python Module Search Paths:")
for path in sys.path:
    print(path)

# আপনি চাইলে নিজের মতো করে কোনো কাস্টম ফোল্ডারকেও এই লিস্টে অ্যাড করতে পারেন!
# sys.path.append('C:/My_Custom_Modules')
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. মেমোরি ম্যানেজমেন্ট (`sys.getsizeof`)
আপনার বানানো একটি লিস্ট বা ডিকশনারি র‍্যামে (RAM) কতটুকু মেমোরি খরচ করছে, তা চেক করা প্রো-লেভেল ডেভেলপারদের কাজ!

```python
import sys

# একটি সাধারণ সংখ্যা
num = 42
print("Integer Size (bytes):", sys.getsizeof(num)) # 28 bytes (পাইথনে ইন্টিজার অবজেক্ট হিসেবে থাকে)

# একটি সাধারণ স্ট্রিং
text = "Hello"
print("String Size (bytes):", sys.getsizeof(text)) # 54 bytes

# বিশাল বড় একটি লিস্ট
numbers_list = list(range(1000000))
print("List Size (MB):", sys.getsizeof(numbers_list) / (1024 * 1024)) # প্রায় 8 MB!
```

### ৬. কাস্টম আউটপুট এবং এরর স্ট্রিম (`stdout` ও `stderr`)
পাইথনে আমরা যখন `print()` কল করি, তখন ব্যাকগ্রাউন্ডে পাইথন ডেটাগুলোকে `sys.stdout` (Standard Output) এর মাধ্যমে স্ক্রিনে পাঠায়। আমরা চাইলে এই আউটপুটকে হ্যাক করে স্ক্রিনের বদলে ফাইলে পাঠিয়ে দিতে পারি!

```python
import sys

# ১. নরমাল প্রিন্ট (স্ক্রিনে দেখাবে)
print("This goes to the console.")

# ২. Error Stream এ মেসেজ পাঠানো (স্ক্রিনে লাল রঙে এরর হিসেবে দেখানোর জন্য)
sys.stderr.write("Warning: Something went wrong!\n")

# ৩. stdout কে হ্যাক করে ফাইলে পয়েন্ট করে দেওয়া!
original_stdout = sys.stdout # অরিজিনালটা সেভ করে রাখলাম
with open('log.txt', 'w') as f:
    sys.stdout = f # এখন থেকে সব প্রিন্ট স্ক্রিনের বদলে ফাইলে যাবে!
    
    print("This message will not show on screen.")
    print("It will be directly saved to log.txt!")

# কাজ শেষে আবার আগের জায়গায় ফিরিয়ে আনা
sys.stdout = original_stdout
print("We are back on the screen!")
```

### সারসংক্ষেপ (Conclusion)
আপনি যখন কোনো CLI (Command Line Interface) টুল বানাবেন, তখন ইউজারের কমান্ড রিসিভ করার জন্য **`sys.argv`** লাগবেই। এছাড়া সার্ভারে কোনো এরর হলে বা স্ক্রিপ্ট মাঝপথে বন্ধ করতে হলে **`sys.exit()`** এবং **`sys.stderr`** এর ব্যবহার একজন প্রো-লেভেল ব্যাকএন্ড ডেভেলপারের জন্য অবশ্য পাঠ্য!
