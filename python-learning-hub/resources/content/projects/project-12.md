## ১১. লাইভ প্রজেক্ট: প্রাইস ট্র্যাকার বট (Price Tracker Bot)

প্রোগ্রামিংয়ের আসল মজা হলো বাস্তব জীবনের সমস্যা সমাধান করা। চলুন একটি শক্তিশালী প্রজেক্ট বানাই যা ই-কমার্স সাইট থেকে কোনো পণ্যের দাম স্ক্র্যাপ (Scrape) করবে এবং দাম কমলে আপনাকে ইমেইল করে জানিয়ে দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের দুটি এক্সটার্নাল লাইব্রেরি লাগবে:
1. **requests:** ওয়েবসাইটের এইচটিএমএল (HTML) ডেটা আনার জন্য।
2. **BeautifulSoup (bs4):** এইচটিএমএল ডেটা থেকে নির্দিষ্ট লেখা (যেমন পণ্যের দাম) খুঁজে বের করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install requests beautifulsoup4
```

### প্রজেক্টের কোড:
নিচে একটি বেসিক প্রাইস ট্র্যাকার বটের কোড দেওয়া হলো (এখানে আমাজনের একটি পণ্যের ডেমো লিংক ব্যবহার করা হয়েছে):

```python
import requests
from bs4 import BeautifulSoup
import smtplib
import time

# পণ্যের লিংক (যেকোনো ই-কমার্স সাইটের লিংক দিতে পারেন)
URL = 'https://www.amazon.com/dp/B08F7PTF53'

# আপনার ব্রাউজারের User-Agent (গুগলে 'my user agent' লিখে সার্চ করলে এটি পাবেন)
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
}

def check_price():
    # ওয়েবপেইজের ডেটা রিকোয়েস্ট করা
    page = requests.get(URL, headers=headers)
    
    # BeautifulSoup দিয়ে HTML ডেটা পার্স করা
    soup = BeautifulSoup(page.content, "html.parser")
    
    try:
        # পণ্যের নাম এবং দাম খুঁজে বের করা (সাইটের HTML ট্যাগ অনুযায়ী id বা class বদলাতে হতে পারে)
        title = soup.find(id="productTitle").get_text().strip()
        
        # দামের অংশটি খুঁজে বের করা (যেমন: $1,200.00)
        price_str = soup.find(class_="a-price-whole").get_text()
        
        # দাম থেকে কমা (,) বাদ দিয়ে ফ্লোট নাম্বারে কনভার্ট করা
        price = float(price_str.replace(',', '').replace('.', ''))
        
        print(f"Product: {title}")
        print(f"Current Price: ${price}")
        
        # যদি দাম ১০০০ ডলারের নিচে নেমে যায়, তবে ইমেইল সেন্ড করবে
        if price < 1000.0:
            send_email(title, price)
            print("Email Sent!")
            
    except Exception as e:
        print("Error fetching details. Maybe the HTML structure changed or page didn't load properly.", e)

def send_email(product_name, current_price):
    # জিমেইলের SMTP সার্ভার সেটআপ
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.ehlo()
    server.starttls()
    server.ehlo()
    
    # আপনার ইমেইল এবং অ্যাপ পাসওয়ার্ড (App Password)
    sender_email = 'your_email@gmail.com'
    sender_password = 'your_app_password_here'
    
    server.login(sender_email, sender_password)
    
    subject = f"Price Dropped! {product_name}"
    body = f"Good News! The price of {product_name} has dropped to ${current_price}.\nCheck the link: {URL}"
    
    msg = f"Subject: {subject}\n\n{body}"
    
    # ইমেইল পাঠানো
    server.sendmail(
        sender_email, 
        'receiver_email@gmail.com', # যাকে ইমেইল পাঠাবেন
        msg
    )
    print("Hey, email has been sent successfully!")
    server.quit()

# লুপ চালিয়ে প্রতিদিন একবার (৮৬৪০০ সেকেন্ড) চেক করা
while True:
    check_price()
    time.sleep(86400) # ২৪ ঘণ্টা পর পর কোডটি আবার রান করবে
```

> [!WARNING] 
> **বিঃদ্রঃ** ইমেইল পাঠানোর জন্য আপনার জিমেইল অ্যাকাউন্ট থেকে **"App Passwords"** তৈরি করে নিতে হবে। আপনার রেগুলার জিমেইল পাসওয়ার্ড দিয়ে এটি কাজ করবে না। এছাড়া ওয়েবসাইটগুলো মাঝে মাঝেই তাদের এইচটিএমএল (HTML) স্ট্রাকচার পরিবর্তন করে, তাই `soup.find` এর ভেতরের `id` বা `class` প্রয়োজন অনুযায়ী আপডেট করে নিতে হবে।

---