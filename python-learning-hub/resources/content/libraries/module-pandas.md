# Pandas (Zero to Hero) কমপ্লিট গাইড

ডেটা সায়েন্স (Data Science) বা ডেটা অ্যানালাইসিসের জগতে সবচেয়ে বিখ্যাত এবং বহুল ব্যবহৃত লাইব্রেরির নাম হলো **Pandas (প্যান্ডাস)**। 

সহজ কথায় বলতে গেলে, এটি হলো **"পাইথনের এক্সেল (Microsoft Excel)"**! এক্সেলে যে কাজগুলো করতে আপনার মাউস দিয়ে বারবার ক্লিক করে অনেক সময় নষ্ট করতে হয়, প্যান্ডাস দিয়ে আপনি ১ লাইনের কোড লিখে ১০ লক্ষ ডেটার ওপরেও সেই কাজ চোখের পলকে করে ফেলতে পারবেন!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ডেটা লোডিং থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Grouping, SQL-স্টাইল Joins এবং Pivot Tables পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং ডেটাফ্রেম (DataFrame) তৈরি
প্রথমে প্যান্ডাস ইনস্টল করে নিন:
```bash
pip install pandas
```
প্যান্ডাসে মূলত দুটি ডেটা স্ট্রাকচার থাকে: **Series** (শুধু একটি কলাম) এবং **DataFrame** (একাধিক কলাম বা টেবিল)।

```python
import pandas as pd

# একটি সাধারণ পাইথন ডিকশনারি
data = {
    'Name': ['Rahim', 'Karim', 'Jamal', 'Salam'],
    'Age': [25, 30, 22, 35],
    'City': ['Dhaka', 'Chittagong', 'Sylhet', 'Dhaka']
}

# ডিকশনারিকে প্যান্ডাস টেবিলে (DataFrame) রূপান্তর করা
df = pd.DataFrame(data)

print(df)
```
*আউটপুট:*
```text
    Name  Age        City
0  Rahim   25       Dhaka
1  Karim   30  Chittagong
2  Jamal   22      Sylhet
3  Salam   35       Dhaka
```

### ২. ফাইল থেকে ডেটা পড়া এবং সেভ করা (Read/Write CSV)
রিয়েল-লাইফে আমরা হাতে লিখে ডেটা বানাই না, আমরা CSV বা Excel ফাইল থেকে ডেটা লোড করি।

```python
import pandas as pd

# ১. CSV ফাইল থেকে ডেটা রিড করা
# df = pd.read_csv('employees.csv')

# ২. Excel ফাইল থেকে ডেটা রিড করা
# df = pd.read_excel('data.xlsx')

# ৩. ডেটা প্রসেস করার পর আবার নতুন ফাইলে সেভ (Write) করা
# df.to_csv('cleaned_data.csv', index=False) # index=False দিলে বামপাশের 0,1,2 সেভ হবে না
```

### ৩. ডেটা সম্পর্কে বেসিক ধারণা নেওয়া
ডেটা লোড করার পর সবার আগে দেখতে হয় ডেটাটি কেমন।

```python
# প্রথম ৫ লাইনের ডেটা দেখা (ডেটাফ্রেম অনেক বড় হলে এটি খুব কাজের)
print(df.head()) 

# শেষ ৩ লাইনের ডেটা দেখা
print(df.tail(3))

# ডেটার সামারি দেখা (কয়টি কলাম, ডেটা টাইপ কী, কোনো মিসিং ভ্যালু আছে কি না)
print(df.info())

# গাণিতিক কলামগুলোর (যেমন: Age, Salary) গড়, সর্বোচ্চ, সর্বনিম্ন ভ্যালু দেখা
print(df.describe())
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. নির্দিষ্ট কলাম এবং রো (Row) সিলেক্ট করা
পুরো টেবিল থেকে শুধু দরকারি ডেটাগুলো বের করে আনা।

```python
# ১. শুধু একটি নির্দিষ্ট কলাম দেখা
print(df['Name'])

# ২. একাধিক কলাম একসাথে দেখা (লিস্টের ভেতর লিস্ট)
print(df[['Name', 'City']])

# ৩. রো (Row) বের করা (iloc - Index Location)
# শুধু প্রথম লাইনের ডেটা (index 0)
print(df.iloc[0]) 

# প্রথম ২টি লাইনের ডেটা (slicing)
print(df.iloc[0:2])
```

### ৫. ডেটা ফিল্টার করা (Conditional Filtering)
ধরি, আমরা শুধু তাদের ডেটাই চাই যাদের বয়স ২৫ এর বেশি।

```python
import pandas as pd

# যাদের বয়স ২৫ এর বেশি
adults = df[df['Age'] > 25]
print(adults)

# একাধিক কন্ডিশন (বয়স ২৫ এর বেশি এবং ঢাকায় থাকে)
# লক্ষ্য করুন: একাধিক কন্ডিশনের ক্ষেত্রে () ব্যবহার করতে হয় এবং & (and) বা | (or) দিতে হয়
dhaka_adults = df[(df['Age'] > 25) & (df['City'] == 'Dhaka')]
print(dhaka_adults)
```

### ৬. মিসিং ভ্যালু (Missing Data) হ্যান্ডেল করা
রিয়েল-ওয়ার্ল্ড ডেটাতে অনেক ফাঁকা ঘর (NaN বা Null) থাকে। এগুলো ঠিক না করলে মেশিন লার্নিং মডেল কাজ করবে না।

```python
import pandas as pd
import numpy as np

