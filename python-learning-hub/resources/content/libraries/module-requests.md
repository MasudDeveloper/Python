# Requests (Zero to Hero) কমপ্লিট গাইড

ইন্টারনেটে যত ধরনের কাজ হয় (যেমন: ডেটা স্ক্র্যাপ করা, API থেকে ডেটা আনা, বা অন্য ওয়েবসাইটে ডেটা সেন্ড করা)—তার সবই হয় HTTP রিকোয়েস্টের মাধ্যমে। পাইথনের নিজস্ব `urllib` লাইব্রেরি থাকলেও, সেটি অনেক জটিল। 

এই জন্যই তৈরি করা হয়েছে **`requests`** লাইব্রেরি। এর মূলমন্ত্রই হলো **"HTTP for Humans"** (মানুষের ব্যবহারের জন্য)। এটি পাইথনের ইতিহাসের সবচেয়ে জনপ্রিয় থার্ড-পার্টি প্যাকেজ।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Session Management এবং বড় ফাইল স্ট্রিমিং পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক GET রিকোয়েস্ট
প্রথমে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install requests
```

যখন আমরা কোনো ওয়েবসাইটে ভিজিট করি বা API থেকে ডেটা জানতে চাই, তখন আমরা `GET` রিকোয়েস্ট করি।
```python
import requests

# ১. API থেকে ডেটা আনার জন্য রিকোয়েস্ট পাঠানো
response = requests.get("https://api.github.com/users/github")

# ২. স্ট্যাটাস কোড চেক করা (200 মানে সব ঠিক আছে, 404 মানে Not Found)
print("Status Code:", response.status_code)

# ৩. রেসপন্সটি যদি JSON হয়, তবে সরাসরি ডিকশনারিতে কনভার্ট করা যায়!
data = response.json()
print("Followers:", data["followers"])
```

### ২. Query Parameters পাঠানো
ধরুন আপনি একটি সার্চ API তে রিকোয়েস্ট পাঠাচ্ছেন। லிংकের শেষে `?q=python&limit=10` লেখা অনেক ঝামেলার। `requests` এটি অটোমেটিক করে দেয়।

```python
import requests

# API এর বেস URL
url = "https://httpbin.org/get"

# সার্চের প্যারামিটারগুলো একটি ডিকশনারিতে দেওয়া
search_params = {
    "q": "python tutorial",
    "limit": 10,
    "page": 2
}

# params আর্গুমেন্ট দিয়ে রিকোয়েস্ট পাঠানো (পাইথন নিজে URL বানিয়ে নিবে)
response = requests.get(url, params=search_params)

# ফাইনাল URL টি কী তৈরি হয়েছে?
print("Actual URL:", response.url) 
# আউটপুট: https://httpbin.org/get?q=python+tutorial&limit=10&page=2
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. POST রিকোয়েস্ট (সার্ভারে ডেটা পাঠানো)
যখন আপনি লগিন করেন বা রেজিস্ট্রেশন ফর্ম পূরণ করেন, তখন আপনি সার্ভারে ডেটা পাঠান। এর জন্য `POST` রিকোয়েস্ট ব্যবহার করা হয়।

```python
import requests

url = "https://httpbin.org/post"

# যে ডেটাগুলো সার্ভারে পাঠাবেন (ফর্ম ডেটা)
user_data = {
    "username": "admin123",
    "password": "supersecretpassword"
}

# data বা json আর্গুমেন্ট দিয়ে রিকোয়েস্ট পাঠানো
response = requests.post(url, json=user_data)

print("Server Reply:", response.json())
```

### ৪. Custom Headers এবং Authentication
আপনি যখন স্ক্র্যাপিং করেন, তখন অনেক ওয়েবসাইট পাইথনের রিকোয়েস্ট দেখলে ব্লক করে দেয়। কারণ পাইথনের ডিফল্ট User-Agent থাকে `python-requests/2.x.x`। সার্ভারকে ধোঁকা দেওয়ার জন্য আমাদেরকে ফেক (Fake) হেডার পাঠাতে হয়, যেন সার্ভার ভাবে আমরা আসল ব্রাউজার (Chrome/Firefox) থেকে আসছি!

