# Statistics (Zero to Hero) কমপ্লিট গাইড

ডেটা অ্যানালাইসিস বা মেশিন লার্নিংয়ের জন্য আমরা সাধারণত বিশাল সাইজের লাইব্রেরি যেমন: `Pandas` বা `NumPy` ব্যবহার করি। কিন্তু আপনার কাছে যদি খুব ছোট কোনো ডেটাসেট থাকে এবং আপনি শুধু বেসিক কিছু স্ট্যাটিস্টিকস (গড়, মধ্যক) বের করতে চান, তবে থার্ড-পার্টি লাইব্রেরি ইনস্টল করার কোনো দরকার নেই!

পাইথনের বিল্ট-ইন **`statistics`** মডিউল দিয়েই এই কাজগুলো খুব সহজে এবং একুরেটভাবে করা যায়।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Normal Distribution এবং Z-Score পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. গড় (Mean / Average) বের করা
সবগুলো ডেটাকে যোগ করে ডেটার পরিমাণ দিয়ে ভাগ করা।
```python
import statistics

scores = [85, 90, 75, 88, 92]

avg_score = statistics.mean(scores)
print("গড় মার্ক (Mean):", avg_score) # 86.0
```

### ২. মধ্যক (Median) বের করা
গড় (Mean) এর একটি বড় সমস্যা হলো, ডেটার মধ্যে যদি একটি বিশাল আউটলায়ার (Outlier) থাকে, তবে গড়ের রেজাল্ট নষ্ট হয়ে যায়। 
যেমন: ৫ জনের বেতনের লিস্টে ৪ জন পায় ১০ হাজার, আর ১ জন পায় ১ কোটি! এদের গড় করলে রেজাল্ট ২০ লাখ দেখাবে, যা অবাস্তব। এই সমস্যা সমাধানে **Median (মধ্যক)** ব্যবহার করা হয়। এটি ডেটাগুলোকে ছোট থেকে বড় সাজিয়ে ঠিক মাঝখানের ভ্যালুটি নেয়।

```python
import statistics

# এখানে 1000000 হলো আউটলায়ার
salaries = [10000, 12000, 11000, 10500, 1000000]

print("গড় বেতন (Mean):", statistics.mean(salaries)) # 208700 (অবাস্তব!)
print("মধ্যক বেতন (Median):", statistics.median(salaries)) # 11000 (সঠিক ধারণা দেয়)
```

### ৩. প্রচুরক (Mode) বের করা
যে ডেটাটি লিস্টের মধ্যে সবচেয়ে বেশিবার আছে। ইকমার্সে সবচেয়ে বেশি বিক্রিত প্রোডাক্ট (Best Seller) বের করতে এটি কাজে লাগে।

```python
import statistics

votes = ['Alice', 'Bob', 'Alice', 'Charlie', 'Alice', 'Bob']

# কে সবচেয়ে বেশি ভোট পেয়েছে?
winner = statistics.mode(votes)
print("সবচেয়ে বেশি ভোট পেয়েছে (Mode):", winner) # Alice
```
*(বিঃদ্রঃ পাইথন ৩.৮ এর পর থেকে একাধিক প্রচুরক থাকলে `statistics.multimode()` ব্যবহার করা যায়)*

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. ভ্যারিয়েন্স এবং স্ট্যান্ডার্ড ডেভিয়েশন (Variance & Stdev)
ডেটাগুলো তাদের গড় (Mean) থেকে গড়ে কতটা দূরে ছড়িয়ে আছে, তা মাপার জন্য এগুলো ব্যবহৃত হয়। স্ট্যান্ডার্ড ডেভিয়েশন কম হওয়া মানে ডেটাগুলো গড়ের খুব কাছাকাছি (Consistent)। আর বেশি হওয়া মানে ডেটাগুলো অনেক বেশি ছড়ানো (Inconsistent)।

```python
import statistics

# ধরি ২ জন ব্যাটসম্যানের ৫টি ম্যাচের রান
player1 = [50, 52, 48, 51, 49] # সব সময় গড়ের কাছাকাছি রান করে
player2 = [10, 100, 5, 85, 50] # কখনো খুব ভালো, কখনো খুব খারাপ

print("Player 1 StDev:", statistics.stdev(player1)) # 1.58
print("Player 2 StDev:", statistics.stdev(player2)) # 41.68
```
*(যেহেতু Player 1 এর StDev কম, তাই সে অনেক বেশি কনসিস্টেন্ট বা নির্ভরযোগ্য ব্যাটসম্যান!)*

### ৫. কোয়ান্টাইলস বা পার্সেন্টাইলস (Quantiles)
পুরো ডেটাসেটকে ৪টি সমান ভাগে (Quartiles) ভাগ করা। এটি বক্স প্লট (Box Plot) তৈরি করতে এবং ডেটার রেঞ্জ বুঝতে সাহায্য করে।

