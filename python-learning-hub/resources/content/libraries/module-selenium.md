# Selenium (Zero to Hero) কমপ্লিট গাইড

ইন্টারনেট থেকে ডেটা স্ক্র্যাপ করার জন্য `BeautifulSoup` সবচেয়ে বেস্ট হলেও এর একটি বিশাল সমস্যা আছে। মডার্ন ওয়েবসাইটগুলো (যেমন: Facebook, YouTube, বা Single Page Application) তাদের ডেটা সরাসরি HTML এ পাঠায় না, বরং **জাভাস্ক্রিপ্ট (JavaScript)** দিয়ে ব্রাউজারে ডেটা লোড করে। `BeautifulSoup` জাভাস্ক্রিপ্ট রান করতে পারে না, তাই সে ফাঁকা পেজ পায়।

এই সমস্যার সমাধান হলো **Selenium (সেলেনিয়াম)**। এটি মূলত একটি ব্রাউজার অটোমেশন টুল, যা আপনার কম্পিউটারের আসল ক্রোম (Chrome) বা ফায়ারফক্স (Firefox) ব্রাউজারকে রোবটের মতো কন্ট্রোল করতে পারে! এটি বাটনে ক্লিক করতে পারে, ফর্মে টাইপ করতে পারে, এবং স্ক্রলিং করতে পারে।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Headless Mode এবং Waits পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক ব্রাউজার ওপেন করা
প্রথমে লাইব্রেরিটি এবং ব্রাউজার কন্ট্রোল করার জন্য `webdriver-manager` ইনস্টল করে নিন (যাতে বারবার ক্রোমড্রাইভার ডাউনলোড করতে না হয়):
```bash
pip install selenium webdriver-manager
```

```python
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time

# ১. অটোমেটিকভাবে লেটেস্ট ক্রোমড্রাইভার সেটআপ করে ক্রোম ব্রাউজার ওপেন করা
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service)

# ২. যেকোনো লিংকে যাওয়া (ব্রাউজার নিজে থেকে ওপেন হয়ে এই লিংকে যাবে!)
driver.get("https://www.google.com")

print("Page Title:", driver.title)

# ৩. কাজ শেষে ৫ সেকেন্ড পর ব্রাউজার ক্লোজ করা
time.sleep(5)
driver.quit()
```

### ২. এলিমেন্ট খোঁজা এবং টাইপ/ক্লিক করা
আমরা গুগল ওপেন করে সেখানে কিছু লিখে সার্চ বাটনে ক্লিক করার একটি বট বানাবো।

```python
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys # কীবোর্ডের বাটন (যেমন Enter) প্রেস করার জন্য
import time

driver = webdriver.Chrome()
driver.get("https://www.google.com")

# ১. গুগলের সার্চ বক্সটি খোঁজা (এর নাম 'q' দেওয়া থাকে)
search_box = driver.find_element(By.NAME, "q")

# ২. বক্সে কিছু টাইপ করা (send_keys)
search_box.send_keys("Python Selenium Tutorial")

# ৩. টাইপ করার পর কীবোর্ডের ENTER বাটন প্রেস করা
search_box.send_keys(Keys.RETURN)

time.sleep(5)
driver.quit()
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. ওয়েটিং স্ট্র্যাটেজি (Waits - Implicit vs Explicit)
ইন্টারনেট স্লো হলে কোনো বাটন বা টেক্সট স্ক্রিনে আসার আগেই সেলেনিয়াম ক্লিক করার চেষ্টা করে এবং `NoSuchElementException` এরর দিয়ে ক্র্যাশ করে। এর জন্য `time.sleep()` ব্যবহার করা বোকামি। 

**ক) Implicit Wait:** ব্রাউজারকে বলে দেওয়া যে কোনো কিছু না পেলে সাথে সাথে এরর না দিয়ে অন্তত ১০ সেকেন্ড ওয়েট করো।
```python
driver.implicitly_wait(10) # কোডের শুরুতেই একবার লিখে দিতে হয়
```

**খ) Explicit Wait (প্রো-লেভেল):** নির্দিষ্ট কোনো বাটনের জন্য নির্দিষ্ট কন্ডিশন (যেমন: বাটনটি কি ক্লিকেবল হয়েছে?) পূরণ হওয়া পর্যন্ত ওয়েট করা। এটি অনেক বেশি ফাস্ট এবং রিলায়েবল!

```python
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By

driver = webdriver.Chrome()
driver.get("https://example.com")

try:
    # সর্বোচ্চ ১০ সেকেন্ড অপেক্ষা করবে। যদি তার আগেই বাটনটি ক্লিক করার উপযুক্ত (clickable) হয়ে যায়, 
    # তবে সাথে সাথে ক্লিক করে পরের লাইনে চলে যাবে!
    button = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.ID, "submit-button"))
    )
    button.click()
except:
    print("Button was not found within 10 seconds!")
```

### ৪. হেডলেস মোড (Headless Mode - ব্যাকগ্রাউন্ডে রান করা)
স্ক্র্যাপিং করার সময় বারবার সামনে ব্রাউজার ওপেন হয়ে বিরক্ত করলে কাজে মনোযোগ দেওয়া যায় না। আপনি চাইলে ব্রাউজারটিকে পুরোপুরি লুকিয়ে (Headless) ব্যাকগ্রাউন্ডে রান করাতে পারেন! সার্ভারে (যেমন AWS) রান করালে এটি দেওয়াই লাগে।

```python
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

