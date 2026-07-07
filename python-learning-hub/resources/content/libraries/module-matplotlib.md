# Matplotlib (Zero to Hero) কমপ্লিট গাইড

ডেটা সায়েন্স বা অ্যানালাইসিসের মূল উদ্দেশ্যই হলো ডেটার ভেতরে লুকিয়ে থাকা প্যাটার্ন (Pattern) খুঁজে বের করা। কিন্তু লক্ষ লক্ষ রো (Row) বা কলাম দেখে ডেটা বোঝা মানুষের পক্ষে প্রায় অসম্ভব! 

এজন্যই ডেটাকে ছবি বা গ্রাফের মাধ্যমে সুন্দর করে দেখানোর (Data Visualization) প্রয়োজন হয়। আর পাইথনে ডেটা ভিজ্যুয়ালাইজেশনের সবচেয়ে আদি, অকৃত্রিম এবং পাওয়ারফুল লাইব্রেরির নাম হলো **Matplotlib**।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের বেসিক লাইন গ্রাফ থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের অবজেক্ট-ওরিয়েন্টেড সাবপ্লট (Subplots) এবং 3D গ্রাফ বানানো পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক লাইন প্লট (Line Plot)
প্রথমে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install matplotlib numpy
```
Matplotlib এর ভেতর `pyplot` নামের একটি মডিউল আছে, যেটিকে পুরো দুনিয়ায় `plt` হিসেবে ইমপোর্ট করা হয়।

```python
import matplotlib.pyplot as plt
import numpy as np

# ১. কিছু ডামি ডেটা তৈরি করা (x এবং y অক্ষের জন্য)
x = np.array([1, 2, 3, 4, 5])
y = np.array([10, 20, 25, 30, 50])

# ২. লাইন গ্রাফ তৈরি করা
plt.plot(x, y)

# ৩. গ্রাফটি স্ক্রিনে দেখানো (এটি না লিখলে গ্রাফ দেখা যাবে না!)
plt.show()
```

### ২. টাইটেল, লেবেল এবং লিজেন্ড যুক্ত করা (Customization)
একটি সাদা-কালো গ্রাফ দেখতে ভালো লাগে না, আর গ্রাফে কীসের ডেটা আছে সেটাও বোঝা যায় না। চলুন একে অর্থপূর্ণ করি।

```python
import matplotlib.pyplot as plt

x = [2018, 2019, 2020, 2021, 2022]
profit = [10, 15, 12, 25, 30]

# গ্রাফের রং (color), মার্কার (বিন্দুগুলো), এবং লাইনের স্টাইল পরিবর্তন
plt.plot(x, profit, color='green', marker='o', linestyle='dashed', label='Company Profit')

# অক্ষগুলোর নাম দেওয়া
plt.xlabel("Years")
plt.ylabel("Profit (in Millions $)")

# গ্রাফের মূল টাইটেল
plt.title("Company Growth Over 5 Years")

# লিজেন্ড বা নির্দেশিকা দেখানো (যেখানে 'Company Profit' লেখা থাকবে)
plt.legend()

plt.show()
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. বিভিন্ন ধরনের গ্রাফ (Types of Plots)
ডেটার ধরন অনুযায়ী ভিন্ন ভিন্ন গ্রাফ ব্যবহার করতে হয়।

**ক) বার চার্ট (Bar Chart) - ক্যাটাগরি তুলনা করার জন্য:**
```python
import matplotlib.pyplot as plt

languages = ['Python', 'Java', 'C++', 'JavaScript']
popularity = [90, 70, 60, 85]

# বার চার্ট তৈরি করা (কালার লিস্টও দেওয়া যায়)
plt.bar(languages, popularity, color=['blue', 'orange', 'green', 'red'])
plt.title("Programming Language Popularity")
plt.show()
```

**খ) স্ক্যাটার প্লট (Scatter Plot) - দুটি ভ্যারিয়েবলের সম্পর্ক (Correlation) খোঁজার জন্য:**
মেশিন লার্নিংয়ে আউটলায়ার (Outlier) বা অস্বাভাবিক ডেটা খুঁজতে এটি খুব কাজে লাগে।
```python
import matplotlib.pyplot as plt
import numpy as np

# ১০০টি রেন্ডম ডেটা
x = np.random.rand(100)
y = np.random.rand(100)

plt.scatter(x, y, color='purple', alpha=0.5) # alpha দিয়ে ট্রান্সপারেন্সি বোঝায়
plt.title("Random Scatter Plot")
plt.show()
```

