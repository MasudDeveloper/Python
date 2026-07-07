# Playwright (Zero to Hero) কমপ্লিট গাইড

ওয়েব অটোমেশন এবং স্ক্র্যাপিংয়ের জগতে একসময় **Selenium** রাজত্ব করলেও, বর্তমানে এর জায়গা দখল করে নিয়েছে মাইক্রোসফটের তৈরি **Playwright**। 

Selenium এর তুলনায় Playwright ১০ গুণ বেশি ফাস্ট, এতে কোনো ওয়েবড্রাইভার (Webdriver) আলাদা করে ডাউনলোড করতে হয় না এবং এর "Auto-wait" ফিচারের কারণে কোডে বারবার `time.sleep()` লিখতে হয় না!

এই টিউটোরিয়ালে আমরা একদম শূন্য থেকে শুরু করে **Playwright** ব্যবহার করে মডার্ন ওয়েব স্ক্র্যাপিং এবং এন্টি-বট বাইপাস করা শিখবো।

---

## 🟢 পর্ব ১: বিগিনার লেভেল (Fundamentals)

### ১. ইনস্টলেশন
Playwright ব্যবহার করার জন্য প্রথমে এর পাইথন লাইব্রেরি এবং ব্রাউজার ইঞ্জিনগুলো ডাউনলোড করতে হয়। টার্মিনালে নিচের কমান্ড দুটি রান করুন:

```bash
pip install playwright
playwright install
```
*(বিঃদ্রঃ `playwright install` কমান্ডটি Chromium, Firefox এবং WebKit (Safari) এর ডামি ব্রাউজারগুলো ডাউনলোড করে নিবে)।*

### ২. প্রথম স্ক্রিপ্ট (Sync API)
Playwright দুইভাবে কাজ করে: Sync (সাধারণ) এবং Async (অনেকগুলো কাজ একসাথে)। আমরা আপাতত Sync ব্যবহার করবো।

চলুন একটি ওয়েবসাইটে ঢুকে তার স্ক্রিনশট নিই:

```python
from playwright.sync_api import sync_playwright

def run():
    # Playwright চালু করা হলো
    with sync_playwright() as p:
        # Chromium (Google Chrome) ব্রাউজার ওপেন করা (headless=False দিলে ব্রাউজার চোখে দেখা যাবে)
        browser = p.chromium.launch(headless=False)
        
        # নতুন একটি ট্যাব বা পেজ খোলা
        page = browser.new_page()
        
        # নির্দিষ্ট লিংকে যাওয়া
        page.goto("https://en.wikipedia.org/")
        
        # ওয়েবসাইটের টাইটেল প্রিন্ট করা
        print("Page Title:", page.title())
        
        # পুরো পেজের একটি স্ক্রিনশট নেওয়া
        page.screenshot(path="wiki.png", full_page=True)
        
        # ব্রাউজার বন্ধ করা
        browser.close()

run()
```

### ৩. অটো-ওয়েট (Auto-waiting) এবং এলিমেন্ট সিলেক্ট করা
Selenium এ পেজ লোড হওয়া পর্যন্ত অপেক্ষা করতে হয়, কিন্তু Playwright নিজে থেকেই বোঝে কখন পেজ লোড হয়েছে!

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    page = browser.new_page()
    page.goto("https://www.saucedemo.com/")
    
    # ১. ইনপুট বক্সে লেখা (ID বা Name দিয়ে খোঁজা)
    page.locator("#user-name").fill("standard_user")
    page.locator("#password").fill("secret_sauce")
    
    # ২. বাটনে ক্লিক করা
    page.locator("#login-button").click()
    
    # ৩. টেক্সট দিয়ে এলিমেন্ট খোঁজা
    inventory = page.get_by_text("Products")
    print("Is Logged In?:", inventory.is_visible())
    
    browser.close()
```

---

## 🟡 পর্ব ২: ইন্টারমিডিয়েট (Interaction & Scraping)

### ৪. ডাটা স্ক্র্যাপ করা (Extracting Data)
Playwright দিয়ে খুব সহজেই ডাইনামিক (জাভাস্ক্রিপ্টে লোড হওয়া) ওয়েবসাইটের ডেটা স্ক্র্যাপ করা যায়।

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True) # Headless=True মানে ব্রাউজার ব্যাকগ্রাউন্ডে চলবে
    page = browser.new_page()
    page.goto("https://books.toscrape.com/")
    
    # সবগুলো বইয়ের টাইটেল সিলেক্ট করা
    books = page.locator("article.product_pod h3 a")
    
    # count() দিয়ে দেখলাম কয়টি বই আছে
    print("Total Books:", books.count())
    
    # for loop চালিয়ে ডেটা বের করা
    for i in range(books.count()):
        title = books.nth(i).get_attribute("title")
        print(title)
        
    browser.close()
```

### ৫. কীবোর্ড ও মাউস কন্ট্রোল (Keyboard & Mouse)
অনেক ওয়েবসাইটে স্ক্রল না করলে ডেটা লোড হয় না (Infinite Scrolling)।

```python
# পেজের একদম নিচে চলে যাওয়ার জন্য কীবোর্ডের End বাটন চাপা
page.keyboard.press("End")

# মাউস নির্দিষ্ট এলিমেন্টের ওপর নিয়ে যাওয়া (Hover)
page.locator(".dropdown-menu").hover()

# কোনো বাটনে ডাবল ক্লিক করা
page.locator("#submit").dblclick()
```

