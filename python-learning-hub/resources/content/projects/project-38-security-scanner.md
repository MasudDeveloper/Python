# ৩৮. বেসিক সিকিউরিটি স্ক্যানার ও বাগ বাউন্টি থিওরি (Security Header Scanner)

সাইবার সিকিউরিটিতে "বাগ বাউন্টি" (Bug Bounty) একটি অত্যন্ত জনপ্রিয় পেশা। বড় বড় কোম্পানিগুলো (যেমন: গুগল, ফেসবুক) হ্যাকারদের আমন্ত্রণ জানায় তাদের ওয়েবসাইটের ভুল বা সিকিউরিটি বাগ (Bug) খুঁজে বের করার জন্য। বাগ খুঁজে পেলে কোম্পানিগুলো তাদেরকে পুরস্কার (Bounty) বা টাকা দেয়। 

এই প্রজেক্টে আমরা একটি বেসিক সিকিউরিটি স্ক্যানার তৈরি করবো, যা কোনো ওয়েবসাইটের সিকিউরিটি হেডারগুলো (Security Headers) ঠিকমতো কনফিগার করা আছে কি না, তা স্ক্যান করে বের করবে। এটি সম্পূর্ণ নিরাপদ এবং এথিক্যাল একটি কাজ। 

*(বিঃদ্রঃ আমরা এখানে কোনো ম্যালিশিয়াস পেলোড বা SQL Injection/XSS অ্যাটাক স্ক্রিপ্ট তৈরি করবো না, কারণ কারো অনুমতি ছাড়া কোনো ওয়েবসাইটে অ্যাটাক চালানো বেআইনি। আমরা শুধু ডিফেন্সিভ স্ক্যানিং শিখবো।)*

### কীভাবে কাজ করে? (How it works):
1. **HTTP Requests:** পাইথনের `requests` লাইব্রেরি ব্যবহার করে আমরা টার্গেট ওয়েবসাইটে একটি রিকোয়েস্ট পাঠাবো।
2. **Header Analysis:** ওয়েবসাইট থেকে যে রেসপন্স (Response) আসবে, তার হেডারগুলো (Headers) আমরা চেক করবো। 
3. **Missing Headers:** একটি সুরক্ষিত ওয়েবসাইটে কিছু নির্দিষ্ট হেডার থাকা বাধ্যতামূলক (যেমন: `Content-Security-Policy`, `X-Frame-Options`)। আমাদের স্ক্যানার চেক করবে এই হেডারগুলো মিসিং আছে কি না এবং থাকলে ওয়ার্নিং দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:

```bash
pip install requests
```

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে রান করুন। আপনি যেকোনো ওয়েবসাইটের লিংক (যেমন: `https://example.com`) ইনপুট দিলে এটি স্ক্যান করে রিপোর্ট দিবে।

```python
import requests

def security_header_scanner():
    print("=== Basic Security Header Scanner ===")
    url = input("Enter website URL (e.g., https://example.com): ").strip()
    
    # URL এর শুরুতে http/https না থাকলে যোগ করে দেওয়া
    if not url.startswith("http://") and not url.startswith("https://"):
        url = "https://" + url

    # যে সিকিউরিটি হেডারগুলো আমরা চেক করবো
    important_headers = {
        "Strict-Transport-Security": "Protects against man-in-the-middle attacks (HTTPS enforcement).",
        "Content-Security-Policy": "Protects against Cross-Site Scripting (XSS) attacks.",
        "X-Frame-Options": "Protects against Clickjacking attacks.",
        "X-Content-Type-Options": "Protects against MIME-sniffing vulnerabilities."
    }

    try:
        print(f"\n[+] Scanning {url}...")
        # ওয়েবসাইটে রিকোয়েস্ট পাঠানো
        response = requests.get(url, timeout=10)
        headers = response.headers
        
        print("\n=== Scan Results ===")
        missing_count = 0
        
        # হেডারগুলো চেক করা
        for header, description in important_headers.items():
            if header in headers:
                print(f"✅ {header}: FOUND")
            else:
                print(f"❌ {header}: MISSING")
                print(f"   ↳ Risk: {description}")
                missing_count += 1
                
        print("-" * 40)
        if missing_count == 0:
            print("Excellent! The website has all basic security headers.")
        else:
            print(f"Warning: {missing_count} security header(s) are missing. The site might be vulnerable.")
            
    except requests.exceptions.RequestException as e:
        print(f"[-] Error connecting to {url}: {e}")

if __name__ == "__main__":
    security_header_scanner()
```

### কোডটি কীভাবে শিখবেন?
1. **HTTP Headers:** যখন আমরা কোনো ওয়েবসাইটে ঢুকি, তখন ব্রাউজার এবং সার্ভারের মধ্যে কিছু অদৃশ্য ডেটা আদান-প্রদান হয়, যাকে হেডার বলে। এটিতে ওয়েবসাইটের সিকিউরিটি রুলস লেখা থাকে।
2. **X-Frame-Options:** এই হেডারটি না থাকলে হ্যাকাররা আপনার ওয়েবসাইটকে অন্য একটি ওয়েবসাইটের ভেতরে (Iframe দিয়ে) লোড করিয়ে ইউজারদের বোকা বানাতে পারে (যাকে Clickjacking বলে)।
3. **Content-Security-Policy (CSP):** এটি XSS (Cross-Site Scripting) আটকাতে সাহায্য করে। CSP না থাকলে হ্যাকার ওয়েবসাইটে ক্ষতিকর জাভাস্ক্রিপ্ট রান করাতে পারে। 
4. **requests.exceptions:** যদি ইউজার কোনো ভুল ইউআরএল দেয় বা ওয়েবসাইটটি ডাউন থাকে, তবে প্রোগ্রাম যেন ক্র্যাশ না করে সেজন্য আমরা `try-except` ব্লক ব্যবহার করে এক্সেপশন হ্যান্ডেলিং করেছি।
