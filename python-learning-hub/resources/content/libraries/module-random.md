# Random (Zero to Hero) কমপ্লিট গাইড

গেম ডেভেলপমেন্ট, লটারি সিস্টেম, মেশিন লার্নিংয়ের ডেটা শাফল করা (Shuffle) বা পাসওয়ার্ড জেনারেট করার জন্য দৈবচয়ন বা রেন্ডমাইজেশন (Randomization) খুবই জরুরি একটি বিষয়। পাইথনে এই কাজগুলো করার জন্যই তৈরি করা হয়েছে **`random`** মডিউল। 

তবে একটি গোপন সত্যি কথা হলো—কম্পিউটার নিজে থেকে কোনো রেন্ডম সংখ্যা তৈরি করতে পারে না! এগুলোকে বলা হয় **Pseudorandom** (ছদ্ম-রেন্ডম)। এরা কিছু নির্দিষ্ট গাণিতিক নিয়মে তৈরি হয়।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Weighted Randomness এবং হ্যাকিং-প্রুফ (Cryptographically Secure) পাসওয়ার্ড জেনারেশন বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. রেন্ডম নাম্বার বা সংখ্যা তৈরি করা
```python
import random

# ১. ০.০ থেকে ১.০ এর মধ্যে যেকোনো একটি ভগ্নাংশ বা ফ্লোট (Float) নাম্বার
num1 = random.random()
print("Random Float:", num1) # 0.4567...

# ২. দুটি নির্দিষ্ট সংখ্যার মধ্যে রেন্ডম ইন্টিজার (Integer) নাম্বার (যেমন ১ থেকে ১০)
# এখানে ১ এবং ১০ দুটোই রেজাল্টে আসতে পারে
num2 = random.randint(1, 10)
print("Random Integer (1-10):", num2)

# ৩. নির্দিষ্ট স্টেপ অনুযায়ী রেন্ডম নাম্বার (যেমন ০ থেকে ১০০ এর মধ্যে শুধু জোড় সংখ্যা)
# randrange(start, stop, step) - এখানে 100 রেজাল্টে আসবে না
num3 = random.randrange(0, 100, 2)
print("Random Even Number:", num3)
```

### ২. লিস্ট থেকে রেন্ডম ডেটা সিলেক্ট করা (`choice`)
লটারিতে বিজয়ীর নাম ঘোষণা করা বা গেমের মধ্যে রেন্ডম কালার দেওয়ার জন্য এটি ব্যবহৃত হয়।

```python
import random

colors = ['Red', 'Green', 'Blue', 'Yellow', 'Black']

# লিস্ট থেকে যেকোনো ১টি আইটেম রেন্ডমলি বাছাই করা
winner = random.choice(colors)

print("Winning Color:", winner)
```

### ৩. লিস্ট শাফল (Shuffle) বা এলোমেলো করা
তাসের গেম (Card Game) বানানোর সময় বা কুইজ অ্যাপে প্রশ্নের সিরিয়াল এলোমেলো করার জন্য।
```python
import random

cards = ['Ace', 'King', 'Queen', 'Jack', '10']

# এটি অরিজিনাল লিস্টটিকেই এলোমেলো করে দিবে (নতুন লিস্ট বানাবে না)
random.shuffle(cards)

print("Shuffled Cards:", cards)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. একাধিক রেন্ডম ডেটা বাছাই করা (`sample` এবং `choices`)
ধরুন, লটারিতে ১ জন নয়, ৩ জন বিজয়ী সিলেক্ট করতে হবে।

**ক) `random.sample` (Without Replacement - অর্থাৎ একই মানুষ ২ বার প্রাইজ পাবে না):**
```python
import random

participants = ['Rahim', 'Karim', 'Jamal', 'Salam', 'Borkot']

# ৫ জন থেকে ৩ জনকে বাছাই (কেউ ডাবল চান্স পাবে না)
winners = random.sample(participants, k=3)

print("Lottery Winners:", winners)
```

**খ) `random.choices` (With Replacement - একই মানুষ বারবার প্রাইজ পেতে পারে):**
লুডুর ছক্কার মতো, যেখানে বারবার ৬ উঠতে পারে।
```python
import random

dice_faces = [1, 2, 3, 4, 5, 6]