# একটি ডামি ডেটা যেখানে কিছু ফাঁকা (NaN) ভ্যালু আছে
data = {'Name': ['Alice', 'Bob', 'Charlie', 'David'],
        'Age': [25, np.nan, 30, 22],
        'Score': [85, 90, np.nan, 88]}
df = pd.DataFrame(data)

# ১. মিসিং ভ্যালু চেক করা
print(df.isnull().sum())

# ২. যেসব রো-তে ফাঁকা ভ্যালু আছে, সেগুলো পুরোপুরি ডিলিট করে দেওয়া
df_clean = df.dropna()

# ৩. ফাঁকা ভ্যালু ডিলিট না করে ডিফল্ট কোনো ভ্যালু (যেমন 0) বসিয়ে দেওয়া
df_filled = df.fillna(0)

# ৪. (প্রো লেভেল) ফাঁকা বয়সের জায়গায় সবার গড় (Mean) বয়স বসিয়ে দেওয়া
df['Age'] = df['Age'].fillna(df['Age'].mean())
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. গ্রুপ বাই (GroupBy) এবং এগ্রিগেশন
SQL এর `GROUP BY` এর মতো, প্যান্ডাসেও আপনি ডেটাকে ক্যাটাগরি অনুযায়ী গ্রুপ করে হিসাব-নিকাশ করতে পারেন।

```python
import pandas as pd

data = {
    'Department': ['IT', 'HR', 'IT', 'Finance', 'HR', 'IT'],
    'Salary': [50000, 40000, 55000, 60000, 42000, 52000]
}
df = pd.DataFrame(data)

# ১. কোন ডিপার্টমেন্টে মোট কত স্যালারি দেওয়া হয়?
total_salary = df.groupby('Department')['Salary'].sum()
print("Total Salary:\n", total_salary)

# ২. কোন ডিপার্টমেন্টের গড় (Average) স্যালারি কত?
avg_salary = df.groupby('Department')['Salary'].mean()
print("Average Salary:\n", avg_salary)
```

### ৮. SQL স্টাইলে ডেটাফ্রেম জোড়া লাগানো (Merge / Join)
আপনার কাছে যদি দুটি আলাদা ফাইল বা টেবিল থাকে (যেমন: কাস্টমার লিস্ট এবং অর্ডার লিস্ট), তবে কমন আইডির ওপর ভিত্তি করে তাদের একসাথে করা যায়।

```python
import pandas as pd

# কাস্টমার ডেটা
customers = pd.DataFrame({
    'ID': [1, 2, 3],
    'Name': ['Rahim', 'Karim', 'Jamal']
})

# অর্ডার ডেটা (যেখানে কাস্টমারের ID আছে)
orders = pd.DataFrame({
    'OrderID': [101, 102, 103],
    'CustomerID': [1, 1, 3],
    'Amount': [500, 1200, 300]
})

# দুটি টেবিলকে Merge বা Join করা (CustomerID এবং ID এর ওপর ভিত্তি করে)
merged_df = pd.merge(orders, customers, left_on='CustomerID', right_on='ID', how='inner')

print(merged_df)
```

### ৯. কাস্টম ফাংশন অ্যাপ্লাই করা (`apply`)
কলামের প্রতিটি ডেটার ওপরে যদি আপনি নিজের বানানো কোনো জটিল লজিক রান করাতে চান।

```python
import pandas as pd

df = pd.DataFrame({'Price': [100, 200, 300]})

# একটি ফাংশন যা দামের ওপর ১৫% ভ্যাট যোগ করবে
def add_vat(price):
    return price + (price * 0.15)

# 'Price' কলামের সব ডেটার ওপরে ফাংশনটি রান করানো (Vectorized Apporach)
df['Price_with_VAT'] = df['Price'].apply(add_vat)

print(df)
```

### ১০. পিভট টেবিল (Pivot Tables)
এক্সেলের সবচেয়ে পাওয়ারফুল ফিচার হলো পিভট টেবিল। প্যান্ডাসেও মাত্র ১ লাইনেই এটি করা যায়!

```python
import pandas as pd

data = {
    'Date': ['01-Jan', '01-Jan', '02-Jan', '02-Jan'],
    'City': ['Dhaka', 'Sylhet', 'Dhaka', 'Sylhet'],
    'Sales': [100, 150, 200, 120]
}
df = pd.DataFrame(data)

# পিভট টেবিল তৈরি করা (রো তে থাকবে Date, কলামে থাকবে City, আর ভ্যালুতে থাকবে Sales)
pivot = pd.pivot_table(df, values='Sales', index='Date', columns='City')

print(pivot)
```

### সারসংক্ষেপ (Conclusion)
ডেটা ক্লিনিং (Data Cleaning), ডেটা ম্যানিপুলেশন বা মেশিন লার্নিংয়ের জন্য ডেটা রেডি করার ক্ষেত্রে **Pandas** এর কোনো বিকল্প নেই! আপনি যদি শুধু `groupby()`, `merge()`, এবং `fillna()` এর মতো ফাংশনগুলো ভালোভাবে আয়ত্ত করতে পারেন, তবে আপনি যেকোনো কোম্পানির ডেটা অ্যানালিস্ট (Data Analyst) হিসেবে কাজ শুরু করতে পারবেন!