```python
import statistics

data = [1, 20, 30, 40, 50, 60, 70, 80, 90, 100]

# ডেটাকে ৪ ভাগে ভাগ করা (২৫%, ৫০%, ৭৫% পজিশনের ভ্যালু দিবে)
quartiles = statistics.quantiles(data, n=4)

print("25th Percentile (Q1):", quartiles[0])
print("50th Percentile (Q2/Median):", quartiles[1])
print("75th Percentile (Q3):", quartiles[2])
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. নরমাল ডিস্ট্রিবিউশন (`NormalDist`)
পাইথন ৩.৮ এ মেশিন লার্নিং ইঞ্জিনিয়ারদের জন্য `statistics.NormalDist` ক্লাসটি যুক্ত করা হয়েছে। এটি গসিয়ান (Gaussian) বা নরমাল ডিস্ট্রিবিউশন নিয়ে কাজ করাকে পানির মতো সোজা করে দিয়েছে।

এটি মূলত Mean (μ) এবং Standard Deviation (σ) নিয়ে একটি ডিস্ট্রিবিউশন তৈরি করে।

```python
from statistics import NormalDist

# ১. নরমাল ডিস্ট্রিবিউশন তৈরি (Mean = 50, StDev = 10)
nd = NormalDist(mu=50, sigma=10)

# ২. CDF (Cumulative Distribution Function)
# ধরি, ক্লাসের গড় মার্ক ৫০ এবং StDev ১০। 
# প্রশ্ন: ক্লাসের কত পারসেন্ট স্টুডেন্ট ৬০ এর নিচে মার্কস পেয়েছে?
percentage = nd.cdf(60)
print(f"Percentage of students scoring below 60: {percentage * 100:.2f}%") # 84.13%
```

### ৭. দুটি ডিস্ট্রিবিউশনের মধ্যে মিল (Overlap)
ধরুন আপনার কাছে দুটি আলাদা ডেটাসেট আছে (যেমন: পুরুষ এবং নারীদের উচ্চতা)। আপনি জানতে চান এই দুটি ডিস্ট্রিবিউশন একে অপরের সাথে কতটা মিলে যায় (Overlap)।

```python
from statistics import NormalDist

# ஆண்கদের উচ্চতার ডিস্ট্রিবিউশন (Mean 175cm, StDev 7cm)
men_heights = NormalDist(mu=175, sigma=7)

# নারীদের উচ্চতার ডিস্ট্রিবিউশন (Mean 162cm, StDev 6cm)
women_heights = NormalDist(mu=162, sigma=6)

# ওভারল্যাপ বের করা (0.0 মানে কোনো মিল নেই, 1.0 মানে হুবহু এক)
overlap = men_heights.overlap(women_heights)

print(f"Overlap between Men and Women heights: {overlap * 100:.2f}%") 
# আউটপুট: 16.48%
```

### ৮. Z-Score (Z-স্কোর) বের করা
Z-Score মানে হলো—কোনো একটি ডেটা তার সেন্ট্রাল বা গড় (Mean) থেকে কতগুলো স্ট্যান্ডার্ড ডেভিয়েশন (Standard Deviation) দূরে আছে। এটি আউটলায়ার (Outlier) রিমুভ করার জন্য খুব জনপ্রিয় একটি অ্যালগরিদম।

```python
from statistics import NormalDist

# ক্লাসের মার্কসের ডিস্ট্রিবিউশন
nd = NormalDist(mu=70, sigma=15)

# রহিম পেয়েছে ৯৫ মার্ক। তার Z-Score কত?
z_score = nd.zscore(95)
print(f"Rahim's Z-Score: {z_score:.2f}") # 1.67 (অর্থাৎ সে গড় থেকে 1.67 StDev ওপরে আছে)

# সাধারণত Z-Score যদি 3 এর বেশি বা -3 এর কম হয়, তখন তাকে আউটলায়ার (Outlier) ধরা হয়!
```

### সারসংক্ষেপ (Conclusion)
বিশাল ডেটাবেস নিয়ে কাজ করার সময় `Pandas` এর `df.describe()` ব্যবহার করা হলেও, যখন আপনি কোনো লাইটওয়েট মাইক্রোসার্ভিস বা API বানাবেন যেখানে থার্ড-পার্টি লাইব্রেরির সাইজ কমানো জরুরি, তখন ডেটার স্ট্যাটিস্টিক্যাল হিসাব করার জন্য পাইথনের বিল্ট-ইন **`statistics`** মডিউল এবং বিশেষ করে **`NormalDist`** ক্লাসটি ম্যাজিকের মতো কাজ করে!