**গ) পাই চার্ট (Pie Chart) - শতকরা বা অনুপাত (Percentage) দেখানোর জন্য:**
```python
import matplotlib.pyplot as plt

shares = [40, 30, 20, 10]
companies = ['Apple', 'Samsung', 'Xiaomi', 'Others']

# explode দিয়ে কোনো একটি অংশকে একটু বের করে আনা যায় (যেমন প্রথমটি)
plt.pie(shares, labels=companies, explode=[0.1, 0, 0, 0], autopct='%1.1f%%', shadow=True)
plt.title("Smartphone Market Share")
plt.show()
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৪. অবজেক্ট-ওরিয়েন্টেড অ্যাপ্রোচ (Object-Oriented API)
বিগিনাররা সবসময় `plt.plot()` ব্যবহার করে। কিন্তু প্রফেশনাল লেভেলে বা যখন একটি স্ক্রিনে অনেকগুলো গ্রাফ (Dashboard) দেখাতে হয়, তখন অবজেক্ট-ওরিয়েন্টেড অ্যাপ্রোচ (OO API) ব্যবহার করা বাধ্যতামূলক। 

এখানে `Figure` হলো পুরো সাদা ক্যানভাসটি, আর `Axes` হলো তার ভেতরের এক বা একাধিক গ্রাফ!

```python
import matplotlib.pyplot as plt
import numpy as np

x = np.linspace(0, 10, 100)

# fig (ক্যানভাস) এবং ax (গ্রাফ) অবজেক্ট তৈরি করা
# figsize দিয়ে ক্যানভাসের সাইজ (প্রস্থ 8, উচ্চতা 4) সেট করা
fig, ax = plt.subplots(figsize=(8, 4))

# এখন আমরা plt এর বদলে ax ব্যবহার করে গ্রাফ আঁকবো!
ax.plot(x, np.sin(x), label='Sin Wave', color='blue')
ax.plot(x, np.cos(x), label='Cos Wave', color='red')

# টাইটেল ও লেবেল সেট করার নিয়ম কিছুটা ভিন্ন
ax.set_title("Sine and Cosine Waves")
ax.set_xlabel("Time")
ax.set_ylabel("Amplitude")

ax.legend()
plt.show()
```

### ৫. সাবপ্লট (Subplots) - একসাথে একাধিক গ্রাফ দেখানো
ড্যাশবোর্ডের মতো একটি স্ক্রিনেই ২-৩টি গ্রাফ পাশাপাশি বা ওপর-নিচ করে দেখানো।

```python
import matplotlib.pyplot as plt
import numpy as np

x = np.linspace(0, 5, 10)

# ১ সারি এবং ২ কলামের ক্যানভাস তৈরি করা
fig, (ax1, ax2) = plt.subplots(nrows=1, ncols=2, figsize=(10, 4))

# প্রথম গ্রাফ (বাম দিকে)
ax1.plot(x, x**2, color='blue')
ax1.set_title("Square (x^2)")

# দ্বিতীয় গ্রাফ (ডান দিকে)
ax2.plot(x, x**3, color='orange')
ax2.set_title("Cube (x^3)")

# গ্রাফগুলো যেন একটার ওপর আরেকটা উঠে না যায়, সেজন্য টাইট লেআউট
plt.tight_layout()
plt.show()
```

### ৬. গ্রাফকে ইমেজ হিসেবে সেভ করা (Saving Figures)
রিপোর্টে বা ওয়েবসাইটে দেখানোর জন্য গ্রাফটিকে তো সেভ করতে হবে!

```python
import matplotlib.pyplot as plt

plt.plot([1, 2, 3], [10, 20, 30])
plt.title("My Awesome Graph")

# show() কল করার আগেই savefig() কল করতে হয়!
# dpi (Dots per inch) বাড়িয়ে দিলে ছবির রেজোলিউশন বা কোয়ালিটি অনেক ভালো হয়।
plt.savefig("my_graph.png", dpi=300, bbox_inches='tight')

# plt.show()
```

### ৭. 3D প্লটিং (3D Plotting - Bonus Magic!)
Matplotlib দিয়ে চমৎকার থ্রিডি (3D) গ্রাফও আঁকা যায়!

```python
import matplotlib.pyplot as plt
import numpy as np

# থ্রিডি ক্যানভাস তৈরি
fig = plt.figure()
ax = fig.add_subplot(111, projection='3d')

# ডেটা তৈরি
z = np.linspace(0, 15, 1000)
x = np.sin(z)
y = np.cos(z)

# থ্রিডি লাইন ড্র করা
ax.plot3D(x, y, z, 'red')
ax.set_title("3D Spiral Magic")

plt.show()
```

### সারসংক্ষেপ (Conclusion)
যদিও বর্তমানে আরও অনেক সুন্দর এবং মডার্ন লাইব্রেরি (যেমন: **Seaborn**, **Plotly**) বের হয়েছে, কিন্তু সেই সব লাইব্রেরিগুলো মূলত এই **Matplotlib** এর ওপরেই ভিত্তি করে বানানো! তাই অবজেক্ট-ওরিয়েন্টেড (`fig, ax = plt.subplots()`) পদ্ধতিটি আপনার জানা থাকলে আপনি ডেটা ভিজ্যুয়ালাইজেশনের দুনিয়ায় রাজত্ব করতে পারবেন!
