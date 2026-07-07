## ১৭. লাইভ প্রজেক্ট: পাসওয়ার্ড ম্যানেজার উইথ এনক্রিপশন (Password Manager)

সাইবার সিকিউরিটির যুগে আমাদের প্রচুর পাসওয়ার্ড মনে রাখতে হয়। এই প্রজেক্টটিতে আমরা একটি সিকিউর পাসওয়ার্ড ম্যানেজার তৈরি করবো যা আপনার সব পাসওয়ার্ড একটি ফাইলে সেভ করে রাখবে। কিন্তু মজার ব্যাপার হলো, কেউ যদি ফাইলটি ওপেনও করে, সে পাসওয়ার্ডগুলো পড়তে পারবে না কারণ সেগুলো এনক্রিপ্ট (Encrypt) বা লক করা থাকবে!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের একটি সাইবার সিকিউরিটি লাইব্রেরি লাগবে:
1. **cryptography:** ডেটা এনক্রিপ্ট (লক) এবং ডিক্রিপ্ট (আনলক) করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install cryptography
```

### প্রজেক্টের কোড:

এই প্রোগ্রামে একটি মাস্টার "Key" তৈরি করা হবে, যা দিয়ে পাসওয়ার্ডগুলো লক এবং আনলক করা হবে। তাই ওই 'Key' ফাইলটি সবচেয়ে নিরাপদে রাখতে হবে।

```python
from cryptography.fernet import Fernet
import os

# প্রথমবার রান করার সময় একটি Secret Key তৈরি করার ফাংশন
def write_key():
    key = Fernet.generate_key()
    with open("key.key", "wb") as key_file:
        key_file.write(key)

# তৈরি হওয়া Secret Key টি লোড করার ফাংশন
def load_key():
    # যদি key ফাইল না থাকে, তাহলে নতুন করে তৈরি করবে
    if not os.path.exists("key.key"):
        write_key()
    with open("key.key", "rb") as key_file:
        key = key_file.read()
    return key

# Secret Key লোড করা হচ্ছে
key = load_key()
fer = Fernet(key)

def view_passwords():
    """সেভ করা পাসওয়ার্ডগুলো ডিক্রিপ্ট করে দেখার ফাংশন"""
    if not os.path.exists("passwords.txt"):
        print("No passwords saved yet!\n")
        return
        
    print("\n--- Saved Passwords ---")
    with open("passwords.txt", "r") as f:
        for line in f.readlines():
            data = line.rstrip() # লাইনের শেষের স্পেস বাদ দেওয়া
            if "|" in data:
                user, passw = data.split("|")
                # পাসওয়ার্ড আনলক (Decrypt) করা হচ্ছে
                decrypted_pass = fer.decrypt(passw.encode()).decode()
                print(f"User/Email: {user} => Password: {decrypted_pass}")
    print("-----------------------\n")

def add_password():
    """নতুন পাসওয়ার্ড এনক্রিপ্ট করে সেভ করার ফাংশন"""
    name = input("Account Name (e.g., Facebook, Gmail): ")
    pwd = input("Password: ")

    with open("passwords.txt", "a") as f:
        # পাসওয়ার্ড লক (Encrypt) করা হচ্ছে
        encrypted_pass = fer.encrypt(pwd.encode()).decode()
        f.write(name + "|" + encrypted_pass + "\n")
    print("Password added securely!\n")

# মূল মেনু
while True:
    print("1. Add a new password")
    print("2. View existing passwords")
    print("3. Quit")
    mode = input("Would you like to add a new password or view existing ones? (1/2/3): ")

    if mode == "3":
        print("Exiting Password Manager. Stay Safe!")
        break
    elif mode == "2":
        view_passwords()
    elif mode == "1":
        add_password()
    else:
        print("Invalid Mode. Please try again.\n")
```

> [!WARNING] 
> **বিঃদ্রঃ** কোডটি রান করার পর প্রজেক্ট ফোল্ডারে `key.key` নামে একটি ফাইল তৈরি হবে। এই ফাইলটি ডিলিট হয়ে গেলে আপনি আপনার `passwords.txt` ফাইলের কোনো পাসওয়ার্ড আর আনলক বা রিকভার করতে পারবেন মহাশয়। তাই এটি খুবই সাবধানে রাখতে হবে।

---