## ৪. কন্ট্রোল ফ্লো (Conditionals and Loops)

প্রোগ্রামের সিদ্ধান্ত নেওয়া এবং একই কাজ বারবার করার জন্য কন্ট্রোল ফ্লো ব্যবহার করা হয়। পাইথনে কোড ব্লক বোঝানোর জন্য **ইন্ডেন্টেশন (Indentation বা ফাঁকা স্থান)** ব্যবহার করা হয়।

### If-Elif-Else (শর্ত সাপেক্ষে সিদ্ধান্ত):
```python
marks = 75

if marks >= 80:
    print("Grade: A+")
elif marks >= 70:
    print("Grade: A")
elif marks >= 60:
    print("Grade: A-")
else:
    print("Grade: Fail")
```

### For Loop (লুপ):
যখন আপনি জানেন লুপটি কতবার চলবে বা একটি লিস্ট/স্ট্রিং এর প্রতিটি আইটেম ধরে কাজ করতে চান।
`range(start, stop, step)` ফাংশন ব্যবহার করে নির্দিষ্ট সংখ্যার সিকুয়েন্স তৈরি করা যায়।

```python
# ১ থেকে ৫ পর্যন্ত প্রিন্ট করা (stop ভ্যালুর আগের সংখ্যা পর্যন্ত চলে)
for i in range(1, 6):
    print(i)

# লিস্টের ওপর দিয়ে লুপ চালানো
fruits = ["apple", "banana", "mango"]
for fruit in fruits:
    print("I love", fruit)
```

### While Loop:
ততক্ষণ পর্যন্ত কাজ করবে যতক্ষণ শর্তটি `True` থাকে।

```python
count = 1
while count <= 5:
    print(count)
    count += 1  # count = count + 1 এর শর্টকাট
```

### Loop Control Statements:
* `break`: লুপ থেকে সম্পূর্ণ বের হয়ে যেতে।
* `continue`: বর্তমান ইটারেশন স্কিপ করে পরের ইটারেশনে যেতে।

---