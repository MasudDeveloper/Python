# ৩৬. পিয়ার-টু-পিয়ার ফাইল শেয়ারিং নেটওয়ার্ক (Build your own Torrent)

টরেন্ট (Torrent) কীভাবে কাজ করে তা কি ভেবে দেখেছেন? টরেন্টে কোনো নির্দিষ্ট সেন্ট্রাল সার্ভার (যেমন গুগল ড্রাইভ বা ড্রপবক্স) থাকে না। সেখানে ইউজাররা সরাসরি একে অপরের (Peer-to-Peer) কম্পিউটার থেকে ফাইল আদান-প্রদান করে। 

এই প্রজেক্টে আমরা পাইথনের **Socket Programming** ব্যবহার করে এমন একটি সিস্টেম বানাবো, যেখানে দুজন ইউজার (ধরা যাক আপনি এবং আপনার বন্ধু) কোনো থার্ড-পার্টি সার্ভার ছাড়াই সরাসরি নিজেদের মধ্যে বিশাল সাইজের ফাইল শেয়ার করতে পারবেন! এটি নেটওয়ার্কিং শেখার জন্য একটি দারুণ প্রজেক্ট।

### কীভাবে কাজ করে? (How it works):
1. **Socket Connection:** প্রথমে আমরা `TCP Socket` ব্যবহার করে দুটি কম্পিউটারের মধ্যে একটি ডিরেক্ট কানেকশন তৈরি করবো। 
2. **File Chunking:** বড় ফাইল একবারে পাঠানো সম্ভব নয়, তাই ফাইলটিকে ছোট ছোট খণ্ডে (Chunks) ভাগ করে বাফার (Buffer) করে পাঠানো হবে। 
3. **Peer-to-Peer (P2P):** এখানে প্রতিটি কম্পিউটারই একই সাথে সার্ভার (ডেটা সেন্ড করার জন্য) এবং ক্লায়েন্ট (ডেটা রিসিভ করার জন্য) হিসেবে কাজ করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য কোনো এক্সটার্নাল লাইব্রেরি লাগবে না। আমরা পাইথনের বিল্ট-ইন `socket`, `threading` এবং `os` মডিউল ব্যবহার করবো।

### প্রজেক্টের কোড:

এই কোডটি মূলত দুটি মোডে কাজ করে: **Sender** (যে ফাইল পাঠাবে) এবং **Receiver** (যে ফাইল রিসিভ করবে)।

```python
import socket
import os

# ফাইলের খণ্ডের সাইজ (4 KB করে পাঠানো হবে)
BUFFER_SIZE = 4096 
SEPARATOR = "<SEPARATOR>"

def send_file(filename, host, port):
    print(f"Connecting to {host}:{port} to send file...")
    
    # ফাইলের সাইজ বের করা
    filesize = os.path.getsize(filename)
    
    # সকেট তৈরি এবং কানেক্ট করা
    s = socket.socket()
    try:
        s.connect((host, port))
        print("[+] Connected successfully.")
    except Exception as e:
        print(f"[-] Connection failed: {e}")
        return

    # ফাইলের নাম এবং সাইজ পাঠানো
    s.send(f"{filename}{SEPARATOR}{filesize}".encode())

    # ফাইল রিড করে ছোট ছোট খণ্ডে পাঠানো
    print(f"Sending {filename}...")
    with open(filename, "rb") as f:
        while True:
            # বাফার সাইজ অনুযায়ী ফাইল রিড করা
            bytes_read = f.read(BUFFER_SIZE)
            if not bytes_read:
                # ফাইল শেষ হয়ে গেলে লুপ ব্রেক করা
                break
            # ডেটা সেন্ড করা
            s.sendall(bytes_read)
            
    print("[+] File sent successfully!")
    s.close()

def receive_file(host, port):
    print(f"Listening for incoming files on {host}:{port}...")
    
    # সার্ভার সকেট তৈরি করা
    s = socket.socket()
    s.bind((host, port))
    s.listen(1)
    
    client_socket, address = s.accept()
    print(f"[+] Client connected from {address}")
    
    # ফাইলের নাম এবং সাইজ রিসিভ করা
    received = client_socket.recv(BUFFER_SIZE).decode()
    filename, filesize = received.split(SEPARATOR)
    
    # নতুন ফাইল তৈরি করে ডেটা রাইট করা
    filename = os.path.basename(filename)
    filename = "received_" + filename
    print(f"Receiving {filename} ({filesize} bytes)...")
    
    with open(filename, "wb") as f:
        while True:
            # ডেটা রিসিভ করা
            bytes_read = client_socket.recv(BUFFER_SIZE)
            if not bytes_read:
                break
            # ফাইলে রাইট করা
            f.write(bytes_read)
            
    print(f"[+] File {filename} received and saved successfully!")
    
    client_socket.close()
    s.close()

if __name__ == "__main__":
    print("=== P2P File Sharing ===")
    print("1. Send a file")
    print("2. Receive a file")
    choice = input("Enter your choice (1 or 2): ")
    
    # লোকালহোস্ট ব্যবহার করা হচ্ছে। বাস্তবে এখানে অন্য কম্পিউটারের IP Address দিতে হবে।
    IP_ADDRESS = "127.0.0.1" 
    PORT = 5001
    
    if choice == '1':
        file_to_send = input("Enter the exact filename (with extension) to send: ")
        if os.path.exists(file_to_send):
            send_file(file_to_send, IP_ADDRESS, PORT)
        else:
            print("File not found!")
    elif choice == '2':
        receive_file(IP_ADDRESS, PORT)
    else:
        print("Invalid choice.")
```

### কীভাবে প্র্যাকটিস করবেন? (How to practice):
1. আপনার কম্পিউটারে দুটি আলাদা টার্মিনাল বা কমান্ড প্রম্পট ওপেন করুন।
2. প্রথম টার্মিনালে কোডটি রান করে `2` চাপুন (এটি রিসিভার হিসেবে অপেক্ষা করবে)।
3. দ্বিতীয় টার্মিনালে কোডটি রান করে `1` চাপুন এবং একটি ফাইলের নাম দিন। 
4. দেখবেন সাথে সাথেই প্রথম টার্মিনালে ফাইলটি রিসিভ হয়ে গেছে! বাস্তবে `127.0.0.1` এর বদলে আপনার বন্ধুর কম্পিউটারের আইপি অ্যাড্রেস দিলে পৃথিবীর যেকোনো প্রান্ত থেকে ফাইল ট্রান্সফার করা যাবে।

### কোডটি কীভাবে শিখবেন?
1. **TCP Sockets:** `socket.socket()` দিয়ে আমরা একটি TCP কানেকশন তৈরি করেছি, যা ডেটা লস ছাড়াই ফাইল ট্রান্সফার নিশ্চিত করে।
2. **Buffer/Chunking:** `f.read(BUFFER_SIZE)` এর মাধ্যমে আমরা 4 KB (4096 bytes) করে ডেটা পড়ছি। আপনি যদি ১ জিবি সাইজের ফাইল একবারে র‍্যামে লোড করে পাঠাতে যান, তবে কম্পিউটার হ্যাং করতে পারে। তাই এভাবে ছোট খণ্ডে পাঠানো হয়।
3. **os.path.basename:** এর কাজ হলো ফুল পাথ থেকে শুধু ফাইলের নামটি আলাদা করা। ধরুন আপনি দিলেন `C:/downloads/movie.mp4`, এটি শুধু `movie.mp4` কে রিসিভারের কাছে পাঠাবে।