# ৫ বার ছক্কা মারা হলো (একই নাম্বার বারবার আসতে পারে)
rolls = random.choices(dice_faces, k=5)

print("Dice Rolls:", rolls)
```

### ৫. ওয়েটেড রেন্ডমনেস (Weighted Probability)
গেমে যখন আমরা কোনো বস (Boss) মারি, তখন ৯৯% সময় সাধারণ তরবারি পাওয়া যায়, আর ১% সময় ম্যাজিক তরবারি পাওয়া যায়। এটিকে বলে Weight সেট করা।

```python
import random

items = ['Normal Sword', 'Magic Sword']
# Normal Sword পাওয়ার চান্স 99 বার, Magic Sword পাওয়ার চান্স 1 বার
probabilities = [99, 1] 

# ১০ বার গেম খেললে কী কী পাওয়া যাবে?
loot = random.choices(items, weights=probabilities, k=10)

print("Your Loot:", loot) 
# আউটপুট: প্রায় সবগুলোই 'Normal Sword' আসবে
```

### ৬. সিড (Seed) দিয়ে রেন্ডমনেস ফিক্স করা!
মেশিন লার্নিং বা টেস্টিংয়ের সময় আমরা চাই যেন প্রতিবার একই রেন্ডম ডেটা জেনারেট হয় (যাতে আমরা বাগ ধরতে পারি)। `seed` ফাংশন দিয়ে রেন্ডম জেনারেটরের প্যাটার্ন ফিক্স করে দেওয়া যায়।

```python
import random

# একটি নির্দিষ্ট সিড সেট করা (যেকোনো নাম্বার হতে পারে)
random.seed(42)

print("First Run:", random.randint(1, 100)) # 82

# আবার একই সিড সেট করলে, হুবহু আগের নাম্বারটিই আসবে!
random.seed(42)
print("Second Run:", random.randint(1, 100)) # 82 (ম্যাজিক!)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. সিকিউর রেন্ডমনেস (`secrets` মডিউল)
সাধারণ `random` মডিউলের একটি ভয়ংকর দিক হলো—হ্যাকাররা যদি আপনার `seed` বা আগের কয়েকটি রেন্ডম নাম্বার জেনে ফেলে, তবে তারা সহজেই পরের নাম্বারটি কী হবে তা গেস করতে পারবে!

এজন্য ক্রিপ্টোগ্রাফি, টোকেন জেনারেট করা বা সিকিউরড পাসওয়ার্ড বানানোর জন্য পাইথনে `random` এর বদলে **`secrets`** মডিউল ব্যবহার করা হয়। এটি অপারেটিং সিস্টেমের সেন্সর থেকে (যেমন মাউস নড়াচড়া, কীবোর্ডের স্পিড) ট্রু-রেন্ডম (True Random) ডেটা নেয়, যা হ্যাক করা অসম্ভব।

```python
import secrets
import string

# ১. সিকিউরড রেন্ডম নাম্বার (যেমন ব্যাংকের OTP)
otp = secrets.randbelow(9999)
print("Bank OTP:", otp)

# ২. সিকিউরড URL বা API টোকেন তৈরি করা
token = secrets.token_hex(16) # 16 বাইটের হেক্সাডেসিমেল স্ট্রিং
print("API Token:", token)

# ৩. হ্যাকিং-প্রুফ শক্তিশালী পাসওয়ার্ড তৈরি করা (১২ ক্যারেক্টারের)
alphabet = string.ascii_letters + string.digits + string.punctuation
# password = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~'

# secrets.choice ব্যবহার করে সিকিউরড পাসওয়ার্ড বানানো
secure_password = "".join(secrets.choice(alphabet) for _ in range(12))

print("Highly Secure Password:", secure_password)
```

### সারসংক্ষেপ (Conclusion)
গেম বা সাধারণ ডেটা প্রসেসিংয়ের জন্য **`random`** মডিউল অসাধারণ। তবে যদি কোনো সিকিউরিটি রিলেটেড কাজ থাকে (যেমন: ইউজারের পাসওয়ার্ড রিসেট টোকেন, ক্রিপ্টোগ্রাফি বা সেশন আইডি তৈরি), তখন কোনো অবস্থাতেই `random` মডিউল ব্যবহার করা উচিত নয়, সেখানে অবশ্যই **`secrets`** মডিউল ব্যবহার করতে হবে!
