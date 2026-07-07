## ৮. ফাইল হ্যান্ডলিং (File Handling)

ফাইল রিড (Read) এবং রাইট (Write) করার পদ্ধতি।
* `r`: Read (পড়া) - ডিফল্ট
* `w`: Write (লেখা) - আগের ডাটা মুছে নতুন করে লেখে।
* `a`: Append (যুক্ত করা) - আগের ডাটার শেষে নতুন ডাটা যুক্ত করে।

### ফাইলের সাধারণ কাজ:
```python
# রাইট করা
file = open("data.txt", "w")
file.write("Hello, this is line 1.\n")
file.close()

# রিড করা
file = open("data.txt", "r")
print(file.read())
file.close()
```

### Context Manager (`with` statement):
এটি ফাইল হ্যান্ডলিং এর সবচেয়ে ভালো পদ্ধতি, কারণ ফাইল কাজ শেষে স্বয়ংক্রিয়ভাবে ক্লোজ হয়ে যায়।
```python
with open("data.txt", "a") as file:
    file.write("This is line 2.\n")

with open("data.txt", "r") as file:
    for line in file:
        print(line.strip()) # strip() নতুন লাইনের স্পেস দূর করে
```

---