```python
import requests

url = "https://httpbin.org/headers"

# ১. ফেক ব্রাউজারের হেডার এবং সিক্রেট টোকেন পাঠানো
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
    "Authorization": "Bearer my_secret_token_123"
}

response = requests.get(url, headers=headers)
print("Response:", response.text)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. Session অবজেক্ট (লগিন অবস্থা ধরে রাখা)
ধরি, আপনি একটি ওয়েবসাইটে লগিন করেছেন। এরপর যখন আপনি ওই সাইটের অন্য একটি পেজে (যেমন: ড্যাশবোর্ড) যাবেন, সার্ভার কীভাবে বুঝবে যে আপনিই লগিন করেছিলেন? সে কুকিজ (Cookies) চেক করে।

সাধারণ `requests.get()` কুকিজ মনে রাখে না। কিন্তু `requests.Session()` কুকিজ এবং কানেকশন মনে রাখে!

```python
import requests

# ১. একটি সেশন তৈরি করা
session = requests.Session()

# ২. লগিন লিংকে POST রিকোয়েস্ট পাঠানো
login_data = {"user": "admin", "pass": "1234"}
session.post("https://httpbin.org/post", data=login_data)

# ৩. এখন এই সেশনটি কুকিজ সেভ করে ফেলেছে!
# এরপর যতবারই এই সেশন দিয়ে রিকোয়েস্ট পাঠাবেন, সার্ভার ভাববে আপনি লগিন করা আছেন!
profile_response = session.get("https://httpbin.org/cookies")
print("Saved Cookies:", profile_response.json())
```

### ৬. Timeout হ্যান্ডলিং
কখনো কখনো সার্ভার স্লো থাকলে আপনার পাইথন কোড সেখানে অনন্তকাল আটকে (Hang) থাকতে পারে। এটি এড়ানোর জন্য সব সময় `timeout` সেট করে দিতে হয়।

```python
import requests

try:
    # যদি সার্ভার ৩ সেকেন্ডের মধ্যে রেসপন্স না করে, তবে পাইথন এরর দিবে
    response = requests.get("https://httpbin.org/delay/5", timeout=3)
    print("Success!")
except requests.exceptions.Timeout:
    print("Error: The server is taking too long! Timeout occurred.")
except requests.exceptions.RequestException as e:
    print("Error: A network issue occurred:", e)
```

### ৭. বিশাল বড় ফাইল ডাউনলোড করা (Streaming)
ধরুন আপনি একটি 1GB সাইজের মুভি বা ডেটাসেট ডাউনলোড করছেন। আপনি যদি সরাসরি `requests.get()` দেন, তবে পুরো 1GB ডেটা আপনার র‍্যামে (RAM) লোড হবে এবং কম্পিউটার ক্র্যাশ করবে! 

বড় ফাইল ডাউনলোডের জন্য **`stream=True`** ব্যবহার করে ডেটাকে ছোট ছোট ভাগে (Chunks) ভাগ করে হার্ডডিস্কে সেভ করতে হয়।

```python
import requests

url = "https://speed.hetzner.de/100MB.bin"

# stream=True মানে হলো ডেটা একসাথে ডাউনলোড হবে না, কানেকশন ওপেন থাকবে
with requests.get(url, stream=True) as response:
    # 200 OK কি না চেক করা
    response.raise_for_status()
    
    # ফাইলে সেভ করা শুরু (wb = write binary)
    with open("downloaded_file.bin", "wb") as file:
        # ১ মেগাবাইট (1024*1024) করে ডেটা আসবে এবং সেভ হবে! (র‍্যাম সেভ হবে)
        for chunk in response.iter_content(chunk_size=8192): 
            if chunk:
                file.write(chunk)
                
print("Large file downloaded successfully without crashing RAM!")
```

### সারসংক্ষেপ (Conclusion)
স্ক্র্যাপিং (Scraping), বটিং (Botting), অটোমেশন বা API টেস্টিং—পাইথনে ইন্টারনেটের যেকোনো কাজের মূল ভিত্তি হলো এই **`requests`** লাইব্রেরি। এর `Session` অবজেক্টের ব্যবহার এবং `headers` ম্যানিপুলেশনের টেকনিক জানা একজন হ্যাকার বা স্ক্র্যাপারের জন্য সবচেয়ে বড় শক্তি!
