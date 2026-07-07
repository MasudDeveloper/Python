# ৪৩. ব্লকচেইন ইমপ্লিমেন্টেশন (Build your own Blockchain)

বিটকয়েন (Bitcoin) বা ক্রিপ্টোকারেন্সির কথা তো সবাই শুনেছেন, কিন্তু এর মূল প্রযুক্তিটি কী? এর পেছনের ম্যাজিক হলো **ব্লকচেইন (Blockchain)**। এটি মূলত একটি ডিজিটাল লেজার বা খাতা, যেখানে তথ্যগুলো এমনভাবে চেইনের মতো যুক্ত থাকে যে কেউ চাইলেই পেছনের কোনো তথ্য ডিলিট বা পরিবর্তন (Hack) করতে পারে না।

এই প্রজেক্টে আমরা ক্রিপ্টোগ্রাফি ব্যবহার করে পাইথনে একেবারে স্ক্র্যাচ থেকে একটি বেসিক ব্লকচেইন তৈরি করবো। এটি আপনাকে বিটকয়েন কীভাবে কাজ করে, তা বুঝতে ব্যাপকভাবে সাহায্য করবে!

### কীভাবে কাজ করে? (How it works):
1. **Block (ব্লক):** ব্লকচেইনে প্রতিটি ব্লকের ভেতরে কিছু ডেটা (যেমন: কে কাকে কত টাকা পাঠালো), একটি টাইমস্ট্যাম্প এবং একটি ইউনিক 'হ্যাশ' (Hash বা ডিজিটাল ফিঙ্গারপ্রিন্ট) থাকে।
2. **Chain (চেইন):** প্রতিটি ব্লকের সাথে তার আগের ব্লকের হ্যাশ যুক্ত থাকে। ফলে আপনি যদি মাঝখানের কোনো ব্লকের ডেটা পরিবর্তন করার চেষ্টা করেন, তবে তার হ্যাশ বদলে যাবে এবং সাথে সাথে চেইনের বাকি সবগুলো ব্লক ইনভ্যালিড (Invalid) হয়ে যাবে!
3. **Proof of Work (Mining):** নতুন কোনো ব্লক চেইনে যুক্ত করার আগে মাইনিং (Mining) করতে হয়। মাইনিং হলো কম্পিউটারের প্রসেসর ব্যবহার করে একটি জটিল গাণিতিক পাজল সলভ করা।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের কোনো এক্সটার্নাল লাইব্রেরি লাগবে না। আমরা পাইথনের বিল্ট-ইন `hashlib` এবং `time` মডিউল ব্যবহার করবো।

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে রান করুন। এখানে আমরা একটি ব্লকচেইন বানিয়েছি এবং মাইনিংয়ের মাধ্যমে তাতে দুটি ব্লক অ্যাড করেছি।

