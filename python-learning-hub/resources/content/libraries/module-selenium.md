# Selenium (Zero to Hero) কমপ্লিট গাইড

BeautifulSoup এবং Requests দিয়ে অনেক কাজ করা গেলেও, যেসব ওয়েবসাইটে জাভাস্ক্রিপ্ট (JavaScript) দিয়ে ডেটা লোড হয় বা লগিন করার সময় মানুষের মতো মাউস নাড়ানোর প্রয়োজন হয়, সেখানে সাধারণ স্ক্র্যাপিং কাজ করে না।

এসব ক্ষেত্রে আমাদের দরকার হয় এমন একটি টুলের যা একদম সত্যিকারের মানুষের মতো একটি ব্রাউজার (Google Chrome বা Firefox) ওপেন করবে এবং অটোমেটিক ক্লিক, টাইপ বা স্ক্রল করবে। ওয়েব অটোমেশনের এই জগতের সবচেয়ে পুরোনো এবং জনপ্রিয় রাজা হলো **Selenium**।

এই টিউটোরিয়ালে আমরা একদম শূন্য থেকে শুরু করে **Selenium** এর প্রো-লেভেল এন্টি-বট বাইপাস পর্যন্ত সবকিছু শিখবো।

---

## 🟢 পর্ব ১: বিগিনার লেভেল (Fundamentals)

### ১. ইনস্টলেশন এবং অটোমেটিক ড্রাইভার সেটআপ
আগে Selenium ব্যবহার করার জন্য ম্যানুয়ালি "ChromeDriver" ডাউনলোড করে পাথ (Path) সেট করতে হতো। এখন `webdriver-manager` এর সাহায্যে এটি অটোমেটিক হয়ে যায়। টার্মিনালে নিচের কমান্ডটি রান করুন:

```bash
pip install selenium webdriver-manager
```

### ২. প্রথম অটোমেশন স্ক্রিপ্ট (Opening a Browser)
চলুন পাইথন দিয়ে গুগল ক্রোম ওপেন করে একটি ওয়েবসাইটে ভিজিট করি:

```python
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

# ১. অটোমেটিকভাবে ক্রোম ড্রাইভার ডাউনলোড এবং সেটআপ করা
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service)

# ২. ওয়েবসাইটে প্রবেশ করা
driver.get("https://www.wikipedia.org/")

# ৩. ওয়েবসাইটের টাইটেল প্রিন্ট করা
print("Page Title:", driver.title)

# ৪. কিছুক্ষণ অপেক্ষা করে ব্রাউজার বন্ধ করে দেওয়া
time.sleep(3)
driver.quit()
```

### ৩. এলিমেন্ট খোঁজা (Locators)
Selenium দিয়ে কোনো বাটনে ক্লিক বা টাইপ করার আগে তাকে খুঁজে বের করতে হয়। এর জন্য `By` ক্লাসটি ব্যবহার করা হয়।

```python
from selenium.webdriver.common.by import By

# ১. ID দিয়ে খোঁজা (সবচেয়ে ফাস্ট)
search_box = driver.find_element(By.ID, "searchInput")

# ২. Name দিয়ে খোঁজা
search_box = driver.find_element(By.NAME, "search")

# ৩. Class Name দিয়ে খোঁজা
button = driver.find_element(By.CLASS_NAME, "search-btn")

# ৪. XPath দিয়ে খোঁজা (সবচেয়ে পাওয়ারফুল, যদি ID বা Class না থাকে)
# ব্রাউজারের Inspect এ গিয়ে রাইট ক্লিক করে "Copy XPath" করা যায়
button = driver.find_element(By.XPATH, '//*[@id="search-form"]/fieldset/button')
```

---

## 🟡 পর্ব ২: ইন্টারমিডিয়েট (Interaction & Waits)

### ৪. ক্লিক করা এবং টাইপ করা (Click & Type)
এলিমেন্ট খোঁজার পর আমরা মানুষের মতো তাতে টাইপ বা ক্লিক করতে পারি।

