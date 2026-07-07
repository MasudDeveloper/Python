# JSON (Zero to Hero) কমপ্লিট গাইড

ইন্টারনেটে যত ডেটা আদান-প্রদান হয় (যেমন: Facebook বা YouTube এর API), তার ৯৯% ডেটাই একটি নির্দিষ্ট ফরমেটে পাঠানো হয়, যার নাম **JSON** (JavaScript Object Notation)। 

আপনি পাইথনে ডেটা পাঠাচ্ছেন, আর রিসিভার হয়তো জাভা (Java) বা পিএইচপি (PHP) ব্যবহার করছে। যেহেতু একেক ল্যাঙ্গুয়েজের ডেটা স্ট্রাকচার একেক রকম, তাই সবাই যেন ডেটা বুঝতে পারে, সেজন্য একটি ইউনিভার্সাল বা কমন ভাষা দরকার। JSON হলো সেই ইউনিভার্সাল ভাষা! 

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Custom Object Serialization (পাইথনের ক্লাসকে JSON বানানো) পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. পাইথন ডিকশনারি বনাম JSON
JSON দেখতে হুবহু পাইথন ডিকশনারির (Dictionary) মতোই। কিন্তু একটি বিশাল পার্থক্য আছে—**JSON মূলত একটি স্ট্রিং (String) বা টেক্সট!**
* ডিকশনারিতে আমরা সিঙ্গল কোট (`'`) বা ডাবল কোট (`"`) যেকোনোটি ব্যবহার করতে পারি।
* কিন্তু JSON এ সব সময় ডাবল কোট (`"`) ব্যবহার করা বাধ্যতামূলক এবং এর ভেতরে `True`/`False` এর বদলে ছোট হাতের `true`/`false` লিখতে হয়।

### ২. `json.loads()` (JSON স্ট্রিং থেকে পাইথন ডিকশনারি)
ইন্টারনেট থেকে ডেটা আনলে সেটি JSON স্ট্রিং হিসেবে আসে। একে পাইথনে ব্যবহার করার জন্য ডিকশনারিতে কনভার্ট করতে হয়। (এখানে `s` মানে হলো `string`)

```python
import json

# এটি একটি JSON স্ট্রিং (খেয়াল করুন, পুরোটা একটি স্ট্রিংয়ের ভেতরে)
json_string = '{"name": "Rahim", "age": 25, "is_student": true}'

print(type(json_string)) # <class 'str'>

# স্ট্রিং থেকে পাইথন ডিকশনারিতে রূপান্তর (Load String)
data = json.loads(json_string)

print(type(data)) # <class 'dict'>
print("Name:", data["name"]) # Rahim
print("Is Student?", data["is_student"]) # True (পাইথনের বড় হাতের True হয়ে গেছে)
```

### ৩. `json.dumps()` (পাইথন ডিকশনারি থেকে JSON স্ট্রিং)
যখন আমরা পাইথন থেকে ডেটা ইন্টারনেট বা অন্য কোনো সার্ভারে পাঠাই, তখন ডিকশনারিকে JSON স্ট্রিং বানিয়ে পাঠাতে হয়। (এখানে `s` মানে হলো `string`)

```python
import json

# এটি একটি সাধারণ পাইথন ডিকশনারি
python_dict = {
    "name": "Karim",
    "age": 30,
    "is_student": False,
    "skills": ["Python", "Django"]
}

# ডিকশনারি থেকে JSON স্ট্রিংয়ে রূপান্তর (Dump String)
json_string = json.dumps(python_dict)

print(type(json_string)) # <class 'str'>
# আউটপুট: {"name": "Karim", "age": 30, "is_student": false, "skills": ["Python", "Django"]}
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. `json.load()` এবং `json.dump()` (ফাইল নিয়ে কাজ করা)
স্ট্রিংয়ের বদলে যদি আমরা সরাসরি কম্পিউটারে কোনো `.json` ফাইলে ডেটা সেভ করতে চাই বা ফাইল থেকে রিড করতে চাই, তখন শেষের `s` টা বাদ দিয়ে শুধু `load()` এবং `dump()` ব্যবহার করতে হয়।

**ফাইলে ডেটা সেভ করা (`dump`):**
```python
import json

data = {"name": "Alice", "country": "UK"}

# data.json নামে একটি ফাইলে ডেটা রাইট (w) করা
with open("data.json", "w") as file:
    json.dump(data, file)
```

**ফাইল থেকে ডেটা রিড করা (`load`):**
```python
import json

# data.json ফাইল থেকে ডেটা রিড (r) করা
with open("data.json", "r") as file:
    loaded_data = json.load(file)

print(loaded_data["name"]) # Alice
```

### ৫. JSON কে সুন্দর করা (Pretty Print / Beautify)
অনেক বড় JSON ডেটা এক লাইনে প্রিন্ট হলে তা পড়া খুব কঠিন। একে সুন্দর করে সাজানোর জন্য `indent` ব্যবহার করা হয়।

```python
import json

user = {
    "name": "John",
    "age": 25,
    "address": {"city": "New York", "zip": 10001}
}

