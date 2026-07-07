# The Ninja Stealth API Interception (Zero to Hero)

সাধারণত আমরা যখন ওয়েব স্ক্র্যাপিং শিখি, তখন আমরা `BeautifulSoup` দিয়ে ওয়েবসাইটের HTML থেকে ডেটা খুঁজি। কিন্তু এটি অনেক স্লো এবং ঝামেলার। 

প্রো-লেভেলের হ্যাকার বা ডেটা ইঞ্জিনিয়াররা কখনো HTML থেকে ডেটা বের করে না। তারা ওয়েবসাইটের ব্যাকগ্রাউন্ডে চলা **গোপন API (Hidden API)** গুলোকে ট্র্যাক করে এবং সরাসরি সেই API থেকে সাজানো-গোছানো JSON ডেটা চুরি করে নিয়ে আসে! এই পদ্ধতিটিকেই বলা হয় **"API Interception"**।

এই টিউটোরিয়ালে আমরা শিখবো কীভাবে একজন "নিনজা" এর মতো ওয়েবসাইটের গোপন ডেটা ট্রাফিক ইন্টারসেপ্ট বা হাইজ্যাক করা যায়।

---

## 🟢 পর্ব ১: বিগিনার (The Secret Discovery)

### ১. API Interception কী এবং কেন?
ধরুন আপনি একটি ই-কমার্স সাইটে গেলেন। আপনি পেজ স্ক্রল করছেন আর নতুন নতুন জুতার ছবি লোড হচ্ছে (Infinite Scroll)। এখানে পুরো ওয়েবসাইটটি নতুন করে লোড হচ্ছে না, বরং সাইটের ব্যাকগ্রাউন্ডে একটি গোপন লিংক (API) দিয়ে শুধু জুতার ডেটাগুলো JSON ফরমেটে আসছে। 

আমরা যদি HTML থেকে ডেটা না খুঁজে সরাসরি ওই গোপন লিংকটি খুঁজে বের করতে পারি, তবে আমরা চোখের পলকে লাখ লাখ ডেটা পেয়ে যাবো!

### ২. গোপন API কীভাবে খুঁজে বের করবেন?
যেকোনো ওয়েবসাইটের গোপন API খোঁজার জন্য কোনো কোড লাগে না, আপনার ব্রাউজারই যথেষ্ট:
১. ওয়েবসাইটের যেকোনো জায়গায় রাইট ক্লিক করে **Inspect** এ যান।
২. ওপরের মেনু থেকে **Network** ট্যাবে ক্লিক করুন।
৩. এবার `Fetch/XHR` অপশনটি সিলেক্ট করুন। (XHR মানে হলো ব্যাকগ্রাউন্ডের API রিকোয়েস্ট)।
৪. এবার ওয়েবসাইটে একটু স্ক্রল করুন বা কোনো বাটনে ক্লিক করুন।
৫. দেখবেন Network ট্যাবে অনেকগুলো নতুন লিংক বা ফাইল লোড হচ্ছে। সেগুলোর ওপর ক্লিক করে ডানপাশের **Preview** বা **Response** ট্যাবে গেলেই দেখবেন আপনার কাঙ্ক্ষিত ডেটাগুলো সুন্দর JSON ফরমেটে সাজানো আছে!

---

## 🟡 পর্ব ২: ইন্টারমিডিয়েট (Replication Mode)

### ৩. Requests দিয়ে API ক্লোন করা (The Stealth Mode)
Network ট্যাব থেকে গোপন API এর লিংকটি খুঁজে পাওয়ার পর, আমরা সরাসরি পাইথনের `requests` লাইব্রেরি দিয়ে সেই লিংক থেকে ডেটা নিয়ে আসতে পারি। 

তবে মনে রাখবেন, সরাসরি লিংক কপি করে রিকোয়েস্ট দিলে সার্ভার আপনাকে ব্লক করে দিবে। তাই ব্রাউজারের ইনস্পেক্ট টুল থেকে ওই রিকোয়েস্টের ওপর রাইট ক্লিক করে **"Copy as cURL"** করবেন, তারপর যেকোনো cURL to Python কনভার্টার ওয়েবসাইট (যেমন: `curlconverter.com`) দিয়ে কোড জেনারেট করে নিবেন।

```python
import requests

# Network ট্যাব থেকে পাওয়া গোপন API এর লিংক
api_url = "https://api.example.com/v1/hidden-shoes-data?page=2"

# ব্রাউজার থেকে কপি করা হুবহু হেডার্স (যাতে সার্ভার বুঝতে না পারে আপনি বট)
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
    "Accept": "application/json",
    "Authorization": "Bearer hidden_token_12345",
    "Referer": "https://www.example.com/"
}

# সরাসরি API থেকে ডেটা নিয়ে আসা
response = requests.get(api_url, headers=headers)
data = response.json() # সরাসরি ডিকশনারি পেয়ে গেলাম!

# এখন আর BeautifulSoup দিয়ে ট্যাগ খোঁজার দরকার নেই!
for shoe in data['products']:
    print(f"Name: {shoe['title']} | Price: {shoe['price']}")
```
*এটি সবচেয়ে ফাস্ট স্ক্র্যাপিং পদ্ধতি, তবে সাইটে যদি শক্তিশালি Bot Protection (যেমন Cloudflare) থাকে, তখন এই ট্রিক কাজ করবে না।*

