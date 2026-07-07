# Socket (Zero to Hero) কমপ্লিট গাইড

আপনি যখন ব্রাউজারে `youtube.com` লেখেন, তখন ব্যাকগ্রাউন্ডে আপনার কম্পিউটার ইউটিউবের সার্ভারের সাথে একটি গোপন পাইপ বা টানেল তৈরি করে, যার ভেতর দিয়ে ডেটা আদান-প্রদান হয়। এই পাইপটিকেই কম্পিউটার সায়েন্সের ভাষায় বলা হয় **Socket (সকেট)**!

`requests` বা `Django` এর মতো লাইব্রেরিগুলো হাই-লেভেলের, এরা আপনার হয়ে সকেটের কাজগুলো ব্যাকগ্রাউন্ডে করে দেয়। কিন্তু আপনি যদি একদম কোর (Core) লেভেলে নেটওয়ার্কিং শিখতে চান, হ্যাকিং টুলস বানাতে চান বা লাইভ চ্যাট অ্যাপ্লিকেশন বানাতে চান—তবে পাইথনের বিল্ট-ইন **`socket`** মডিউলটির কোনো বিকল্প নেই।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের TCP Server এবং Client অ্যাপ্লিকেশন তৈরি করা বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. সকেট কী এবং কীভাবে কাজ করে?
একটি সকেট মূলত দুটি জিনিসের সমন্বয়ে তৈরি হয়: **IP Address** (কোথায় পাঠাবো) এবং **Port Number** (কোন দরজায় পাঠাবো)। 
যেমন: `192.168.1.5 : 80` (এখানে 80 হলো ওয়েব সার্ভারের স্ট্যান্ডার্ড পোর্ট)।

### ২. ডোমেইন থেকে IP Address বের করা
```python
import socket

domain = "www.google.com"

# ডোমেইন নেম থেকে তার আসল IP Address বের করা (DNS Lookup)
ip_address = socket.gethostbyname(domain)

print(f"{domain} এর আসল IP Address হলো: {ip_address}")
```

### ৩. একটি বেসিক সকেট তৈরি করা
```python
import socket

# ১. সকেট তৈরি করা
# AF_INET মানে হলো আমরা IPv4 অ্যাড্রেস ব্যবহার করবো (যেমন 192.168.0.1)
# SOCK_STREAM মানে হলো আমরা TCP প্রোটোকল ব্যবহার করবো (যা নির্ভরযোগ্য)
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

print("Socket successfully created!")

# কাজ শেষে সকেট বন্ধ করে দেওয়া উচিত
s.close()
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

সকেট দিয়ে মূলত দুটি প্রোগ্রাম তৈরি করা হয়: একটি **Server (সার্ভার)**, যে সারাদিন বসে বসে কানেকশনের জন্য অপেক্ষা করে। এবং একটি **Client (ক্লায়েন্ট)**, যে সার্ভারকে রিকোয়েস্ট পাঠায়।

### ৪. বেসিক TCP Server তৈরি করা
প্রথমে আমরা এমন একটি সার্ভার বানাবো, যে চালু হয়ে অন্য কম্পিউটারের রিকোয়েস্টের জন্য অপেক্ষা করবে।

```python
import socket

def start_server():
    # ১. সকেট তৈরি
    server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    
    # ২. IP এবং Port এর সাথে সকেটকে বাইন্ড (Bind) বা যুক্ত করা
    # 'localhost' মানে শুধু এই কম্পিউটার থেকেই কানেক্ট করা যাবে
    server_socket.bind(('localhost', 9999))
    
    # ৩. কানেকশনের জন্য অপেক্ষা করা (Listen)
    # 5 মানে হলো একসাথে সর্বোচ্চ ৫ জন লাইনে দাঁড়াতে পারবে
    server_socket.listen(5)
    print("Server is listening on port 9999...")
    
    while True:
        # ৪. কেউ কানেক্ট করলে তার রিকোয়েস্ট অ্যাকসেপ্ট (Accept) করা
        # এটি ক্লায়েন্টের সকেট এবং তার IP অ্যাড্রেস রিটার্ন করবে
        client_socket, address = server_socket.accept()
        print(f"Connection established with {address}!")
        
        # ৫. ক্লায়েন্টকে ওয়েলকাম মেসেজ পাঠানো (মেসেজ অবশ্যই byte এ রূপান্তর করতে হবে)
        message = "Welcome to the Python Socket Server!"
        client_socket.send(message.encode('utf-8'))
        
        # ৬. কাজ শেষে ক্লায়েন্টের কানেকশনটি কেটে দেওয়া
        client_socket.close()