# ১. ক্রোমের অপশন সেট করা
chrome_options = Options()
chrome_options.add_argument("--headless") # ব্রাউজার হাইড করার কমান্ড
chrome_options.add_argument("--disable-gpu")
chrome_options.add_argument("--window-size=1920x1080")

# ২. অপশনগুলো দিয়ে ব্রাউজার চালু করা
driver = webdriver.Chrome(options=chrome_options)
driver.get("https://python.org")

print("Title from Background:", driver.title) # ব্রাউজার না দেখিয়েই কাজ করবে!
driver.quit()
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. মাল্টিপল ট্যাব এবং আইফ্রেম (Tabs & Iframes) হ্যান্ডলিং
অনেক সময় লিংকে ক্লিক করলে নতুন একটি ট্যাব ওপেন হয়। সেলেনিয়াম নিজে থেকে নতুন ট্যাবে ফোকাস করতে পারে না, তাকে বলে দিতে হয়।

```python
import time

driver = webdriver.Chrome()
driver.get("https://example.com")

# ১. নতুন একটি ট্যাব ওপেন করা
driver.execute_script("window.open('https://google.com', '_blank');")
time.sleep(2)

# ২. উইন্ডো হ্যান্ডেল (Window Handles) দিয়ে সবগুলো ট্যাবের লিস্ট নেওয়া
tabs = driver.window_handles

# ৩. দ্বিতীয় ট্যাবে (index 1) ফোকাস বা সুইচ (Switch) করা
driver.switch_to.window(tabs[1])
print("Currently in Tab:", driver.title)

# ৪. কাজ শেষে আবার আগের বা প্রথম ট্যাবে ফিরে আসা
driver.switch_to.window(tabs[0])
```

একইভাবে ওয়েবসাইটের ভেতরে যদি কোনো `iframe` (যেমন: এম্বেড করা ইউটিউব ভিডিও বা গুগল ক্যাপচা) থাকে, তবে সেখানে সুইচ করতে হয়:
```python
# iframe এর আইডি বা নাম দিয়ে সুইচ করা
driver.switch_to.frame("captcha-frame")
# এখন আপনি iframe এর ভেতরের জিনিসগুলো খুঁজলে পাবেন...

# কাজ শেষে আবার মেইন পেইজে ফিরে আসা
driver.switch_to.default_content()
```

### ৬. স্ক্রিনশট নেওয়া (Screenshot)
স্ক্র্যাপিংয়ের সময় বা টেস্টিংয়ের সময় কোনো এরর হলে বা প্রমাণ রাখার জন্য স্ক্রিনশট নেওয়া যায়।

```python
driver = webdriver.Chrome()
driver.get("https://github.com")

# পুরো পেজের স্ক্রিনশট নেওয়া
driver.save_screenshot("github_homepage.png")

# নির্দিষ্ট কোনো এলিমেন্টের (যেমন লোগো) স্ক্রিনশট নেওয়া
logo = driver.find_element(By.CLASS_NAME, "octicon-mark-github")
logo.screenshot("github_logo.png")

driver.quit()
```

### ৭. বট ডিটেকশন বাইপাস করা (Undetected ChromeDriver)
আজকাল ক্লাউডফ্লেয়ার (Cloudflare) বা অনেক ওয়েবসাইট সেলেনিয়ামকে রোবট হিসেবে চিনে ফেলে এবং ব্লক করে দেয় (ক্যাপচা দিয়ে দেয়)। এই সমস্যা এড়ানোর জন্য সাধারণ সেলেনিয়ামের বদলে `undetected_chromedriver` নামের থার্ড-পার্টি লাইব্রেরি ব্যবহার করা হয়, যা ওয়েবসাইটকে বোকা বানিয়ে বোঝায় যে এটি আসল মানুষ!

প্রথমে ইনস্টল করুন: `pip install undetected-chromedriver`

```python
import undetected_chromedriver as uc
import time

# সাধারণ webdriver এর বদলে uc.Chrome() ব্যবহার করতে হয়
driver = uc.Chrome()
driver.get("https://nowsecure.nl") # এটি ক্লাউডফ্লেয়ার প্রোটেক্টেড সাইট

time.sleep(10)
print("Bypassed Security! Page Title:", driver.title)
driver.quit()
```

### সারসংক্ষেপ (Conclusion)
যদিও জাভাস্ক্রিপ্ট রেন্ডার করা সাইটের জন্য **Selenium** বেস্ট, কিন্তু এটি সাধারণ `requests` এর চেয়ে অনেক বেশি মেমোরি খরচ করে এবং স্লো। তাই প্রো-লেভেলের ডেটা ইঞ্জিনিয়াররা সবসময় চেষ্টা করে API খুঁজে বের করার। যদি API না পাওয়া যায়, তখনই শুধুমাত্র সর্বশেষ ভরসা হিসেবে সেলেনিয়ামের (خاص করে `undetected_chromedriver`) শরণাপন্ন হতে হয়!