### ৬. নতুন ট্যাব এবং অ্যালার্ট হ্যান্ডলিং
ওয়েবসাইটে পপ-আপ অ্যালার্ট আসলে বা নতুন ট্যাবে লিংক ওপেন হলে সেটি কন্ট্রোল করা:

```python
# ১. পপ-আপ অ্যালার্ট হ্যান্ডলিং (অটোমেটিকভাবে OK ক্লিক করে দেওয়া)
page.on("dialog", lambda dialog: dialog.accept())
page.locator("#alert-button").click()

# ২. নতুন ট্যাবে ওপেন হওয়া পেজ ধরা
with page.expect_popup() as popup_info:
    page.locator("a[target='_blank']").click() # এমন লিংকে ক্লিক যা নতুন ট্যাবে খোলে
new_page = popup_info.value
print("New Tab URL:", new_page.url)
```

---

## 🔴 পর্ব ৩: অ্যাডভান্সড (Pro Ninja Level)

### ৭. এন্টি-বট বাইপাস (Bypassing Bot Detection)
Cloudflare বা মডার্ন ফায়ারওয়াল খুব সহজেই বটের ব্রাউজার চিনে ফেলে। তাদেরকে ধোঁকা দেওয়ার জন্য Playwright এ কিছু ম্যাজিক ট্রিকস আছে।

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    # 1. Playwright এর ডিফল্ট ব্রাউজারের বদলে আপনার পিসির আসল Chrome ব্যবহার করা!
    # (executable_path এ আপনার পিসির ক্রোম ব্রাউজারের লোকেশন দিতে হবে)
    browser = p.chromium.launch(
        headless=False, 
        executable_path=r"C:\Program Files\Google\Chrome\Application\chrome.exe"
    )
    
    # 2. আসল ইউজার-এজেন্ট, ভাষা এবং লোকেশন দেওয়া
    context = browser.new_context(
        user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36",
        locale="en-US",
        timezone_id="Asia/Dhaka",
        viewport={"width": 1920, "height": 1080}
    )
    
    # 3. Cloudflare কে বাইপাস করতে WebDriver ফ্ল্যাগ হাইড করা (Custom Script)
    page = context.new_page()
    page.add_init_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
    
    page.goto("https://bot.sannysoft.com/") # এখানে গিয়ে টেস্ট করতে পারেন আপনি বট নাকি মানুষ!
    page.screenshot(path="bot_test.png")
    browser.close()
```

### ৮. লগিন সেশন সেভ করা (Save Cookies)
ফেসবুক বা লিংকডইনে বারবার ইউজার আইডি পাসওয়ার্ড দিয়ে লগিন করলে একাউন্ট ব্যান হয়ে যায়। একবার লগিন করে তার কুকিজ বা স্ট্যাট সেভ করে রাখাই হলো প্রো-হ্যাকারদের কাজ!

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    
    # --- স্টেপ ১: প্রথমবার লগিন করে সেশন সেভ করা ---
    context = browser.new_context()
    page = context.new_page()
    page.goto("https://github.com/login")
    page.fill("#login_field", "my_email@gmail.com")
    page.fill("#password", "my_secret_pass")
    page.click("input[name='commit']")
    
    # লগিন সফল হলে কুকিজ এবং সেশন state.json ফাইলে সেভ করে রাখা!
    context.storage_state(path="state.json")
    browser.close()

# --- স্টেপ ২: পরবর্তীতে যখন কোড রান করবেন ---
with sync_playwright() as p:
    browser = p.chromium.launch(headless=False)
    # আগের সেভ করা ফাইলটি লোড করা, ফলে আর লগিন করতে হবে না!
    context = browser.new_context(storage_state="state.json") 
    
    page = context.new_page()
    page.goto("https://github.com/") # সরাসরি লগিন অবস্থায় প্রোফাইল ওপেন হবে!
    browser.close()
```

### ৯. নেটওয়ার্ক রিকোয়েস্ট ব্লক করা (Fast Scraping)
আপনি যখন কোনো সাইট স্ক্র্যাপ করেন, তখন ছবি, ফন্ট বা স্টাইল শিট (CSS) লোড হয়ে অনেক সময় নষ্ট হয় এবং ব্যান্ডউইথ খরচ হয়। এগুলো ব্লক করে দিলে স্ক্র্যাপিং ১০ গুণ ফাস্ট হয়!

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page()
    
    # ইমেইজ, ফন্ট এবং মিডিয়া ফাইল লোড হওয়া ব্লক করে দেওয়া
    page.route("**/*", lambda route: route.abort() 
               if route.request.resource_type in ["image", "media", "font", "stylesheet"] 
               else route.continue_())

    page.goto("https://amazon.com/") # কোনো ছবি লোড হবে না, শুধু টেক্সট লোড হবে চোখের পলকে!
    print("Page title:", page.title())
    
    browser.close()
```

---

### সারসংক্ষেপ (Conclusion)
আপনি যদি পাইথন দিয়ে মডার্ন ওয়েব স্ক্র্যাপিং, অটোমেশন বা টেস্টিং শিখতে চান, তবে **Playwright** হলো বর্তমান এবং ভবিষ্যতের সবচেয়ে সেরা টুল। Selenium এর দিন শেষ হয়ে আসছে। এর `storage_state` এবং `Network Interception` ফিচারগুলো একে অন্য সবার চেয়ে আলাদা এবং পাওয়ারফুল করেছে!