```python
import hashlib
import time

class Block:
    def __init__(self, index, data, previous_hash):
        self.index = index
        self.timestamp = time.time()
        self.data = data
        self.previous_hash = previous_hash
        self.nonce = 0 # Nonce ব্যবহৃত হয় মাইনিংয়ের সময়
        self.hash = self.calculate_hash()
        
    def calculate_hash(self):
        """SHA-256 অ্যালগরিদম ব্যবহার করে ব্লকের হ্যাশ তৈরি করা"""
        block_string = f"{self.index}{self.timestamp}{self.data}{self.previous_hash}{self.nonce}"
        return hashlib.sha256(block_string.encode()).hexdigest()
        
    def mine_block(self, difficulty):
        """Proof of Work বা মাইনিং করার ফাংশন"""
        target = "0" * difficulty
        while self.hash[:difficulty] != target:
            self.nonce += 1
            self.hash = self.calculate_hash()
        print(f"Block Mined! Hash: {self.hash}")

class Blockchain:
    def __init__(self):
        self.chain = [self.create_genesis_block()]
        self.difficulty = 4 # গাণিতিক পাজলের কঠিনতা (Difficulty Level)
        
    def create_genesis_block(self):
        """ব্লকচেইনের একদম প্রথম ব্লক (Genesis Block) তৈরি করা"""
        return Block(0, "Genesis Block - The Beginning", "0")
        
    def get_latest_block(self):
        """চেইনের সর্বশেষ ব্লকটি পাওয়া"""
        return self.chain[-1]
        
    def add_block(self, new_block):
        """নতুন ব্লক মাইনিং করে চেইনে যুক্ত করা"""
        new_block.previous_hash = self.get_latest_block().hash
        print(f"Mining block {new_block.index}...")
        new_block.mine_block(self.difficulty)
        self.chain.append(new_block)
        
    def is_chain_valid(self):
        """ব্লকচেইনটি হ্যাক হয়েছে কি না তা চেক করা"""
        for i in range(1, len(self.chain)):
            current_block = self.chain[i]
            previous_block = self.chain[i-1]
            
            # যদি বর্তমান ব্লকের ডেটা পরিবর্তন করা হয়
            if current_block.hash != current_block.calculate_hash():
                return False
                
            # যদি চেইন ভেঙে দেওয়া হয়
            if current_block.previous_hash != previous_block.hash:
                return False
                
        return True

if __name__ == "__main__":
    print("=== MyCoin Blockchain ===")
    my_coin = Blockchain()
    
    # নতুন দুটি ব্লক (Transaction) অ্যাড করা হচ্ছে
    print("\nAdding Block 1...")
    my_coin.add_block(Block(1, {"sender": "Alice", "receiver": "Bob", "amount": 50}, ""))
    
    print("\nAdding Block 2...")
    my_coin.add_block(Block(2, {"sender": "Bob", "receiver": "Charlie", "amount": 20}, ""))
    
    # ব্লকচেইন ভ্যালিড কি না তা চেক করা
    print(f"\nIs blockchain valid? {my_coin.is_chain_valid()}")
    
    # আসুন হ্যাকিং করার চেষ্টা করি! (অ্যালিসের টাকা ৫০ এর বদলে ৫০০ করে দিই)
    print("\n[HACKER ATTACK] Changing Alice's amount from 50 to 500...")
    my_coin.chain[1].data = {"sender": "Alice", "receiver": "Bob", "amount": 500}
    
    # এখন চেক করে দেখি ব্লকচেইন ভ্যালিড আছে কি না
    print(f"Is blockchain valid after hack? {my_coin.is_chain_valid()}")
```

### কোডটি কীভাবে শিখবেন?
1. **SHA-256 (Cryptography):** `hashlib.sha256()` ফাংশনটি যেকোনো টেক্সট বা ডেটাকে ৬৫ ক্যারেক্টারের একটি নির্দিষ্ট সাইজের স্ট্রিংয়ে (Hash) পরিণত করে। ডেটার একটি অক্ষর পরিবর্তন করলেও পুরো হ্যাশটি সম্পূর্ণ বদলে যায়, যা ব্লকচেইনকে হ্যাকিং প্রুফ (Hacking-proof) করে তোলে।
2. **Proof of Work (Mining):** `target = "0" * difficulty` দিয়ে আমরা কম্পিউটারকে শর্ত দিয়েছি যে, একটি ব্লকের হ্যাশ অবশ্যই নির্দিষ্ট সংখ্যক `0` (যেমন `0000abc...`) দিয়ে শুরু হতে হবে। কম্পিউটারকে এই জিরো মেলানোর জন্য হাজার হাজার বার `nonce` পরিবর্তন করে হ্যাশ ক্যালকুলেট করতে হয়। এটিকেই মূলত মাইনিং বলে!
3. **Immutability (অপরিবর্তনশীলতা):** কোডের শেষের দিকে আমরা হ্যাকার হিসেবে ডেটা পরিবর্তন করার চেষ্টা করেছি। কিন্তু `is_chain_valid()` ফাংশনটি সাথে সাথে ধরে ফেলেছে যে ব্লকচেইনটি হ্যাক হয়েছে এবং ডেটা করাপ্ট (Corrupted) হয়ে গেছে!
