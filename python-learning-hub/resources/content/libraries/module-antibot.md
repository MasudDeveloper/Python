# Codebreaker: Anti-Bot Bypass Masterclass

ওয়েব স্ক্র্যাপিংয়ের দুনিয়ায় আপনি যখন Amazon, LinkedIn, Google বা Cloudflare এবং Datadome এর মতো শক্তিশালি ফায়ারওয়াল (WAF) বসানো ওয়েবসাইট স্ক্র্যাপ করতে যাবেন, তখন শুধু `BeautifulSoup` বা `Playwright` দিয়ে কাজ হবে না।

সার্ভার আপনার আইপি ব্লক করে দিবে, Captcha দিয়ে আটকে দিবে অথবা আপনার পাইথনের TLS ফিঙ্গারপ্রিন্ট ধরে আপনাকে চিরতরে ব্যান করে দিবে। এই ধরনের এন্টারপ্রাইজ লেভেলের (Enterprise Level) সিকিউরিটি ভাঙার জন্য হ্যাকার এবং প্রো-স্ক্র্যাপাররা যেসব "Codebreaker" বা নিনজা টেকনিক ব্যবহার করে, আজ আমরা সেগুলো শিখবো।

---

## 🟢 পর্ব ১: Proxy Rotation (The Ghost Identity)

### ১. Proxy Rotation কী?
যখন আপনি একটি ওয়েবসাইট থেকে বারবার ডেটা টানেন, সার্ভার আপনার IP Address (যেমন: 103.111.45.12) দেখে বুঝতে পারে আপনি মানুষ নন। তখন সে ওই IP টি ব্লক করে দেয়। 
**Proxy** হলো এমন একটি সার্ভার, যা আপনার এবং টার্গেট ওয়েবসাইটের মাঝে দাঁড়িয়ে থাকে। আপনি রিকোয়েস্ট পাঠান প্রক্সিকে, আর প্রক্সি তার নিজের IP থেকে ওয়েবসাইটের কাছে রিকোয়েস্ট পাঠায়। 

**Proxy Rotation** মানে হলো, আপনি প্রতি রিকোয়েস্টের সময় একটি করে নতুন IP ব্যবহার করবেন। ফলে সার্ভার ভাববে পৃথিবীর বিভিন্ন দেশ থেকে আলাদা আলাদা মানুষ তার সাইটে ঢুকছে!

### ২. Requests এ Proxy ব্যবহার করা
```python
import requests

# আপনার কেনা বা ফ্রি প্রক্সির লিস্ট (IP:Port ফরমেট)
proxies = {
    "http": "http://198.51.100.25:8080",
    "https": "http://198.51.100.25:8080"
}

# প্রক্সি ব্যবহার করে রিকোয়েস্ট পাঠানো
try:
    response = requests.get("https://api.ipify.org?format=json", proxies=proxies, timeout=5)
    print("My Fake IP is:", response.json()['ip'])
except:
    print("Proxy Failed!")
```

### ৩. অটোমেটিক Proxy Rotation (Smart Proxies)
ম্যানুয়ালি হাজার হাজার প্রক্সি লিস্ট ম্যানেজ করা খুবই কষ্টের। প্রফেশনালরা **BrightData**, **ScraperAPI** বা **Oxylabs** এর মতো Rotating Proxy সার্ভিস ব্যবহার করে। তারা একটি মাত্র লিংক দেয়, এবং প্রতিবার রিকোয়েস্ট করার সময় তারাই আপনার IP পরিবর্তন করে দেয়!

```python
import requests

# ScraperAPI এর মতো সার্ভিস ব্যবহার করলে
API_KEY = "your_secret_api_key_here"
url_to_scrape = "https://amazon.com"

# প্রক্সি সার্ভিস আপনার হয়ে টার্গেট লিংকে ভিজিট করে ডেটা এনে দিবে!
proxy_url = f"http://api.scraperapi.com?api_key={API_KEY}&url={url_to_scrape}"

response = requests.get(proxy_url)
print(response.text) # আমাজনের ডেটা পেয়ে গেলেন কোনো ব্যান ছাড়া!
```

---

## 🟡 পর্ব ২: TLS / JA3 Fingerprinting (The Invisible Cloak)

### ৪. TLS Fingerprinting কী?
আপনি যদি Proxy ব্যবহার করেন, তারপরেও Cloudflare আপনাকে ধরে ফেলবে! কারণ আপনি যখন `requests` বা `urllib` দিয়ে সার্ভারে কানেক্ট করেন, তখন আপনার পাইথন স্ক্রিপ্ট সার্ভারের সাথে হ্যান্ডশেক (SSL/TLS Handshake) করে। 

এই হ্যান্ডশেক করার স্টাইল ব্রাউজারের (Chrome) থেকে আলাদা হয়। সার্ভার এই স্টাইলটির একটি হ্যাশ (Hash) তৈরি করে, যাকে বলা হয় **JA3 Fingerprint**। সার্ভার যদি দেখে আপনার JA3 Fingerprint গুগল ক্রোমের সাথে মিলছে না, সে আপনাকে সাথে সাথে 403 Forbidden দিয়ে দিবে!

### ৫. Curl-Cffi দিয়ে JA3 স্পুফিং (Spoofing)
TLS ফিঙ্গারপ্রিন্ট বাইপাস করার জন্য সাধারণ `requests` মডিউল কাজ করবে না। আমাদের দরকার `curl_cffi` নামের একটি স্পেশাল প্যাকেজ, যা হুবহু Google Chrome এর মতো TLS হ্যান্ডশেক করে!