---

## 🔴 পর্ব ৩: অ্যাডভান্সড (The Real Ninja Interception)

যখন ওয়েবসাইট অনেক বেশি সিকিউর থাকে এবং API রিকোয়েস্টগুলোতে বারবার টোকেন (Token) পরিবর্তন হয়, তখন আমরা `requests` দিয়ে তা ধরতে পারি না। তখন আমাদেরকে আসল ব্রাউজারের ভেতর থেকে ডেটা "ছিনতাই" (Intercept) করতে হয়! 

### ৪. Playwright দিয়ে রিয়েল-টাইম API Interception (Master level)
Playwright এর সবচেয়ে ভয়ংকর ফিচার হলো, এটি ব্রাউজারে যা লোড হচ্ছে তার সবকিছুর ওপর নজরদারি করতে পারে। আমরা Playwright কে বলবো— "ওয়েবসাইট যখন ব্যাকগ্রাউন্ডে কোনো API কল করবে, তুমি সেই API এর রেসপন্সটি আমার জন্য ধরে নিয়ে আসবে!"

```python
from playwright.sync_api import sync_playwright
import json

def intercept_api(response):
    # আমরা শুধু সেই রেসপন্সগুলো ধরবো যেগুলোর লিংকে 'api/v1/shoes' লেখা আছে 
    # এবং যেগুলোর স্ট্যাটাস 200 (Success)
    if "api/v1/shoes" in response.url and response.status == 200:
        print(f"\n--- [NINJA ALERT] API Intercepted: {response.url} ---")
        
        # API থেকে আসা JSON ডেটাটি হাইজ্যাক করে প্রিন্ট করা
        raw_data = response.json()
        print(json.dumps(raw_data, indent=2)) 

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()

    # ওয়েবসাইটের যেকোনো রেসপন্স আসার সাথে সাথে আমাদের ফাংশনটি কল হবে (The Hook)
    page.on("response", intercept_api)

    # ওয়েবসাইটে প্রবেশ করা
    print("Navigating to website...")
    page.goto("https://www.example.com/shoes-page")
    
    # মানুষের মতো স্ক্রল করা যাতে ওয়েবসাইট ব্যাকগ্রাউন্ডে API কল করে
    page.mouse.wheel(0, 2000)
    page.wait_for_timeout(3000) # ডেটা লোড হওয়ার জন্য একটু অপেক্ষা
    
    browser.close()
```
**এই পদ্ধতি কেন সেরা?**
কারণ এখানে আপনাকে কোনো হেডার, টোকেন বা কুকিজ নিয়ে ভাবতে হবে না! ব্রাউজার নিজে থেকে মানুষের মতো লগিন করবে, সিকিউরিটি বাইপাস করবে, আর আপনি শুধু মাঝখান থেকে API এর ডেটাগুলো রিসিভ করে নিবেন!

### ৫. Selenium Wire দিয়ে API Interception
আপনি যদি Playwright এর বদলে Selenium ব্যবহার করতে পছন্দ করেন, তবে সাধারণ Selenium দিয়ে এই কাজ করা যায় না। এর জন্য `selenium-wire` নামের একটি এক্সট্রা প্যাকেজ ব্যবহার করতে হয়।

প্রথমে ইন্সটল করুন: `pip install selenium-wire webdriver-manager`

```python
from seleniumwire import webdriver # সাধারণ selenium এর বদলে seleniumwire
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time
import json

# ব্রাউজার সেটআপ
options = webdriver.ChromeOptions()
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

driver.get('https://www.example.com/shoes-page')
time.sleep(5) # API লোড হওয়ার জন্য অপেক্ষা

# ব্রাউজারে যতগুলো রিকোয়েস্ট হয়েছে সবগুলোর লিস্ট চেক করা
for request in driver.requests:
    # আমরা শুধু আমাদের কাঙ্ক্ষিত API লিংকেই ফোকাস করবো
    if request.response and "api/v1/shoes" in request.url:
        print(f"\n[Intercepted] URL: {request.url}")
        
        # বডি থেকে ডেটা ডিকোড (Decode) করে JSON এ কনভার্ট করা
        body = request.response.body.decode('utf-8')
        data = json.loads(body)
        print("Stolen Data:", data)

driver.quit()
```

---

### সারসংক্ষেপ (Conclusion)
১. **Beginner:** `BeautifulSoup` দিয়ে HTML ট্যাগ থেকে ডেটা খোঁজে।
২. **Intermediate:** ইনস্পেক্ট করে গোপন API খুঁজে বের করে এবং `requests` দিয়ে সেটি ডিরেক্ট কল করে ডেটা নিয়ে আসে।
৩. **Ninja/Pro:** `Playwright` বা `Selenium Wire` ব্যবহার করে ব্রাউজারের নেটওয়ার্ক ট্রাফিক মনিটর করে এবং রিয়েল-টাইমে API হাইজ্যাক করে ডেটা চুরি করে!

স্ক্র্যাপিংয়ের দুনিয়ায় **API Interception** হলো আলটিমেট ব্রেন-হ্যাক। এটি শিখে গেলে আপনাকে আর ঘণ্টার পর ঘণ্টা HTML ট্যাগ নিয়ে মাথা ঘামাতে হবে না!