# start_server() # এটি রান করলে সার্ভার অনন্তকাল চলতে থাকবে!
```

### ৫. বেসিক TCP Client তৈরি করা
উপরের সার্ভারটি রান করার পর, এবার আমরা একটি ক্লায়েন্ট কোড লিখবো যা ওই সার্ভারের সাথে কানেক্ট করবে।

```python
import socket

def start_client():
    # ১. ক্লায়েন্ট সকেট তৈরি
    client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    
    try:
        # ২. সার্ভারের IP এবং Port এ কানেক্ট (Connect) করা
        client_socket.connect(('localhost', 9999))
        
        # ৩. সার্ভার থেকে ডেটা রিসিভ (Receive) করা
        # 1024 হলো বাফার সাইজ (অর্থাৎ একসাথে সর্বোচ্চ 1024 বাইট ডেটা রিসিভ করবে)
        response = client_socket.recv(1024)
        
        # ৪. বাইট ডেটাকে ডিকোড করে স্ট্রিংয়ে রূপান্তর করা
        print("Server says:", response.decode('utf-8'))
        
    except ConnectionRefusedError:
        print("Error: The server is not running!")
    finally:
        client_socket.close()

# start_client() # এটি রান করলে সার্ভারের সাথে কানেক্ট হয়ে মেসেজ প্রিন্ট করবে
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. Timeout সেট করা
সার্ভারের সাথে কানেক্ট হতে গিয়ে অনেক সময় আটকে যায়। এই হ্যাং (Hang) হওয়া থেকে বাঁচার জন্য `settimeout` ব্যবহার করা হয়।

```python
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# ৩ সেকেন্ডের মধ্যে কানেক্ট হতে না পারলে এরর দিবে
s.settimeout(3.0)

try:
    # একটি ভুল বা বন্ধ থাকা পোর্টে কানেক্ট করার চেষ্টা
    s.connect(('localhost', 8080))
except socket.timeout:
    print("Error: Connection Timeout! Server took too long.")
except socket.error as e:
    print("Socket Error:", e)
finally:
    s.close()
```

### ৭. Non-blocking Sockets (অ্যাডভান্সড আর্কিটেকচার)
সাধারণ সকেটগুলো ব্লকিং (Blocking) হয়। অর্থাৎ `accept()` বা `recv()` কল করলে ডেটা না আসা পর্যন্ত পুরো পাইথন প্রোগ্রাম ওই এক লাইনেই ফ্রিজ বা আটকে থাকে। 

হাই-পারফরম্যান্স সার্ভারে (যেমন Node.js বা Nginx) সকেটকে নন-ব্লকিং (Non-blocking) করে দেওয়া হয়। এতে ডেটা না থাকলেও প্রোগ্রাম ফ্রিজ হয় না, বরং সে অন্য কাজ করতে থাকে!

```python
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# সকেটকে নন-ব্লকিং মুডে সেট করা
s.setblocking(False)

s.bind(('localhost', 9998))
s.listen(1)

try:
    # ডেটা না থাকলে এটি ফ্রিজ না হয়ে সাথে সাথে 'BlockingIOError' দিবে!
    client, addr = s.accept()
except BlockingIOError:
    print("No connections right now. Doing some other useful work...")
```
*(এই নন-ব্লকিং সকেটের কনসেপ্টের ওপরে ভিত্তি করেই পাইথনের `asyncio` ফ্রেমওয়ার্ক তৈরি করা হয়েছে!)*

### সারসংক্ষেপ (Conclusion)
ইন্টারনেটের জগতে সমস্ত হ্যাকিং, সাইবার সিকিউরিটি টুলস (যেমন Port Scanner বা Reverse Shell), মাল্টিপ্লেয়ার গেমের সার্ভার বা ভিডিও স্ট্রিমিং অ্যাপ—সবকিছুর একদম মূলে কাজ করে এই **`socket`** প্রোটোকল। এটি হয়তো প্রতিদিনের ওয়েব ডেভেলপমেন্টে সরাসরি লাগে না, কিন্তু একজন সত্যিকারের সফটওয়্যার ইঞ্জিনিয়ার হতে হলে সকেটের জ্ঞান থাকা অপরিহার্য!
