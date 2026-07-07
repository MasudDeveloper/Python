# ৫৩. রিয়েল-টাইম নেটওয়ার্ক প্যাকেট স্নিফার (Network Packet Sniffer)

আপনার ল্যাপটপ বা কম্পিউটার যখন ইন্টারনেটের সাথে যুক্ত থাকে, তখন প্রতি সেকেন্ডে হাজার হাজার ডেটা 'প্যাকেট' (Packet) আকারে রাউটারে আসা-যাওয়া করে। হ্যাকাররা অনেক সময় পাবলিক ওয়াইফাইতে বসে এই প্যাকেটগুলো মনিটর করে ইউজারদের ডেটা চুরি করে, যাকে প্যাকেট স্নিফিং (Packet Sniffing) বলা হয়।

সাইবার সিকিউরিটি শেখার জন্য এই প্রজেক্টটি খুবই গুরুত্বপূর্ণ! আমরা পাইথনের **Scapy** লাইব্রেরি ব্যবহার করে একটি নেটওয়ার্ক মনিটরিং টুল বানাবো, যা আমাদের নিজেদের কম্পিউটারে আসা-যাওয়া করা ডেটা প্যাকেটগুলোকে অ্যানালাইসিস করতে সাহায্য করবে।

*(বিঃদ্রঃ এই প্রজেক্টটি সম্পূর্ণ শিক্ষামূলক। অন্যের নেটওয়ার্কে অনুমতি ছাড়া প্যাকেট স্নিফিং করা একটি সাইবার ক্রাইম। আমরা শুধু নিজেদের নেটওয়ার্কে সিকিউরিটি অ্যানালাইসিস শিখবো।)*

### কীভাবে কাজ করে? (How it works):
1. **Network Interface:** `Scapy` লাইব্রেরি আমাদের কম্পিউটারের নেটওয়ার্ক কার্ড বা ইন্টারফেসের (যেমন: Wi-Fi বা Ethernet) সাথে কানেক্ট করবে।
2. **Packet Capturing:** ইন্টারনেটে যাওয়া প্রতিটি ডেটা প্যাকেটকে (HTTP, TCP, UDP) এটি রিয়েল-টাইমে ধরে ফেলবে।
3. **Analysis:** প্যাকেটটি কোন আইপি (IP) অ্যাড্রেস থেকে আসছে, কোন ওয়েবসাইটে যাচ্ছে এবং ভেতরে কী ধরনের ডেটা আছে, তা আমরা টার্মিনালে দেখতে পাবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install scapy
```
*(উইন্ডোজে Scapy চালানোর জন্য Npcap ইনস্টল করা থাকতে পারে, যা Wireshark এর সাথে আসে।)*

### প্রজেক্টের কোড:
নিচের কোডটি একটি বেসিক প্যাকেট স্নিফারের স্ট্রাকচার, যা নেটওয়ার্কের TCP/IP প্যাকেটগুলো ক্যাপচার করবে।

```python
from scapy.all import sniff, IP, TCP, Raw

def process_packet(packet):
    """প্রতিটি প্যাকেট ক্যাপচার করার পর এই ফাংশনটি কল হবে"""
    
    # আমরা শুধু IP প্যাকেটগুলো অ্যানালাইসিস করবো
    if packet.haslayer(IP):
        source_ip = packet[IP].src
        destination_ip = packet[IP].dst
        
        # আমরা শুধু TCP প্যাকেট (যেমন ওয়েবসাইট ব্রাউজিং) দেখতে চাই
        if packet.haslayer(TCP):
            source_port = packet[TCP].sport
            destination_port = packet[TCP].dport
            
            print("-" * 50)
            print(f"[+] TCP Packet: {source_ip}:{source_port} --> {destination_ip}:{destination_port}")
            
            # যদি প্যাকেটের ভেতর কোনো কাঁচা ডেটা (Raw Payload) থাকে
            if packet.haslayer(Raw):
                payload = packet[Raw].load
                
                # আমরা চেক করবো যে ডেটার ভেতর HTTP বা কোনো গুরুত্বপূর্ণ টেক্সট আছে কি না
                try:
                    text_data = payload.decode('utf-8', 'ignore')
                    if "HTTP" in text_data or "GET" in text_data or "POST" in text_data:
                        print(f"[*] HTTP Data Detected: \n{text_data[:150]}...") # প্রথম ১৫০ অক্ষর প্রিন্ট করা
                except Exception:
                    pass

def network_sniffer():
    print("=== Real-time Network Packet Sniffer ===")
    print("Listening for incoming/outgoing packets on your network...\n")
    print("Press CTRL+C to stop the sniffer.")
    
    try:
        # sniff() ফাংশনটি রিয়েল-টাইমে নেটওয়ার্ক প্যাকেট ধরতে থাকে
        # store=False মানে হলো মেমোরিতে প্যাকেট জমিয়ে না রাখা (যাতে র‍্যাম ফুল না হয়)
        # count=10 মানে হলো ১০টি প্যাকেট ক্যাপচার করার পর থেমে যাবে (অসীম করতে count তুলে দিন)
        sniff(prn=process_packet, store=False, filter="tcp", count=10)
    except KeyboardInterrupt:
        print("\nSniffer stopped manually.")
    except Exception as e:
        print(f"\nError starting sniffer: {e}")
        print("Note: You may need Administrator/Root privileges to capture packets.")

if __name__ == "__main__":
    network_sniffer()
```

### কোডটি কীভাবে শিখবেন?
1. **scapy.all.sniff:** এটি `Scapy` লাইব্রেরির সবচেয়ে পাওয়ারফুল ফাংশন। এটি ব্যাকগ্রাউন্ডে চলতে থাকে এবং প্রতিটি প্যাকেট পাওয়ার সাথে সাথে `prn=process_packet` আর্গুমেন্টের মাধ্যমে আমাদের কাস্টম ফাংশনে প্যাকেটটি পাঠিয়ে দেয়।
2. **packet.haslayer(IP):** একটি প্যাকেটের অনেকগুলো লেয়ার (Layer) থাকতে পারে। এটি OSI মডেল ফলো করে। আমরা চেক করছি প্যাকেটটির ভেতরে `IP` লেয়ার আছে কি না, থাকলে আমরা তার সোর্স (Source) এবং ডেস্টিনেশন (Destination) আইপি বের করে নিচ্ছি।
3. **packet.haslayer(Raw):** অনেক প্যাকেটের ভেতরে ইউজারনেম, পাসওয়ার্ড বা ওয়েবসাইটের ডেটা সাধারণ টেক্সট (Plain Text) আকারে যায়, যা `Raw` লেয়ারে থাকে। আমরা সেই কাঁচা ডেটাকে ডিকোড (Decode) করে দেখার চেষ্টা করেছি। (বিঃদ্রঃ HTTPS সাইটগুলোর ডেটা এনক্রিপ্টেড থাকে, তাই সেগুলো প্লেইন টেক্সটে পড়া যায় না, যা সিকিউরিটির জন্য ভালো!)
