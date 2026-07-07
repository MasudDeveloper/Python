# Datetime (Zero to Hero) কমপ্লিট গাইড

সফটওয়্যার ডেভেলপমেন্টে সবচেয়ে কনফিউজিং এবং ক্রিটিক্যাল বিষয়গুলোর মধ্যে একটি হলো **তারিখ ও সময় (Date & Time)** নিয়ে কাজ করা! 

ধরুন, আপনি একটি ইকমার্স সাইট বানিয়েছেন। কেউ বাংলাদেশ থেকে রাত ১০টায় একটি অর্ডার করলো, আর আপনি আপনার সার্ভার (যা হয়তো আমেরিকায় আছে) থেকে টাইম সেভ করলেন। ফলাফল? অর্ডারের টাইম ১২ ঘণ্টা উলটপালট হয়ে যাবে! 

এই ধরনের টাইমজোন সমস্যা, কত দিন বাকি আছে তার হিসাব, কিংবা স্ট্রিং থেকে ডেট বের করার কাজগুলো করার জন্য পাইথনের সবচেয়ে পাওয়ারফুল মডিউল হলো **`datetime`**।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের টাইমজোন (Timezone) এবং ইউনিক্স টাইমস্ট্যাম্প (Unix Timestamp) পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. বর্তমান সময় এবং তারিখ বের করা
`datetime` মডিউলের ভেতরে `datetime` নামের একটি ক্লাস আছে।
```python
from datetime import datetime, date

# ১. বর্তমান তারিখ এবং সময় (Today's Date & Time)
now = datetime.now()
print("এখনকার সময়:", now) 
# আউটপুট: 2026-07-05 21:05:30.123456

# ২. শুধু আজকের তারিখ (Time ছাড়া)
today = date.today()
print("আজকের তারিখ:", today) 
# আউটপুট: 2026-07-05
```

### ২. তারিখ থেকে দিন, মাস, বছর আলাদা করা
কখনো কখনো আমাদের পুরো ডেট দরকার হয় না, শুধু বছর বা মাস দরকার হয়।
```python
from datetime import datetime

now = datetime.now()

print("বছর (Year):", now.year)   # 2026
print("মাস (Month):", now.month) # 7
print("দিন (Day):", now.day)     # 5
print("ঘণ্টা (Hour):", now.hour) # 21
```

### ৩. নিজের ইচ্ছামতো একটি নির্দিষ্ট তারিখ তৈরি করা
ধরুন আপনি কারও জন্মদিনের একটি অবজেক্ট তৈরি করতে চান।
```python
from datetime import datetime, date

# ফরম্যাট: date(year, month, day)
birthday = date(2000, 1, 15)
print("জন্মদিন:", birthday)

# যদি সময়সহ তৈরি করতে চান: datetime(year, month, day, hour, minute, second)
meeting = datetime(2026, 12, 31, 14, 30, 0)
print("মিটিংয়ের সময়:", meeting)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. সময়ের হিসাব-নিকাশ (Timedelta)
ধরুন, আজকে থেকে ১০০ দিন পর কী বার হবে? অথবা আপনার সাবস্ক্রিপশন শেষ হতে আর কত দিন বাকি আছে? এই যোগ-বিয়োগগুলো করার জন্য **`timedelta`** ব্যবহার করা হয়।

```python
from datetime import datetime, timedelta

today = datetime.now()

# ১. বর্তমান সময়ের সাথে ১০ দিন যোগ করা
ten_days_later = today + timedelta(days=10)
print("১০ দিন পর তারিখ হবে:", ten_days_later)

# ২. বর্তমান সময় থেকে ৩ সপ্তাহ এবং ৫ ঘণ্টা মাইনাস করা
past_time = today - timedelta(weeks=3, hours=5)
print("৩ সপ্তাহ ৫ ঘণ্টা আগে সময় ছিল:", past_time)

# ৩. দুটি তারিখের মধ্যে পার্থক্য বের করা (যেমন বয়স হিসাব করা)
birth_date = datetime(1995, 8, 20)
difference = today - birth_date

print(f"আপনার বয়স {difference.days} দিন!")
```

### ৫. তারিখকে সুন্দর টেক্সটে রূপান্তর করা (`strftime`)
আমাদের সিস্টেমে ডেট থাকে `2026-07-05` ফরম্যাটে, কিন্তু আমরা ইউজারকে দেখাতে চাই `"05 July, 2026"` হিসেবে। একে বলা হয় String Formatting (`strftime` - string format time)।

```python
from datetime import datetime

now = datetime.now()

# %d = দিন (05), %B = মাসের পুরো নাম (July), %Y = বছর (2026)
formatted_date = now.strftime("%d %B, %Y")
print("ইউজার ফ্রেন্ডলি ডেট:", formatted_date)
# আউটপুট: 05 July, 2026

# সময়সহ (12-hour format)
# %I = ঘণ্টা (12 hr), %M = মিনিট, %p = AM/PM
formatted_time = now.strftime("%d-%m-%Y %I:%M %p")
print("সময়সহ:", formatted_time)
# আউটপুট: 05-07-2026 09:05 PM
```

### ৬. টেক্সট থেকে তারিখ অবজেক্ট বানানো (`strptime`)
ধরুন ইউজার একটি ফর্মে তার জন্মতারিখ লিখলো `"15/01/2000"`। এটি তো একটি সাধারণ স্ট্রিং বা টেক্সট। এর সাথে আপনি ১০ দিন যোগ করতে পারবেন না। একে ডেট অবজেক্ট বানানোর জন্য `strptime` (string parse time) ব্যবহার করতে হয়।

```python
from datetime import datetime