# indent=4 দিলে ৪টি স্পেস দিয়ে সুন্দর করে সাজিয়ে দিবে
pretty_json = json.dumps(user, indent=4)
print(pretty_json)
```
*আউটপুট:*
```json
{
    "name": "John",
    "age": 25,
    "address": {
        "city": "New York",
        "zip": 10001
    }
}
```

### ৬. JSON সাইজ ছোট করা (Minify / Separators)
ইন্টারনেটে ব্যান্ডউইথ বাঁচানোর জন্য ডেটা পাঠানোর সময় আমরা চাই সব স্পেস মুছে ফেলতে।
```python
# separators=(item_separator, key_separator)
# ডিফল্টভাবে পাইথন কমা (,) এবং কোলনের (:) পর স্পেস দেয়। আমরা সেটি সরিয়ে দিচ্ছি।
minified_json = json.dumps(user, separators=(',', ':'))
print(minified_json)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. Custom Object Serialization (পাইথনের ক্লাসকে JSON বানানো)
পাইথনের বেসিক ডেটা টাইপ (list, dict, int, str) কে JSON এ রূপান্তর করা যায়। কিন্তু আপনি যদি নিজের বানানো কোনো ক্লাসের অবজেক্টকে JSON বানাতে যান, তবে `TypeError` খাবেন! কারণ JSON জানে না আপনার ক্লাসকে কীভাবে কনভার্ট করতে হবে।

```python
import json

class Student:
    def __init__(self, name, marks):
        self.name = name
        self.marks = marks

s1 = Student("Rahim", 80)

# json.dumps(s1) # এটি TypeError দিবে!
```
**এর সমাধান (Custom Encoder):**
আমাদেরকে একটি ফাংশন বা ক্লাস লিখে পাইথনকে বলে দিতে হবে যে অবজেক্টটিকে কীভাবে ডিকশনারি বানাতে হবে।

```python
import json

class Student:
    def __init__(self, name, marks):
        self.name = name
        self.marks = marks

# কাস্টম এনকোডার ফাংশন
def custom_student_encoder(obj):
    if isinstance(obj, Student):
        return {"name": obj.name, "marks": obj.marks, "__type__": "Student"}
    # যদি অন্য কিছু হয়, তবে ডিফল্টভাবে এরর দিবে
    raise TypeError("Object is not JSON serializable")

s1 = Student("Rahim", 80)

# default আর্গুমেন্টে আমাদের ফাংশনটি দিয়ে দিবো
json_string = json.dumps(s1, default=custom_student_encoder)
print("Encoded JSON:", json_string)
# আউটপুট: {"name": "Rahim", "marks": 80, "__type__": "Student"}
```

### ৮. Custom Object Deserialization (JSON থেকে আবার ক্লাস বানানো)
এবার আমরা সেই JSON স্ট্রিংটিকে আবার আমাদের `Student` ক্লাসের অবজেক্টে ফিরিয়ে আনবো! এর জন্য `object_hook` ব্যবহার করা হয়।

```python
import json

# আগের JSON স্ট্রিংটি
json_string = '{"name": "Rahim", "marks": 80, "__type__": "Student"}'

# কাস্টম ডিকোডার ফাংশন
def custom_student_decoder(dct):
    if "__type__" in dct and dct["__type__"] == "Student":
        # আবার Student অবজেক্ট তৈরি করা
        return Student(dct["name"], dct["marks"])
    return dct

# object_hook এ আমাদের ফাংশনটি দিয়ে দিবো
decoded_obj = json.loads(json_string, object_hook=custom_student_decoder)

print("Decoded Type:", type(decoded_obj)) # <class '__main__.Student'>
print("Student Name:", decoded_obj.name)  # Rahim
```

### ৯. Datetime কে JSON এ রূপান্তর করা
JSON বাই-ডিফল্ট `datetime` অবজেক্টকে সাপোর্ট করে না।

```python
import json
from datetime import datetime

now = datetime.now()

# কাস্টম এনকোডার দিয়ে ডেটটাইমকে স্ট্রিং বানানো
def datetime_encoder(obj):
    if isinstance(obj, datetime):
        return obj.isoformat() # স্ট্রিং (ISO 8601 Format)
    raise TypeError

json_time = json.dumps({"current_time": now}, default=datetime_encoder)
print(json_time)
# আউটপুট: {"current_time": "2026-07-05T21:20:30.123456"}
```

### সারসংক্ষেপ (Conclusion)
ওয়েব ডেভেলপমেন্ট (Django/FastAPI) বা API নিয়ে কাজ করতে গেলে **`json`** মডিউলটি প্রতিনিয়ত ব্যবহার করতে হয়। বিশেষ করে `loads()` (Load String) এবং `dumps()` (Dump String) ফাংশন দুটি আপনার মুখস্থ থাকতে হবে। আর বড় কোনো প্রজেক্টে কাস্টম অবজেক্ট পাঠানোর জন্য `default` এনকোডার লেখার কৌশলটি একজন প্রো-কোডারের অন্যতম প্রধান হাতিয়ার!
