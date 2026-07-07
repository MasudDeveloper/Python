## ২৮. লাইভ প্রজেক্ট: নেটওয়ার্ক পোর্ট স্ক্যানার (Network Port Scanner)

সাইবার সিকিউরিটি (Cyber Security) বা এথিক্যাল হ্যাকিংয়ের (Ethical Hacking) দুনিয়ায় সবচেয়ে বেসিক কিন্তু সবচেয়ে গুরুত্বপূর্ণ টুলগুলোর একটি হলো 'পোর্ট স্ক্যানার'। যখন কোনো হ্যাকার কোনো সার্ভারে অ্যাটাক করতে চায়, সে প্রথমে চেক করে ওই সার্ভারের কোন কোন পোর্ট (Port) বা দরজা খোলা আছে। এই প্রজেক্টে আমরা পাইথন দিয়ে ঠিক এমন একটি স্ক্যানার তৈরি করবো যা কোনো আইপি অ্যাড্রেসের (IP Address) সবগুলো পোর্ট স্ক্যান করে বলে দিবে কোনটি খোলা আছে এবং কোনটি বন্ধ।

### কীভাবে কাজ করে? (How it works):
একটি কম্পিউটারে মোট ৬৫,৫৩৫টি পোর্ট থাকে। আমরা পাইথনের `socket` মডিউল ব্যবহার করে নির্দিষ্ট একটি আইপি অ্যাড্রেসের প্রতিটি পোর্টে কানেকশন রিকোয়েস্ট (Connection Request) পাঠাবো। যদি পোর্টটি কানেকশন অ্যাক্সেপ্ট করে, তার মানে সেটি খোলা (Open), আর যদি রিজেক্ট করে, তার মানে সেটি বন্ধ (Closed)। কাজটি দ্রুত করার জন্য আমরা `threading` ব্যবহার করে একসাথে অনেকগুলো পোর্ট স্ক্যান করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের কোনো থার্ড-পার্টি লাইব্রেরি লাগবে না। পাইথনের বিল্ট-ইন লাইব্রেরি দিয়েই আমরা কাজটি করবো।

### প্রজেক্টের কোড:

```python
import socket
import threading
from datetime import datetime

# থ্রেডিংয়ের সময় পোর্টগুলো যেন সিরিয়ালি প্রিন্ট হয়, সেজন্য একটি লক (Lock) তৈরি করা
print_lock = threading.Lock()

def scan_port(ip, port):
    """একটি নির্দিষ্ট পোর্টে কানেক্ট করার চেষ্টা করার ফাংশন"""
    try:
        # সকেট তৈরি করা (IPv4 এবং TCP কানেকশনের জন্য)
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        
        # টাইমআউট সেট করা (যাতে কানেক্ট হতে বেশি সময় না নেয়)
        s.settimeout(1)
        
        # পোর্টে কানেক্ট করার চেষ্টা করা
        result = s.connect_ex((ip, port))
        
        # result 0 হওয়ার মানে হলো কানেকশন সফল হয়েছে, অর্থাৎ পোর্ট খোলা আছে
        if result == 0:
            with print_lock:
                print(f"[*] Port {port} is OPEN")
        
        # সকেটটি বন্ধ করে দেওয়া
        s.close()
        
    except Exception as e:
        pass

def start_scan(target_ip, start_port, end_port):
    """স্ক্যানিং প্রক্রিয়া শুরু করার ফাংশন"""
    print("-" * 50)
    print(f"Scanning Target IP: {target_ip}")
    print(f"Time Started: {str(datetime.now())}")
    print("-" * 50)
    
    threads = []
    
    # নির্দিষ্ট রেঞ্জের সবগুলো পোর্ট স্ক্যান করা
    for port in range(start_port, end_port + 1):
        # থ্রেড (Thread) তৈরি করা যাতে একসাথে অনেকগুলো পোর্ট স্ক্যান করা যায়
        t = threading.Thread(target=scan_port, args=(target_ip, port))
        threads.append(t)
        t.start()
        
    # সবগুলো থ্রেড শেষ হওয়া পর্যন্ত অপেক্ষা করা
    for t in threads:
        t.join()
        
    print("-" * 50)
    print("Scanning Completed.")

if __name__ == "__main__":
    print("=== Python Port Scanner ===")
    
    # ইউজারের কাছ থেকে টার্গেট আইপি নেওয়া
    # উদাহরণ: 127.0.0.1 (নিজের কম্পিউটার) অথবা 8.8.8.8 (গুগলের ডিএনএস)
    target = input("Enter Target IP Address (e.g., 127.0.0.1): ")
    
    try:
        # আইপি অ্যাড্রেসটি ভ্যালিড কি না তা চেক করা
        target_ip = socket.gethostbyname(target)
        
        # কত থেকে কত পোর্ট স্ক্যান করতে চান (সাধারণত 1 থেকে 1024 এর মধ্যেই কমন পোর্টগুলো থাকে)
        print("Enter Port Range (e.g., 1 to 1024)")
        start_p = int(input("Start Port: "))
        end_p = int(input("End Port: "))
        
        start_scan(target_ip, start_p, end_p)
        
    except socket.gaierror:
        print("\nHostname could not be resolved. Please enter a valid IP or domain.")
    except KeyboardInterrupt:
        print("\nExiting Program.")
```

> [!WARNING]
> **সতর্কতা:** এই স্ক্রিপ্টটি শুধুমাত্র এথিক্যাল বা শিক্ষামূলক কাজে ব্যবহার করবেন। অনুমতি ছাড়া অন্য কারও কম্পিউটার বা ওয়েবসাইটে পোর্ট স্ক্যান করা বেআইনি! প্র্যাকটিস করার জন্য `127.0.0.1` (আপনার নিজের পিসি) অথবা `scanme.nmap.org` ব্যবহার করতে পারেন।

### কোডটি কীভাবে শিখবেন?
1. **Socket Programming:** পাইথনের `socket` মডিউল ব্যবহার করে নেটওয়ার্কের লেভেলে (TCP/IP) কীভাবে একটি সার্ভারের সাথে সরাসরি কানেকশন তৈরি করতে হয় (`socket.connect_ex`) তা শিখতে পারবেন।
2. **Threading:** ৬৫ হাজার পোর্টের জন্য যদি আপনি একটির পর একটি চেক করতে যান, তাহলে সারাদিন লেগে যাবে। `threading.Thread` ব্যবহার করে কীভাবে একসাথে হাজারটা কাজ প্যারালালি (Parallelly) করা যায়, যা আপনার প্রোগ্রামের স্পিড ১০০ গুণ বাড়িয়ে দেয়, তা বুঝতে পারবেন।
3. **Error Handling:** নেটওয়ার্কিংয়ের কাজ করার সময় প্রায়ই কানেকশন লস্ট বা আইপি ভুল হওয়ার মতো এরর আসে। `try-except` ব্লক ব্যবহার করে সেগুলোকে কীভাবে হ্যান্ডেল করতে হয় তার আইডিয়া পাবেন।

---