date_string = "15/01/2000"

# পাইথনকে বুঝিয়ে দিতে হবে স্ট্রিংয়ের কোথায় কী আছে
# %d = দিন, %m = মাস, %Y = বছর
date_object = datetime.strptime(date_string, "%d/%m/%Y")

print("Python Date Object:", type(date_object))
# এখন আমরা চাইলে এর সাথে timedelta দিয়ে দিন যোগ-বিয়োগ করতে পারবো!
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. টাইমজোন বা দেশের সময় হ্যান্ডেল করা (Timezones)
পাইথনে সাধারণ `datetime.now()` যে ডেটা দেয়, তাকে বলা হয় **"Naive"** ডেটটাইম (কারণ এটি জানে না এটি কোন দেশের সময়)। 

কিন্তু প্রফেশনাল প্রোজেক্টে আমাদেরকে **"Aware"** ডেটটাইম নিয়ে কাজ করতে হয় (যে ডেটটাইম জানে সে কোন টাইমজোনে আছে)। এর জন্য পাইথন ৩.৯ থেকে **`zoneinfo`** নামের একটি বিল্ট-ইন মডিউল দেওয়া হয়েছে। (পুরোনো ভার্সনে `pytz` লাইব্রেরি ব্যবহার করা হতো)।

```python
from datetime import datetime
from zoneinfo import ZoneInfo # Python 3.9+

# ১. UTC (গ্লোবাল স্ট্যান্ডার্ড) সময় বের করা
utc_now = datetime.now(ZoneInfo("UTC"))
print("লন্ডন বা UTC সময়:", utc_now)

# ২. বাংলাদেশের (Asia/Dhaka) সময় বের করা
dhaka_now = datetime.now(ZoneInfo("Asia/Dhaka"))
print("বাংলাদেশের সময়:", dhaka_now)

# ৩. আমেরিকার (America/New_York) সময় বের করা
ny_now = datetime.now(ZoneInfo("America/New_York"))
print("নিউ ইয়র্কের সময়:", ny_now)

# ৪. টাইমজোন কনভার্ট করা (যেমন বাংলাদেশের একটি সময়কে নিউ ইয়র্কের সময়ে নেওয়া)
meeting_in_dhaka = datetime(2026, 7, 10, 20, 0, tzinfo=ZoneInfo("Asia/Dhaka")) # রাত ৮টা
meeting_in_ny = meeting_in_dhaka.astimezone(ZoneInfo("America/New_York"))
print("নিউ ইয়র্কের ক্লায়েন্টের ক্যালেন্ডারে মিটিংয়ের সময়:", meeting_in_ny)
```
*(এই টাইমজোন কনভার্সনটি যদি আপনি না জানেন, তবে আপনার সফটওয়্যারের ইউজাররা সব সময় মিটিং বা ইভেন্ট মিস করবে!)*

### ৮. ইউনিক্স টাইমস্ট্যাম্প (Unix Timestamp)
**Timestamp** হলো ১ জানুয়ারি ১৯৭০ সাল থেকে বর্তমান সময় পর্যন্ত টোটাল কত সেকেন্ড পার হয়েছে, তার হিসাব! এটি একটি বিশাল নাম্বার। ডেটাবেসে (যেমন: MongoDB, Firebase) সাধারণত ডেট সেভ না করে এই নাম্বারটি সেভ করা হয়, কারণ নাম্বার প্রসেস করা কম্পিউটারের জন্য ফাস্ট।

```python
from datetime import datetime

now = datetime.now()

# ১. বর্তমান সময়কে Timestamp (সেকেন্ডে) কনভার্ট করা
timestamp = now.timestamp()
print("বর্তমান Timestamp:", timestamp) 
# আউটপুট: 1783264560.123

# ২. Timestamp থেকে আবার নরমাল Date এ ফিরে আসা
normal_date = datetime.fromtimestamp(1783264560)
print("ফিরে আসা ডেট:", normal_date)
```

### ৯. মাসের শেষ দিন বের করা (`calendar` মডিউলের সাথে)
ফেব্রুয়ারি মাস কি ২৮ দিনে নাকি ২৯ দিনে (Leap Year)? এটা নিজে হিসাব করা বোকামি। এজন্য আমরা `calendar` মডিউল ব্যবহার করবো।

```python
import calendar
from datetime import datetime

# বর্তমান বছর এবং মাস
year = 2024
month = 2 # ফেব্রুয়ারি

# monthrange দুটি ভ্যালু দেয়: (প্রথম দিন কী বার, ওই মাসে মোট দিন কয়টি)
_, total_days = calendar.monthrange(year, month)

print(f"{year} সালের ফেব্রুয়ারি মাসে মোট {total_days} দিন ছিল!") # 29
```

### সারসংক্ষেপ (Conclusion)
সফটওয়্যার ডেভেলপমেন্টে সব সময় ডেটাবেসে টাইম সেভ করার সময় **UTC Timezone** সেভ করা উচিত। তারপর ইউজার যখন ওয়েবসাইট ভিজিট করবে, তখন ফ্রন্টএন্ডে বা পাইথনের `astimezone()` দিয়ে তার দেশের সময় অনুযায়ী (Local Time) কনভার্ট করে দেখানো উচিত। আর এই পুরো জিনিসটিই কন্ট্রোল করে **`datetime`** মডিউল!
