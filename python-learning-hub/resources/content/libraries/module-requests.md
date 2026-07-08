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

### ৮. প্রো-লেভেল এন্টি-স্ক্র্যাপিং বাইপাস (Status Codes Solutions)
আপনি যখন কোনো রিয়েল-লাইফ ওয়েবসাইট (যেমন: Amazon, LinkedIn বা Cloudflare দেওয়া সাইট) স্ক্র্যাপ করতে যাবেন, তখন সার্ভার আপনার পাইথন স্ক্রিপ্টকে চিনে ফেলবে এবং বিভিন্ন এরর বা **Status Code** দিবে। এগুলোকে হেডার (Headers) দিয়ে কীভাবে বাইপাস করবেন তার বিস্তারিত গাইড:

#### ১. 403 Forbidden (সার্ভার আপনাকে ব্লক করেছে)
সবচেয়ে বেশি ফেস করা এরর! এর মানে হলো সার্ভার বুঝতে পেরেছে আপনি মানুষ নন, একটি বট।
**কীভাবে বাইপাস করবেন?**
ব্রাউজার যেসব হেডার পাঠায়, আপনাকেও হুবহু সেই হেডারগুলো পাঠাতে হবে। বিশেষ করে `User-Agent`, `Accept-Language` এবং `Referer`।

```python
headers = {
    # ১. ফেক ব্রাউজার ইউজার-এজেন্ট (সবচেয়ে গুরুত্বপূর্ণ)
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    
    # ২. আপনি কোন ভাষা বোঝেন (বটরা সাধারণত এটি পাঠায় না)
    "Accept-Language": "en-US,en;q=0.9,bn;q=0.8",
    
    # ৩. আপনি কোন সাইট থেকে এই সাইটে এসেছেন (সার্ভার ভাবে আপনি গুগল সার্চ করে এসেছেন!)
    "Referer": "https://www.google.com/",
    
    # ৪. মডার্ন ব্রাউজার সিকিউরিটি হেডার (Cloudflare বা ফায়ারওয়াল বাইপাস করার জন্য)
    "sec-ch-ua": '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
    "sec-fetch-dest": "document",
    "sec-fetch-mode": "navigate",
    "sec-fetch-site": "cross-site",
    "Upgrade-Insecure-Requests": "1"
}
response = requests.get("https://amazon.com", headers=headers)
```

#### ২. 406 Not Acceptable (ভুল ডেটা ফরমেট)
সার্ভার আপনার রিকোয়েস্ট পেয়েছে কিন্তু আপনি যেই ডেটা ফরমেট চাইছেন, সার্ভার তা দিতে রাজি না।
**কীভাবে বাইপাস করবেন?**
আপনার `Accept` হেডারে সার্ভারকে বলে দিতে হবে যে আপনি HTML, JSON বা Image যেকোনো ডেটাই রিসিভ করতে প্রস্তুত।
```python
headers = {
    "User-Agent": "Mozilla/5.0...",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8"
}
```

#### ৩. 429 Too Many Requests (আপনি অনেক স্প্যাম করছেন)
আপনি একটি ওয়েবসাইটে সেকেন্ডে ১০-২০টি রিকোয়েস্ট পাঠালে সার্ভার আপনাকে কিছুক্ষণের জন্য ব্যান করে দিবে।
**কীভাবে বাইপাস করবেন?**
এক্ষেত্রে হেডার দিয়ে কাজ হবে না। আপনাকে পাইথনের `time.sleep()` ব্যবহার করে রিকোয়েস্টের মাঝে গ্যাপ দিতে হবে এবং সার্ভারের দেওয়া `Retry-After` হেডারটি পড়তে হবে।
```python
import time
import requests

response = requests.get("https://api.example.com/data")

if response.status_code == 429:
    # সার্ভার যদি বলে দেয় কত সেকেন্ড পর আবার ট্রাই করতে হবে
    if "Retry-After" in response.headers:
        wait_time = int(response.headers["Retry-After"])
        print(f"Banned! Waiting for {wait_time} seconds...")
        time.sleep(wait_time)
    else:
        # না বললে নিজের মতো ৫-১০ সেকেন্ড গ্যাপ দিন
        time.sleep(10)
```