টার্মিনালে ইন্সটল করুন:
```bash
pip install curl-cffi
```

**সাধারণ রিকোয়েস্ট বনাম TLS স্পুফিং:**
```python
import requests
from curl_cffi import requests as cffi_requests

url = "https://tls.peet.ws/api/all" # এই সাইটটি আপনার ফিঙ্গারপ্রিন্ট চেক করে

# ❌ ফেইল: সাধারণ requests (সার্ভার ধরে ফেলবে এটি পাইথন)
res1 = requests.get(url)
print("Normal Requests JA3:", res1.json()['tls']['ja3']) 
# দেখবেন ব্রাউজারের সাথে মিলছে না।

# ✅ সাকসেস: Chrome এর মতো ভান করা
res2 = cffi_requests.get(url, impersonate="chrome110")
print("Curl-Cffi JA3:", res2.json()['tls']['ja3'])
# সার্ভার ১০০% কনফার্ম হবে যে আপনি আসল Google Chrome (ভার্সন ১১০) থেকে এসেছেন!
```

---

## 🔴 পর্ব ৩: Captcha Automation (The Final Boss)

### ৬. Captcha সলভিং কী?
সার্ভার যখন চরম কনফিউজড থাকে আপনি মানুষ না বট, তখন সে একটি ধাঁধা (Captcha) ছুঁড়ে দেয়। যেমন: "Traffic Light সিলেক্ট করুন" (reCAPTCHA) বা "অঙ্ক মেলান" (hCaptcha)। এটি অটোমেশন দিয়ে পার করা সবচেয়ে কঠিন কাজ।

### ৭. 2Captcha বা AntiCaptcha API ব্যবহার করা (The Assassin Method)
ক্যাপচা সলভ করার জন্য সবচেয়ে সহজ এবং প্রো-লেভেল উপায় হলো থার্ড-পার্টি ক্যাপচা সলভিং সার্ভিস ব্যবহার করা। আপনি তাদের API এ ক্যাপচার ছবি বা সাইট-কী (Site Key) পাঠিয়ে দিবেন, তাদের অফিসে বসে থাকা আসল মানুষ বা AI ওই ক্যাপচা সলভ করে আপনাকে টোকেন (Token) দিয়ে দিবে!

প্রথমে ইন্সটল করুন: `pip install 2captcha-python`

```python
from twocaptcha import TwoCaptcha

# আপনার 2Captcha একাউন্টের API Key
api_key = 'YOUR_API_KEY_HERE'

solver = TwoCaptcha(api_key)

try:
    print("Sending Captcha to solve...")
    # ওয়েবসাইটের ক্যাপচা SiteKey এবং URL পাঠানো
    result = solver.recaptcha(
        sitekey='6Le-wvkSAAAAAPBMRTvw0Q4Muexq9bi0DJwx_mJ-',
        url='https://example.com/captcha-page'
    )
    
    print("Captcha Solved Successfully!")
    solved_token = result['code']
    
    # এবার এই টোকেনটি আপনার Playwright বা Selenium এর ফর্মে বসিয়ে সাবমিট করে দিলেই কেল্লাফতে!

except Exception as e:
    print("Failed to solve captcha:", e)
```

### ৮. Playwright + AI দিয়ে ফ্রি ক্যাপচা বাইপাস (The Free Ninja Way)
আপনি চাইলে কোনো পেইড সার্ভিস ছাড়াই অডিও (Audio) ক্যাপচা অপশনটি কাজে লাগিয়ে Speech-to-Text (যেমন Google Speech API) এর মাধ্যমে অটোমেটিকভাবে ক্যাপচা বাইপাস করতে পারেন। 

*   **টেকনিক:** যখন reCAPTCHA আসবে, তখন হেডলেস ব্রাউজার দিয়ে "Audio Challenge" বাটনে ক্লিক করবেন।
*   অডিও ফাইলটি `.mp3` ফরমেটে ডাউনলোড করবেন।
*   পাইথনের `SpeechRecognition` লাইব্রেরি দিয়ে অডিওর কথাগুলো টেক্সটে কনভার্ট করে ইনপুট বক্সে বসিয়ে দিবেন! (এটি অনেক হ্যাকাররা ব্যবহার করে থাকে)।

---

### সারসংক্ষেপ (Conclusion)
ওয়েব স্ক্র্যাপিং কোনো সাধারণ ডেটা কালেক্ট করার কাজ নয়, এটি মূলত সার্ভারের ফায়ারওয়ালের সাথে আপনার পাইথন কোডের একটি যুদ্ধ (War)।

১. **Proxy Rotation** দিয়ে আপনি নিজের অবস্থান লুকান (Invisibility)।
২. **TLS/JA3 Fingerprinting** দিয়ে আপনি নিজের আসল পরিচয় (Python) লুকিয়ে Google Chrome এর রূপ ধারণ করেন (Shape-shifting)।
৩. আর **Captcha Solvers** দিয়ে আপনি সবচেয়ে বড় দেয়ালটি ভেঙে ফেলেন (The Breaching)!

এই তিনটি বিষয় যিনি আয়ত্ত করতে পেরেছেন, ইন্টারনেটের কোনো ওয়েবসাইট তাকে ব্লক করার ক্ষমতা রাখে না!
