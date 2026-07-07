# Urllib (Zero to Hero) কমপ্লিট গাইড

ইন্টারনেট থেকে ডেটা আনার জন্য থার্ড-পার্টি লাইব্রেরি `requests` সবচেয়ে জনপ্রিয় হলেও, অনেক সময় কর্পোরেট বা সিকিউরড সার্ভারে পাইথনের বিল্ট-ইন প্যাকেজ ছাড়া বাইরের কোনো প্যাকেজ ইনস্টল করার পারমিশন থাকে না।

সেইসব ক্ষেত্রে ইন্টারনেট বা নেটওয়ার্কিংয়ের কাজ করার জন্য পাইথনের নিজস্ব বিল্ট-ইন লাইব্রেরি হলো **`urllib`**। এটি `requests` এর চেয়ে ব্যবহার করা একটু কঠিন এবং এর কোড তুলনামূলক বড়, কিন্তু এটি পাইথনের কোরের সাথে একদম মিশে আছে।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Error Handling এবং URL Parsing পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. বেসিক GET রিকোয়েস্ট (`urlopen`)
`urllib` এর মূল কাজগুলো `urllib.request` মডিউলের ভেতরে থাকে।

```python
import urllib.request

url = "https://api.github.com"

# ১. ওয়েবসাইটে রিকোয়েস্ট পাঠানো এবং রেসপন্স ওপেন করা
response = urllib.request.urlopen(url)

# ২. স্ট্যাটাস কোড দেখা
print("Status Code:", response.status) # 200

# ৩. ডেটা রিড করা (urllib বাইট বা Bytes রিটার্ন করে, তাই একে ডিকোড করতে হয়!)
html_bytes = response.read()
html_text = html_bytes.decode('utf-8')

print("Response Text (First 100 chars):", html_text[:100])
```
*(লক্ষ্য করুন: `requests.get().text` এর কাজ করার জন্য `urllib` এ আগে `read()` তারপর `decode('utf-8')` করতে হয়!)*

### ২. ইন্টারনেট থেকে সরাসরি ফাইল ডাউনলোড (`urlretrieve`)
আপনি যদি ইন্টারনেট থেকে কোনো ছবি বা পিডিএফ সরাসরি কম্পিউটারের হার্ডডিস্কে সেভ করতে চান, তবে `urlretrieve` হলো সবচেয়ে সহজ উপায়! এটি এক লাইনেই কাজটি করে দেয়।

```python
import urllib.request

image_url = "https://www.python.org/static/img/python-logo.png"

# ফাইলটি সরাসরি 'python_logo.png' নামে সেভ হয়ে যাবে!
urllib.request.urlretrieve(image_url, "python_logo.png")

print("Image downloaded successfully!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. URL থেকে ডেটা আলাদা করা (`urllib.parse`)
অনেক সময় ওয়েবসাইটের বিশাল একটি লিংক থেকে আমাদের শুধু ডোমেইন নাম, বা পাথ বের করার দরকার হয়।

```python
from urllib.parse import urlparse

url = "https://www.example.com:8080/search/products?q=python&sort=new#top"

# URL টিকে পার্স বা ব্যবচ্ছেদ করা
parsed_url = urlparse(url)

print("Scheme (Protocol):", parsed_url.scheme)   # https
print("Domain (Netloc):", parsed_url.netloc)     # www.example.com:8080
print("Path:", parsed_url.path)                  # /search/products
print("Query (Params):", parsed_url.query)       # q=python&sort=new
print("Fragment (Hash):", parsed_url.fragment)   # top
```

### ৪. Query Parameters তৈরি করা (`urlencode`)
API তে রিকোয়েস্ট পাঠানোর সময় স্পেস বা স্পেশাল ক্যারেক্টারকে URL এর ভাষায় কনভার্ট করা লাগে (যাকে URL Encoding বলে)। যেমন: স্পেস হয়ে যায় `%20` বা `+`।

```python
from urllib.parse import urlencode

# আমাদের সার্চের ডেটা
search_data = {
    "q": "python tutorial",
    "category": "programming",
    "page": 1
}

# ডিকশনারিকে URL ফরমেটে কনভার্ট করা
encoded_query = urlencode(search_data)

print("Encoded Query:", encoded_query)
# আউটপুট: q=python+tutorial&category=programming&page=1

# এবার এটিকে মূল লিংকের সাথে জোড়া লাগানো যায়
final_url = "https://example.com/search?" + encoded_query
print("Final URL:", final_url)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. কাস্টম হেডার পাঠানো (`Request` অবজেক্ট)
সার্ভারকে বোকা বানিয়ে নিজেকে ব্রাউজার হিসেবে পরিচয় দেওয়ার জন্য (User-Agent) আমাদেরকে ফেক হেডার পাঠাতে হয়। `urlopen()` সরাসরি হেডার নেয় না, তাই আগে একটি `Request` অবজেক্ট তৈরি করতে হয়।

```python
import urllib.request

url = "https://httpbin.org/headers"

# ১. আমাদের ফেক হেডার
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Accept": "application/json"
}

# ২. URL এবং Header দিয়ে একটি Request অবজেক্ট তৈরি করা
req = urllib.request.Request(url, headers=headers)

# ৩. এবার সেই অবজেক্টটিকে urlopen এর ভেতর পাঠানো
response = urllib.request.urlopen(req)

data = response.read().decode('utf-8')
print("Server Response:\n", data)
```

### ৬. এরর হ্যান্ডলিং (HTTPError এবং URLError)
সার্ভার যদি 404 (Not Found) বা 403 (Forbidden) এরর দেয়, তবে `urllib` পাইথনকে ক্র্যাশ করিয়ে দেয়। এই ক্র্যাশ ঠেকানোর জন্য `urllib.error` মডিউল ব্যবহার করতে হয়।

```python
import urllib.request
import urllib.error

url = "https://httpbin.org/status/404" # ইচ্ছাকৃতভাবে একটি ভুল লিংক

try:
    response = urllib.request.urlopen(url)
    print("Success!")
    
except urllib.error.HTTPError as e:
    # সার্ভার পর্যন্ত রিকোয়েস্ট গেছে, কিন্তু সার্ভার এরর দিয়েছে (যেমন: 404, 500)
    print(f"HTTP Error Occurred: Status {e.code}, Reason: {e.reason}")
    
except urllib.error.URLError as e:
    # সার্ভার পর্যন্ত রিকোয়েস্ট যেতেই পারেনি! (যেমন: ইন্টারনেট নেই, বা ভুল ডোমেইন)
    print(f"Network Error Occurred: {e.reason}")
    
except Exception as e:
    # অন্য কোনো অজানা এরর
    print("Something went wrong:", e)
```

### সারসংক্ষেপ (Conclusion)
বর্তমানে প্রোডাকশন লেভেলের কাজে থার্ড-পার্টি লাইব্রেরি `requests` বা `httpx` এর রাজত্ব চললেও, পাইথনের ইন্টার্নাল আর্কিটেকচার কীভাবে কাজ করে তা বোঝার জন্য **`urllib`** শেখা জরুরি। বিশেষ করে এর **`urllib.parse`** মডিউলটি URL নিয়ে কাজ করার জন্য এখনও পাইথন ডেভেলপারদের প্রধান পছন্দ!