#### ৪. 401 Unauthorized (লগিন ছাড়া অ্যাক্সেস নিষেধ)
এই এররটি আসে যখন আপনি কোনো API বা সাইটে লগিন ছাড়া বা ভুল টোকেন (Token) দিয়ে ঢোকার চেষ্টা করেন।
**কীভাবে বাইপাস করবেন?**
ব্রাউজারের ইনস্পেক্ট (Inspect) টুল থেকে আপনার লগিন সেশনের `Cookie` বা `Authorization` টোকেনটি কপি করে হেডারে বসিয়ে দিন।
```python
headers = {
    "User-Agent": "Mozilla/5.0...",
    "Authorization": "Bearer YOUR_SECRET_API_TOKEN_HERE",
    
    # অথবা কুকি ব্যবহার করতে পারেন
    "Cookie": "session_id=1234567890abcdef; user_prefs=dark_mode"
}
```

*(বিঃদ্রঃ যদি কোনো সাইট অনেক হার্ডকোর সিকিউরিটি বা বট-প্রোটেকশন যেমন Cloudflare Turnstile, reCAPTCHA বা Datadome ব্যবহার করে, তবে শুধু `requests` মডিউলের হেডার দিয়ে তা বাইপাস করা প্রায় অসম্ভব। তখন আপনাকে `Selenium` বা `Playwright` এর মতো অটোমেশন টুল ব্যবহার করতে হবে।)*

---

### ৯. হ্যাকার ট্রিকস: ব্রাউজার থেকে ওয়েবসাইটের লুকানো API খুঁজে বের করা (API Hunting)
অনেক ওয়েবসাইট (যেমন: নিউজ পোর্টাল, ই-কমার্স) তাদের ডেটা সরাসরি HTML এ না দিয়ে ব্যাকএন্ড API থেকে কল করে নিয়ে আসে। আপনি চাইলে ব্রাউজারের Developer Tools ব্যবহার করে সেই লুকানো API খুঁজে বের করতে পারেন এবং `requests` দিয়ে ডেটা স্ক্র্যাপ করতে পারেন!

**কীভাবে খুঁজবেন?**
১. যেকোনো ওয়েবসাইটে গিয়ে `Right Click -> Inspect` (বা `F12`) চাপুন।
২. **Network** ট্যাবে যান এবং ওপর থেকে **Fetch/XHR** ফিল্টারটি সিলেক্ট করুন। 
৩. এবার পেজটি রিলোড (`F5`) দিন অথবা পেজের কোনো বাটনে ক্লিক করুন। 
৪. রিকোয়েস্টের লিস্টে `Type` কলামে `xhr` বা `fetch` লেখা থাকলে বুঝবেন সেগুলো API কল। 
৫. রিকোয়েস্টের নামের ওপর ক্লিক করলে ডানপাশের প্যানেলে **Headers** (কোথায় রিকোয়েস্ট গেছে) এবং **Preview** (কী JSON ডেটা এসেছে) দেখতে পাবেন।
৬. সেই URL টি কপি করে আপনার পাইথনের `requests.get()` এ বসিয়ে দিলেই আপনি ডেটা পেয়ে যাবেন!

*(বিঃদ্রঃ যদি পেজ রিলোড দেওয়ার পরও Fetch/XHR এ কোনো API না পান, তবে বুঝতে হবে ওয়েবসাইটটি Server-Side Rendering (SSR) ব্যবহার করে সরাসরি HTML এ ডেটা পাঠাচ্ছে। তখন আপনাকে `BeautifulSoup` ব্যবহার করে স্ক্র্যাপিং করতে হবে।)*

### ১০. রিয়েল-লাইফ প্রোজেক্ট: WordPress সাইট থেকে সব ডেটা একসাথে স্ক্র্যাপ করা (Pagination & Loop)
ইন্টারনেটের প্রায় ৪০% ওয়েবসাইট ওয়ার্ডপ্রেস দিয়ে বানানো। ওয়ার্ডপ্রেসে সাধারণত একটি ডিফল্ট REST API থাকে। লিংকের শেষে `/wp-json/wp/v2/posts` বসালে আপনি সরাসরি JSON ডেটা পেয়ে যাবেন!

তবে সার্ভার যেন ক্র্যাশ না করে, তাই API কখনো একসাথে সব ডেটা দেয় না। তারা ডেটাকে পেজে ভাগ করে দেয় (Pagination)। ওয়ার্ডপ্রেসে এক লিংকে সর্বোচ্চ ১০০টি ডেটা আনা যায় (`per_page=100`)। 

যদি কোনো সাইটে ৩০০টি পোস্ট থাকে, তবে লুপ চালিয়ে কীভাবে সব ডেটা একসাথে আনবেন তার একটি রিয়েল-লাইফ কোড:

```python
import requests

all_posts = [] 
page_number = 1

print("ডেটা সংগ্রহ শুরু হচ্ছে...")

while True:
    # per_page=100 এবং page=1, 2, 3... করে লুপ চলবে
    url = f"https://example.com/wp-json/wp/v2/posts?per_page=100&page={page_number}"
    
    print(f"পেজ {page_number} থেকে ডেটা আনা হচ্ছে...")
    response = requests.get(url)
    
    # যদি রিকোয়েস্টে এরর দেয় (যেমন পেজ আর নেই), লুপ বন্ধ করো
    if response.status_code != 200:
        break
        
    data = response.json()
    
    # যদি পেজে কোনো ডেটা না থাকে, তারমানে সব আনা শেষ! লুপ বন্ধ।
    if len(data) == 0:
        break
        
    # পাওয়া ডেটাগুলো আমাদের মূল লিস্টে যোগ করা
    all_posts.extend(data)
    page_number += 1

print(f"\nমোট {len(all_posts)} টি পোস্টের ডেটা পাওয়া গেছে!")
```

### ১১. অন্যান্য ওয়েবসাইট (Shopify, Reddit, React) থেকে API বের করার ট্রিকস
ওয়ার্ডপ্রেস ছাড়াও অন্যান্য জনপ্রিয় প্লাটফর্মে বানানো ওয়েবসাইটগুলোর API বের করার কিছু দারুণ হ্যাকার ট্রিকস আছে:

**১. Shopify (ই-কমার্স সাইট):**
ইন্টারনেটের অনেক বড় বড় ই-কমার্স সাইট Shopify দিয়ে বানানো। এই সাইটগুলোর প্রোডাক্টের ডেটা বের করা সবচেয়ে সহজ! যেকোনো শপিফাই সাইটের লিংকের শেষে শুধু `/products.json` বসিয়ে দিলেই আপনি সব প্রোডাক্টের নাম, দাম, ছবির লিংক JSON আকারে পেয়ে যাবেন।
*   **লিংক:** `https://example-shopify-store.com/products.json`

**২. Reddit (রেডিট):**
রেডিটের যেকোনো পেজ, সাব-রেডিট বা নির্দিষ্ট পোস্টের লিংকের শেষে শুধু `.json` বসিয়ে দিলে পুরো পেজটি API এ কনভার্ট হয়ে যায়! 
*   **লিংক:** `https://www.reddit.com/r/python.json` (Python সাব-রেডিটের সব পোস্ট)

**৩. Next.js বা React দিয়ে বানানো সাইট (Modern Web Apps):**
আজকালকার মডার্ন ওয়েবসাইটগুলো (সিঙ্গেল পেজ অ্যাপ্লিকেশন) লোড হওয়ার সময় নিজেদের ডেটা আলাদা ফাইল থেকে কল করে। 
*   **ট্রিক:** Network ট্যাবে গিয়ে `Fetch/XHR` ফিল্টারে খুঁজুন এমন কোনো লিংক আছে কিনা যার মধ্যে `/_next/data/` অথবা `graphql` অথবা `/api/v1/` লেখা আছে। এরাই হলো মেইন API!

**৪. সাধারণ API চেনার উপায়:**
যদি ওয়েবসাইটটি সম্পূর্ণ কাস্টম কোডে বানানো হয়, তবে তাদের API লিংকে সাধারণত এই শব্দগুলো থাকে (যা Network ট্যাবে খুঁজলে পাবেন):
*   `api.website.com/...` (সাবডোমেইন হিসেবে API)
*   `website.com/api/v1/...` (URL এর ভেতর)
*   `website.com/ajax/load_more` (Ajax কলের মাধ্যমে)

এই শর্টকাট ট্রিকসগুলো জানা থাকলে আপনার ওয়েব স্ক্র্যাপিংয়ের কাজ ঘণ্টার বদলে কয়েক সেকেন্ডেই হয়ে যাবে এবং BeautifulSoup দিয়ে কোড পার্স করার ঝামেলা থাকবে না!

### ১২. গোল্ডেন রুল: কখন API খুঁজবেন আর কখন BeautifulSoup ব্যবহার করবেন?
কোন ওয়েবসাইটের জন্য আপনি API খুঁজবেন আর কোন ওয়েবসাইটের জন্য সরাসরি BeautifulSoup (HTML Parsing) ব্যবহার করবেন, তার একটি ক্লিয়ার গাইডলাইন নিচে দেওয়া হলো:

#### 🟢 যেসব ওয়েবসাইটে API পাওয়ার সম্ভাবনা ৯৯% (যেখানে API খুঁজবেন)
এই ওয়েবসাইটগুলো সাধারণত সার্ভার থেকে সরাসরি HTML না পাঠিয়ে ব্রাউজারে API-এর মাধ্যমে JSON ডেটা পাঠায়। 
1.  **Modern Web Apps (React, Next.js, Vue):** Wappalyzer দিয়ে চেক করে যদি দেখেন সাইটটি React বা Vue দিয়ে বানানো, তবে নিশ্চিত থাকুন এর ডেটা কোনো না কোনো API থেকেই আসছে।
2.  **যাদের মোবাইল অ্যাপ আছে:** যেসব ওয়েবসাইটের মোবাইল অ্যাপ আছে, তারা সাধারণত অ্যাপ এবং ওয়েবসাইটের জন্য একই API ব্যবহার করে।
3.  **ই-কমার্স ওয়েবসাইট:** Shopify বা এ জাতীয় মডার্ন ই-কমার্স সাইটগুলোতে প্রোডাক্ট লোড করার জন্য ব্যাকএন্ড API ব্যবহার করা হয়।
4.  **বড় সোশ্যাল মিডিয়া বা প্ল্যাটফর্ম:** Reddit, GitHub, Twitter—এদের সবারই নিজস্ব API আছে।

*এই সাইটগুলোর ক্ষেত্রে আপনি সব সময় F12 চেপে Network ট্যাবের `Fetch/XHR` ফিল্টারে গিয়ে API খোঁজার চেষ্টা করবেন।*

#### 🔴 যেসব ওয়েবসাইটে API থাকে না (যেখানে BeautifulSoup ব্যবহার করতে হবে)
এই ওয়েবসাইটগুলো সার্ভারেই সব ডেটা প্রসেস করে রেডিমেড HTML পেজ ব্রাউজারে পাঠিয়ে দেয় (Server-Side Rendering)। 
1.  **পুরনো প্রযুক্তির সাইট (Old PHP, ASP.NET):** অনেক আগের বানানো সরকারি ওয়েবসাইট বা পুরোনো পোর্টালগুলোতে কোনো API থাকে না। ডেটা HTML Table বা লিস্ট আকারে আসে।
2.  **স্ট্যাটিক বা সাধারণ নিউজ পোর্টাল:** যেসব সাইটে কোনো লিংকে ক্লিক করলে পুরো পেজটি নতুন করে রিলোড হয়, সেগুলোতে সাধারণত API থাকে না। 
3.  **উইকিপিডিয়া বা সাধারণ ব্লগ:** উইকিপিডিয়ার মতো তথ্যবহুল সাইটগুলো মূলত টেক্সট-বেজড HTML ফাইল ডেলিভার করে।
4.  **কড়া সিকিউরিটি দেওয়া সাইট (Cloudflare/Datadome):** কিছু সাইট API ব্যবহার করলেও তারা এমন সিকিউরিটি দেয় যে পাইথন দিয়ে সেই API কল করলেই ব্লক করে দেবে। তখন বাধ্য হয়ে ব্রাউজার অটোমেশন (Selenium) দিয়ে পেজ ওপেন করে তার HTML নিয়ে BeautifulSoup দিয়ে স্ক্র্যাপ করতে হয়।

**💡 এক লাইনে রুলস (Rule of Thumb):**
যেকোনো সাইট থেকে ডেটা নেওয়ার আগে প্রথমে **Network ট্যাবে (`Fetch/XHR`)** চেক করবেন কোনো JSON ডেটা আসছে কিনা। যদি আসে, তাহলে **API** দিয়ে কাজ করবেন। আর যদি সেখানে কিছু না পান এবং দেখেন সব ডেটা মূল HTML-এই চলে এসেছে (`Doc` ট্যাব), তখনই কেবল **BeautifulSoup** ব্যবহার করবেন!

---

### সারসংক্ষেপ (Conclusion)
স্ক্র্যাপিং (Scraping), বটিং (Botting), অটোমেশন বা API টেস্টিং—পাইথনে ইন্টারনেটের যেকোনো কাজের মূল ভিত্তি হলো এই **`requests`** লাইব্রেরি। এর `Session` অবজেক্টের ব্যবহার এবং `headers` ম্যানিপুলেশনের টেকনিক জানা একজন হ্যাকার বা স্ক্র্যাপারের জন্য সবচেয়ে বড় শক্তি!
