## ২৭. লাইভ প্রজেক্ট: অটোমেটেড বাল্ক ইমেইল সেন্ডার (Bulk Email Automation)

ডিজিটাল মার্কেটিং এবং কর্পোরেট জগতে এই প্রজেক্টটির চাহিদা প্রচুর। ধরুন আপনার কোম্পানিতে ১০০০ জন ক্লায়েন্ট আছে এবং আপনি সবাইকে একটি স্পেশাল অফার বা নিউজলেটার পাঠাতে চান। কিন্তু আপনি চান প্রতিটি ইমেইলে তাদের নিজেদের নাম লেখা থাকুক (যেমন: "Hello John", "Hello Sarah")। একটি একটি করে ১০০০ ইমেইল পাঠানো অসম্ভব! এই প্রজেক্টে আমরা এমন একটি পাইথন স্ক্রিপ্ট লিখবো যা একটি এক্সেল ফাইল থেকে সবার নাম ও ইমেইল পড়বে এবং চোখের পলকে সবাইকে কাস্টমাইজড ইমেইল পাঠিয়ে দিবে।

### কীভাবে কাজ করে? (How it works):
আমরা `pandas` লাইব্রেরি দিয়ে প্রথমে এক্সেল (CSV) ফাইলের ডেটাগুলো পড়বো। এরপর পাইথনের বিল্ট-ইন `smtplib` (Simple Mail Transfer Protocol) মডিউল ব্যবহার করে জিমেইলের সার্ভারে লগইন করবো। সবশেষে একটি `for` লুপ চালিয়ে এক্সেল ফাইলের প্রত্যেকটি মানুষের নাম ধরে ধরে ইমেইল সেন্ড করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ডেটা প্রসেসিংয়ের জন্য `pandas` লাইব্রেরিটি লাগবে।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install pandas
```

### প্রজেক্টের কোড:

কোডটি রান করার আগে আপনাকে `contacts.csv` নামে একটি এক্সেল ফাইল তৈরি করতে হবে, যার প্রথম কলামে থাকবে `Name` এবং দ্বিতীয় কলামে থাকবে `Email`।

```python
import smtplib
import pandas as pd
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# জিমেইলে লগইন করার ক্রেডেনশিয়াল
# আপনার জিমেইল অ্যাকাউন্টে 'App Passwords' চালু করে একটি পাসওয়ার্ড তৈরি করতে হবে
# কখনোই আপনার আসল জিমেইল পাসওয়ার্ড এখানে ব্যবহার করবেনশে না!
MY_EMAIL = "your_email@gmail.com"
MY_PASSWORD = "your_app_password" 

def send_bulk_emails():
    try:
        # এক্সেল (CSV) ফাইল থেকে কন্টাক্ট লিস্ট পড়া
        # ফাইলের কলামগুলো হতে হবে: Name, Email
        print("Reading contacts from CSV...")
        data = pd.read_csv("contacts.csv")
        
        # জিমেইলের SMTP সার্ভারের সাথে কানেক্ট করা (পোর্ট 587)
        print("Connecting to Gmail Server...")
        server = smtplib.SMTP("smtp.gmail.com", 587)
        server.starttls() # কানেকশন সিকিউর করার জন্য
        server.login(MY_EMAIL, MY_PASSWORD)
        print("Login Successful!\n")
        
        # লিস্টের প্রত্যেকের জন্য লুপ চালানো
        success_count = 0
        for index, row in data.iterrows():
            name = row['Name']
            recipient_email = row['Email']
            
            # ইমেইলের বডি এবং সাবজেক্ট তৈরি করা
            subject = f"Special Offer for You, {name}!"
            body = f"""
            Hello {name},
            
            We hope you are doing well. 
            We have an exclusive offer just for you. Please check out our website for more details.
            
            Best Regards,
            Python Developer Team
            """
            
            # ইমেইলের ফরম্যাট সাজানো
            msg = MIMEMultipart()
            msg['From'] = MY_EMAIL
            msg['To'] = recipient_email
            msg['Subject'] = subject
            msg.attach(MIMEText(body, 'plain'))
            
            # ইমেইল সেন্ড করা
            server.send_message(msg)
            print(f"Sent email to: {name} ({recipient_email})")
            success_count += 1
            
        # সার্ভার থেকে লগআউট করা
        server.quit()
        print(f"\nSuccessfully sent {success_count} emails!")
        
    except FileNotFoundError:
        print("Error: 'contacts.csv' file not found. Please create one with Name and Email columns.")
    except Exception as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    send_bulk_emails()
```

> [!CAUTION]
> **সতর্কতা:** জিমেইল এখন থার্ড-পার্টি অ্যাপগুলোকে সরাসরি লগইন করতে দেয় না। তাই এই কোডটি কাজ করানোর জন্য আপনাকে আপনার জিমেইল অ্যাকাউন্টের সেটিংসে গিয়ে '2-Step Verification' অন করতে হবে এবং সেখান থেকে **'App Passwords'** তৈরি করে সেই ১৬-ডিজিটের পাসওয়ার্ডটি কোডে ব্যবহার করতে হবে।

### কোডটি কীভাবে শিখবেন?
1. **Pandas Iteration:** `data.iterrows()` ব্যবহার করে কীভাবে একটি এক্সেল ফাইলের হাজার হাজার রো (Row) বা লাইন এক এক করে পড়তে হয়, তা আপনি শিখতে পারবেন।
2. **SMTP Protocol:** পাইথনে `smtplib` মডিউল ব্যবহার করে কীভাবে রিয়েল-ওয়ার্ল্ড ইমেইল সার্ভারের (যেমন জিমেইল বা ইয়াহু) সাথে কানেক্ট করতে হয় এবং সিকিউরভাবে (TLS) ডেটা ট্রান্সফার করতে হয়, তার বেসিক ধারণা পাবেন।
3. **MIME Text Format:** সাধারণ স্ট্রিংয়ের বদলে `MIMEText` এবং `MIMEMultipart` ব্যবহার করে কীভাবে প্রফেশনাল ইমেইল তৈরি করতে হয় (যেখানে Subject, From, To আলাদাভাবে সেট করা থাকে), তা আয়ত্ত করতে পারবেন।

---