```python
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys # কীবোর্ডের বাটন চাপার জন্য

# সার্চ বক্সে "Python" টাইপ করা
search_box = driver.find_element(By.ID, "searchInput")
search_box.send_keys("Python")

# কীবোর্ডের Enter বাটন চাপা
search_box.send_keys(Keys.RETURN)

# অথবা সাবমিট বাটনে ক্লিক করা
# driver.find_element(By.XPATH, '//*[@type="submit"]').click()
```

### ৫. ওয়েট করা (Implicit vs Explicit Waits)
ইন্টারনেটের স্পিড সব সময় এক থাকে না। ওয়েবসাইট লোড হওয়ার আগেই যদি পাইথন ক্লিক করতে যায়, তবে `NoSuchElementException` এরর আসবে। তাই `time.sleep()` এর বদলে স্মার্ট ওয়েট (Smart Wait) ব্যবহার করতে হয়।

**Explicit Wait (সবচেয়ে ভালো পদ্ধতি):** এটি একটি নির্দিষ্ট এলিমেন্ট স্ক্রিনে আসা পর্যন্ত অপেক্ষা করবে।
```python
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# সর্বোচ্চ ১০ সেকেন্ড অপেক্ষা করবে
wait = WebDriverWait(driver, 10)

# বাটনটি ক্লিক করার জন্য স্ক্রিনে আসা পর্যন্ত অপেক্ষা করা
submit_button = wait.until(EC.element_to_be_clickable((By.ID, "submit_btn")))
submit_button.click()
```

### ৬. স্ক্রল করা এবং জাভাস্ক্রিপ্ট রান করানো
অনেক ওয়েবসাইটে নিচে স্ক্রল না করলে ডেটা লোড হয় না (Infinite Scrolling)। Selenium এর মাধ্যমে ব্রাউজারে সরাসরি জাভাস্ক্রিপ্ট কোড রান করানো যায়!

```python
# ১. পেজের একদম নিচে চলে যাওয়া (স্ক্রল ডাউন)
driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")

# ২. নির্দিষ্ট একটি এলিমেন্ট পর্যন্ত স্ক্রল করা
target = driver.find_element(By.ID, "footer")
driver.execute_script("arguments[0].scrollIntoView();", target)
```

---

## 🔴 পর্ব ৩: অ্যাডভান্সড (Pro Ninja Level)

### ৭. হেডলেস মোড (Headless Mode)
যখন আপনি সার্ভারে (যেমন AWS বা VPS) কোড রান করবেন, তখন সেখানে কোনো স্ক্রিন থাকে না। তাই ব্রাউজারকে না দেখিয়ে ব্যাকগ্রাউন্ডে রান করতে হয়।

```python
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

chrome_options = Options()
chrome_options.add_argument("--headless") # ব্রাউজার দেখা যাবে না
chrome_options.add_argument("--window-size=1920,1080") # ফুল সাইজ উইন্ডো

driver = webdriver.Chrome(options=chrome_options)
driver.get("https://github.com/")
print("Opened in background!")
driver.quit()
```

### ৮. এন্টি-বট বাইপাস (Undetected ChromeDriver)
Cloudflare বা মডার্ন ফায়ারওয়াল সাধারণ Selenium দেখলেই `Access Denied` বা `Captcha` দিয়ে দেয়। কারণ Selenium এর ভেতরে `webdriver=true` নামের একটি ভ্যারিয়েবল থাকে যা সার্ভার ধরতে পারে। এটি বাইপাস করার জন্য **Undetected ChromeDriver** নামের একটি স্পেশাল প্যাকেজ ব্যবহার করতে হয়।

প্রথমে ইন্সটল করুন: `pip install undetected-chromedriver`

