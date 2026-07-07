# ৪৮. অ্যান্ড-টু-অ্যান্ড এনক্রিপ্টেড চ্যাট অ্যাপ (End-to-End Encrypted Chat)

হোয়াটসঅ্যাপ (WhatsApp) বা সিগন্যাল (Signal) অ্যাপ ওপেন করলে লেখা থাকে "Messages are end-to-end encrypted"। এর মানে হলো, আপনি এবং আপনার বন্ধু ছাড়া মাঝখানে ইন্টারনেট প্রোভাইডার, হ্যাকার বা খোদ হোয়াটসঅ্যাপ কোম্পানিও আপনাদের মেসেজ পড়তে পারে না! 

এই প্রজেক্টে আমরা **Socket Programming** এবং **RSA Cryptography** ব্যবহার করে পাইথনে ঠিক সেরকমই একটি সিকিউরড চ্যাট অ্যাপ্লিকেশন তৈরি করবো।

### কীভাবে কাজ করে? (How it works):
1. **Public/Private Keys:** `RSA` অ্যালগরিদমের মাধ্যমে দুজন ইউজারের জন্য দুটি চাবি তৈরি হবে। Public Key সবার জন্য উন্মুক্ত (লক করার জন্য), আর Private Key নিজের কাছে থাকবে (আনলক করার জন্য)।
2. **Encryption:** আপনি যখন মেসেজ পাঠাবেন, তখন সেটি আপনার বন্ধুর Public Key দিয়ে লক (Encrypt) হয়ে হিজিবিজি টেক্সট বা সাইফারটেক্সটে (Ciphertext) পরিণত হবে।
3. **Decryption:** ওই মেসেজটি শুধুমাত্র আপনার বন্ধু তার নিজের Private Key দিয়েই আনলক (Decrypt) করতে পারবে, অন্য কেউ নয়!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install rsa
```

### প্রজেক্টের কোড:
নিচে ক্লায়েন্ট এবং সার্ভার মিলিয়ে চ্যাটিংয়ের মূল এনক্রিপশন লজিকটি দেওয়া হলো:

```python
import rsa

def encrypted_chat_demo():
    print("=== End-to-End Encrypted Chat System ===\n")
    
    print("[1] Generating RSA Keys for Alice and Bob...")
    # অ্যালিসের জন্য পাবলিক ও প্রাইভেট কি তৈরি (১০২৪ বিট সাইজের)
    alice_public_key, alice_private_key = rsa.newkeys(1024)
    
    # ববের জন্য পাবলিক ও প্রাইভেট কি তৈরি
    bob_public_key, bob_private_key = rsa.newkeys(1024)
    
    print("✅ Keys successfully generated!\n")
    
    # অ্যালিস ববকে একটি মেসেজ পাঠাতে চায়
    original_message = "Hi Bob! This is a secret message. Meet me at 5 PM."
    print(f"👤 Alice writes: {original_message}")
    
    # অ্যালিস ববের পাবলিক কি দিয়ে মেসেজটি এনক্রিপ্ট করবে
    print("\n[2] Encrypting the message with Bob's Public Key...")
    encrypted_message = rsa.encrypt(original_message.encode(), bob_public_key)
    
    print(f"🔒 Encrypted Data (What hackers see on the internet):\n{encrypted_message}\n")
    
    # বব মেসেজটি রিসিভ করলো এবং তার প্রাইভেট কি দিয়ে ডিক্রিপ্ট করবে
    print("[3] Bob receives the data and decrypts it with his Private Key...")
    try:
        decrypted_message = rsa.decrypt(encrypted_message, bob_private_key).decode()
        print(f"👤 Bob reads: {decrypted_message}")
    except rsa.pkcs1.DecryptionError:
        print("❌ Decryption failed! Someone tampered with the message or used the wrong key.")
        
    # আসুন হ্যাকারকে দিয়ে আনলক করার চেষ্টা করি!
    print("\n[!] Hacker tries to decrypt the message using Alice's Private Key...")
    try:
        rsa.decrypt(encrypted_message, alice_private_key).decode()
    except rsa.pkcs1.DecryptionError:
        print("🚫 HACKER FAILED! RSA Decryption Error: Only Bob's Private Key can unlock it!")

if __name__ == "__main__":
    encrypted_chat_demo()
```

### কোডটি কীভাবে শিখবেন?
1. **Asymmetric Encryption (RSA):** সাধারণ পাসওয়ার্ডে লক এবং আনলক করার পাসওয়ার্ড একই থাকে (Symmetric)। কিন্তু RSA তে লক করার জন্য আলাদা চাবি (Public Key) এবং আনলক করার জন্য আলাদা চাবি (Private Key) থাকে। একে বলা হয় Asymmetric Encryption।
2. **rsa.encrypt():** মেসেজ পাঠানোর আগে টেক্সটটিকে `.encode()` করে বাইনারিতে রূপান্তর করা হয়। তারপর রিসিভারের (Bob) পাবলিক কি দিয়ে এটিকে এনক্রিপ্ট করা হয়।
3. **Security Logic:** যদি হ্যাকার ইন্টারনেটের মাঝপথে (Man-in-the-Middle) ডেটা চুরিও করে, তবে সে শুধু এনক্রিপ্টেড বাইনারি ডেটাই পাবে। যতক্ষণ না তার কাছে ববের প্রাইভেট কি (Private Key) থাকছে, ততক্ষণ সে কোনোভাবেই মেসেজটি পড়তে পারবে না। এটাই হলো অ্যান্ড-টু-অ্যান্ড এনক্রিপশনের মূল শক্তি!
