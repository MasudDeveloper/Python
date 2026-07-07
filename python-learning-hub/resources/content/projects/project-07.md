## ৬. ডাটা স্ট্রাকচার (Data Structures)

পাইথনে প্রধান ৪টি বিল্ট-ইন ডাটা স্ট্রাকচার আছে।

### ১. List (লিস্ট):
লিস্ট হলো একাধিক আইটেমের কালেকশন। এটি পরিবর্তনযোগ্য (Mutable), অর্ডার মেইনটেইন করে এবং ডুপ্লিকেট ভ্যালু রাখতে পারে।

```python
colors = ["red", "green", "blue"]

# ইনডেক্সিং (জিরো-বেসড)
print(colors[0])      # red
print(colors[-1])     # blue (শেষের আইটেম)

# লিস্ট মেথড
colors.append("yellow")     # শেষে যুক্ত করে
colors.insert(1, "black")   # নির্দিষ্ট ইনডেক্সে যুক্ত করে
colors.remove("green")      # নির্দিষ্ট আইটেম মুছে ফেলে
last_item = colors.pop()    # শেষের আইটেম রিমুভ করে রিটার্ন করে

# List Comprehension (লিস্ট তৈরির শর্টকাট)
squares = [x**2 for x in range(1, 6)]
print(squares) # [1, 4, 9, 16, 25]
```

### ২. Tuple (টাপল):
এটি লিস্টের মতোই, তবে একবার তৈরি করলে আর পরিবর্তন করা যায় না (Immutable)।
```python
coordinates = (10.0, 20.0, 30.0)
print(coordinates[1]) # 20.0
# coordinates[0] = 15.0 # এটি Error দিবে!
```

### ৩. Dictionary (ডিকশনারি):
এটি কী-ভ্যালু (Key-Value) পেয়ার হিসেবে ডাটা রাখে।
```python
student = {
    "name": "Rahim",
    "age": 20,
    "major": "CSE"
}

print(student["name"])     # Rahim
student["cgpa"] = 3.8      # নতুন কী-ভ্যালু যুক্ত করা
student["age"] = 21        # ভ্যালু আপডেট করা

# লুপ চালানো
for key, value in student.items():
    print(f"{key}: {value}")
```

### ৪. Set (সেট):
সেট হলো ডুপ্লিকেট-বিহীন, আন-অর্ডারড ডাটা কালেকশন। গণিতের সেটের মতো কাজ করে।
```python
set_a = {1, 2, 3, 4}
set_b = {3, 4, 5, 6}

print(set_a.union(set_b))        # {1, 2, 3, 4, 5, 6}
print(set_a.intersection(set_b)) # {3, 4}
```

---