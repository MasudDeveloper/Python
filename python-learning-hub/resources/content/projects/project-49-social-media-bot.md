# ৪৯. সোশ্যাল মিডিয়া অটোমেশন বট (Social Media Bot)

প্রতিদিন একই সময়ে ইন্সটাগ্রাম বা ফেসবুকে পোস্ট করা, অন্যের প্রোফাইলে গিয়ে লাইক দেওয়া বা কমেন্ট করা খুবই বিরক্তিকর কাজ। কিন্তু আপনি চাইলে পাইথনের **Selenium** ব্যবহার করে আপনার নিজের একটি রোবট বা বট (Bot) বানাতে পারেন, যে আপনার ব্রাউজার কন্ট্রোল করে অটোমেটিকভাবে এই কাজগুলো করে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **Web Driver:** সেলেনিয়াম (Selenium) একটি ওয়েব ড্রাইভারের (যেমন Chrome WebDriver) মাধ্যমে আপনার আসল ব্রাউজারটি ওপেন করবে।
2. **Locating Elements:** ওয়েবসাইটের ইউজারনেম ফিল্ড, পাসওয়ার্ড ফিল্ড বা লাইক বাটনগুলো `XPath` বা `ID` এর মাধ্যমে খুঁজে বের করবে।
3. **Automated Actions:** এরপর আপনার দেওয়া লজিক অনুযায়ী বটটি নিজে নিজেই টাইপ করবে (send_keys), ক্লিক করবে (click) এবং পেজ স্ক্রল করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে সেলেনিয়াম ইনস্টল করে নিন:
```bash
pip install selenium webdriver-manager
```

### প্রজেক্টের কোড:
নিচের কোডটি একটি অটোমেশন বটের বেসিক স্ট্রাকচার, যা স্বয়ংক্রিয়ভাবে ব্রাউজার ওপেন করে একটি ওয়েবসাইটে লগইন করার চেষ্টা করবে।

```python
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from webdriver_manager.chrome import ChromeDriverManager
import time

def social_media_automation():
    print("=== Social Media Automation Bot ===")
    print("Starting Chrome Browser...\n")
    
    # অটোমেটিকভাবে ক্রোম ড্রাইভার সেটআপ করা
    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    
    try:
        # ১. ওয়েবসাইটে যাওয়া (যেমন: instagram.com বা যেকোনো সাইট)
        # (এখানে ডেমো পারপাসে example.com ব্যবহার করা হচ্ছে)
        driver.get("https://www.example.com")
        print("✅ Visited the website!")
        time.sleep(2) # পেজ লোড হওয়ার জন্য ২ সেকেন্ড অপেক্ষা
        
        # ২. HTML ইলিমেন্ট (যেমন লগইন বক্স) খুঁজে বের করা এবং টাইপ করা
        # বাস্তবে এখানে (By.NAME, "username") বা XPath ব্যবহার করতে হয়
        print("🤖 Locating Login fields and typing credentials...")
        
        # ডেমো: পুরো পেজটি স্ক্রল করা
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(2)
        
        # ৩. কোনো নির্দিষ্ট লিঙ্কে ক্লিক করা
        print("🤖 Clicking on a link/button...")
        element = driver.find_element(By.TAG_NAME, "a")
        element.click()
        time.sleep(3)
        
        print("\n🎉 Automation completed successfully!")
        
    except Exception as e:
        print(f"Error during automation: {e}")
        
    finally:
        # কাজ শেষে ব্রাউজার বন্ধ করে দেওয়া
        print("Closing the browser...")
        driver.quit()

if __name__ == "__main__":
    social_media_automation()
```

### কোডটি কীভাবে শিখবেন?
1. **webdriver_manager:** আগে সেলেনিয়াম ব্যবহারের জন্য ক্রোম ব্রাউজারের সাথে মিলিয়ে আলাদা করে ক্রোম ড্রাইভার (.exe) ডাউনলোড করতে হতো। কিন্তু `ChromeDriverManager().install()` ফাংশনটি স্বয়ংক্রিয়ভাবে আপনার পিসির ব্রাউজারের ভার্সনের সাথে মিলিয়ে ড্রাইভার ডাউনলোড করে নেয়।
2. **By.XPATH / By.NAME:** যেকোনো ওয়েবসাইটে গিয়ে Right Click -> Inspect এ গেলে HTML কোড দেখা যায়। সেলেনিয়াম সেই HTML এর `ID`, `Name` বা `XPath` ধরে নির্দিষ্ট বাটনটি খুঁজে নেয়।
3. **time.sleep():** রোবট খুবই ফাস্ট কাজ করে, কিন্তু মানুষের ব্রাউজার বা ইন্টারনেট স্লো হতে পারে। রোবট যদি পেজ লোড হওয়ার আগেই ক্লিক করে দেয়, তবে কোড এরর (Error) খাবে। তাই `time.sleep()` দিয়ে রোবটকে কিছুটা সময় অপেক্ষা করানো হয়, যা অনেকটা মানুষের মতোই আচরণ তৈরি করে।

> [!WARNING]
> **সতর্কতা:** ফেসবুক, ইন্সটাগ্রাম বা লিংকেডিন (LinkedIn) এর মতো ওয়েবসাইটগুলো বট ব্যবহার করা মোটেও পছন্দ করে না। আপনি যদি খুব দ্রুত হাজার হাজার লাইক বা মেসেজ পাঠান, তবে তারা আপনার অ্যাকাউন্ট ব্যান (Ban) করে দিতে পারে। তাই সেলেনিয়াম ব্যবহার করার সময় এর টাইম ডিলে (Time Delay) মানুষের মতো স্বাভাবিক রাখার চেষ্টা করবেন।