```python
import undetected_chromedriver as uc
import time

# সাধারণ webdriver এর বদলে uc.Chrome() ব্যবহার করতে হবে
options = uc.ChromeOptions()
options.add_argument("--disable-popup-blocking")

# এটি ১০০% আসল ক্রোম ব্রাউজারের মতো আচরণ করবে!
driver = uc.Chrome(options=options)

driver.get("https://nowsecure.nl/") # Cloudflare প্রোটেকশন চেক করার সাইট
time.sleep(10)
driver.save_screenshot("cloudflare_bypassed.png")
driver.quit()
```

### ৯. কুকিজ সেভ এবং লোড করা (Login Session Saving)
লগিন করার পর কুকিজগুলো একটি ফাইলে সেভ করে রাখলে পরবর্তীতে আর বারবার লগিন করতে হয় না। এর জন্য `pickle` লাইব্রেরি ব্যবহার করা হয়।

```python
import pickle
import time

# --- ১. প্রথমবার লগিন করে কুকিজ সেভ করা ---
# driver.get("https://example.com/login")
# (লগিন করার কোড লিখুন)
# pickle.dump(driver.get_cookies(), open("cookies.pkl", "wb"))

# --- ২. পরবর্তীতে কুকিজ লোড করে সরাসরি লগিন হওয়া ---
driver.get("https://example.com") # প্রথমে মেইন ডোমেইনে যেতে হবে
cookies = pickle.load(open("cookies.pkl", "rb"))

for cookie in cookies:
    driver.add_cookie(cookie)

driver.refresh() # পেজ রিফ্রেশ করলেই দেখবেন আপনি লগিন অবস্থায় আছেন!
```

---

## 📚 Selenium শেখার সেরা রিসোর্স (Tutorial Recommendations)

যেহেতু আপনি Selenium এর একদম ফুল গাইড চাচ্ছেন, তাই ভিডিও টিউটোরিয়াল দেখে প্র্যাকটিস করাটা সবচেয়ে ভালো হবে। নিচে কিছু সেরা রিসোর্সের লিংক দেওয়া হলো:

### ১. ইউটিউব চ্যানেল (YouTube Tutorials)
*   **Tech With Tim:** ইউটিউবে "Tech With Tim Selenium Python" লিখে সার্চ দিলে একটি চমৎকার ১ ঘণ্টার ক্র্যাশ কোর্স পাবেন। বিগিনারদের জন্য এটি সেরা।
*   **ProgrammingKnowledge:** এদের Selenium Python এর পুরো প্লেলিস্ট আছে যেখানে প্রতিটি টপিক (Wait, Locators, ActionChains) আলাদাভাবে শেখানো হয়েছে।
*   **FreeCodeCamp:** "Selenium Python Tutorial for Beginners" নামের ২-৩ ঘণ্টার ভিডিও আছে, যা দেখলে আর কোনো কিছুর প্রয়োজন হবে না।

### ২. ওয়েবসাইট এবং ডকুমেন্টেশন
*   **[Selenium Official Docs](https://www.selenium.dev/documentation/):** যখন কোনো কমান্ড ভুলে যাবেন, তখন সরাসরি অফিশিয়াল ওয়েবসাইটটি দেখবেন। এটি খুবই সুন্দর করে সাজানো।
*   **[Selenium Python ReadTheDocs](https://selenium-python.readthedocs.io/):** এটি পাইথনের জন্য সবচেয়ে সেরা চিট-শিট। এখানে `Wait`, `Keys` এবং `Locators` এর সব চার্ট দেওয়া আছে। 

### সারসংক্ষেপ (Conclusion)
Selenium একটু ভারী এবং স্লো হলেও, এটি পুরো পৃথিবীর ওয়েব অটোমেশনের ইন্ডাস্ট্রি স্ট্যান্ডার্ড। ডেটা এন্ট্রি অটোমেশন, টেস্টিং বা সোশ্যাল মিডিয়া বট বানানোর জন্য Selenium এর `WebDriverWait` এবং `Undetected ChromeDriver` এর ট্রিকসগুলো আয়ত্ত করাটা সবচেয়ে বেশি জরুরি!
