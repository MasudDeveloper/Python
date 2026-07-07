# Pathlib (Zero to Hero) কমপ্লিট গাইড

আগেকার দিনে পাইথনে ফাইল বা ফোল্ডারের পাথ (লোকেশন) নিয়ে কাজ করার জন্য `os.path` ব্যবহার করা হতো, যা মূলত স্ট্রিং (Text) নিয়ে কাজ করতো। উইন্ডোজে `\` এবং ম্যাক/লিনাক্সে `/` এর জন্য প্রচুর কনফিউশন তৈরি হতো।

পাইথন ৩.৪ এ **`pathlib`** মডিউলটি রিলিজ হওয়ার পর সবকিছু বদলে গেছে! এটি পাথগুলোকে সাধারণ স্ট্রিংয়ের বদলে **অবজেক্ট (Object)** হিসেবে ডিল করে (OOP Approach)। ফলে কোড লেখা অনেক বেশি সহজ এবং রিডেবল (Readable) হয়ে গেছে।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের `rglob` এবং ফাইল রিডিং/রাইটিং পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. পাথ (Path) অবজেক্ট তৈরি করা
সব কাজের শুরুতেই `Path` ক্লাসটি ইমপোর্ট করে নিতে হবে।

```python
from pathlib import Path

# ১. বর্তমান ফোল্ডার (Current Directory)
current_dir = Path('.')
print("বর্তমান ফোল্ডার:", current_dir.absolute())

# ২. যেকোনো ফাইলের পাথ তৈরি করা (স্ল্যাশ নিয়ে চিন্তা করতে হবে না)
my_file = Path('folder/subfolder/file.txt')
print("ফাইলের পাথ:", my_file)
```

### ২. ফাইল বা ফোল্ডার চেক করা
ফাইলটি কি আদৌ এক্সিস্ট করে? নাকি এটি কোনো ফোল্ডার? 

```python
from pathlib import Path

file_path = Path('my_secret.txt')

# এটি কি আছে?
if file_path.exists():
    print("ফাইলটি পাওয়া গেছে!")
    
    # এটি কি সত্যিই ফাইল?
    if file_path.is_file():
        print("এটি একটি সাধারণ ফাইল।")
        
    # এটি কি ফোল্ডার?
    if file_path.is_dir():
        print("এটি একটি ফোল্ডার।")
else:
    print("এমন কোনো ফাইল বা ফোল্ডার নেই!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. পাথের বিভিন্ন অংশ বের করা (Name, Stem, Suffix)
`os.path` এ ফাইলের নাম বা এক্সটেনশন বের করতে ফাংশন কল করতে হতো, কিন্তু `pathlib` এ এগুলো হলো প্রপার্টি!

```python
from pathlib import Path

path = Path('/Users/admin/Downloads/document.pdf')

# ১. শুধু ফাইলের পুরো নাম
print("Full Name:", path.name) # document.pdf

# ২. এক্সটেনশন ছাড়া শুধু নাম (Stem)
print("File Name only:", path.stem) # document

# ৩. শুধু ফাইলের এক্সটেনশন (Suffix)
print("Extension:", path.suffix) # .pdf

# ৪. ফাইলটি কোন ফোল্ডারে আছে (Parent Directory)
print("Parent Folder:", path.parent) # /Users/admin/Downloads
```

### ৪. নতুন ফোল্ডার এবং ফাইল তৈরি করা (`mkdir` ও `touch`)
ম্যাক বা লিনাক্স টার্মিনালে যেমন `touch` দিয়ে ফাইল বানানো যায়, `pathlib` এও ঠিক সেভাবেই কাজ করে!

```python
from pathlib import Path

folder_path = Path('new_project/src')
file_path = folder_path / 'main.py' # ম্যাজিক! স্ট্রিং জোড়া লাগানোর জন্য / (ভাগ) চিহ্ন ব্যবহার করা যায়!

# ১. ফোল্ডার তৈরি করা (parents=True দিলে আগের ফোল্ডারগুলো না থাকলেও বানিয়ে নিবে)
folder_path.mkdir(parents=True, exist_ok=True)
print("Folder created!")

# ২. একটি খালি ফাইল তৈরি করা (touch)
file_path.touch()
print("Empty main.py created!")
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. অলিগলি থেকে ফাইল খুঁজে বের করা (`rglob`)
আপনি চাচ্ছেন একটি ফোল্ডার এবং তার ভেতরের সব ফোল্ডার স্ক্যান করে সবগুলো `.png` ছবি খুঁজে বের করতে। আগে এই কাজটি করার জন্য `os.walk()` দিয়ে অনেক বড় কোড লিখতে হতো। `pathlib` এ এটি মাত্র এক লাইনের কাজ!

```python
from pathlib import Path

# বর্তমান ফোল্ডার
target_dir = Path('.')

# rglob মানে Recursive Glob (অর্থাৎ ভেতরের সব ফোল্ডারেও খুঁজবে)
# শুধু glob() ব্যবহার করলে শুধু বর্তমান ফোল্ডারেই খুঁজতো
png_files = target_dir.rglob('*.png')

for file in png_files:
    print("Found PNG Image:", file.absolute())
```

### ৬. এক লাইনে ফাইল রিড এবং রাইট করা!
ফাইলের ভেতর কোনো লেখা সেভ করতে বা পড়তে হলে আমরা সাধারণত `with open('file.txt', 'r') as f:` লিখে থাকি। `pathlib` এই ৩-৪ লাইনের কোডকে ১ লাইনে নিয়ে এসেছে!

```python
from pathlib import Path

note_file = Path('diary.txt')

# ১. ফাইলে ডেটা লেখা (write_text)
# ফাইল না থাকলে সে বানিয়ে নিবে, আর থাকলে আগের লেখা মুছে নতুনটা সেভ করবে
note_file.write_text("Hello World! This is my Python diary.")
print("File saved successfully!")

# ২. ফাইল থেকে ডেটা পড়া (read_text)
content = note_file.read_text()
print("File Content:", content)
```
*(বিঃদ্রঃ বিশাল বড় ফাইল রিড করার জন্য `open()` ব্যবহার করাই ভালো, তবে ছোট ফাইলের জন্য `write_text` এবং `read_text` জাদুকরী!)*

### ৭. পাথের এক্সটেনশন পরিবর্তন করা (`with_suffix`)
ধরুন, আপনার কাছে একটি `.csv` ফাইলের পাথ আছে, আপনি চাচ্ছেন সেটিকে `.json` এ কনভার্ট করতে।

```python
from pathlib import Path

original = Path('data_backup.csv')

# ফাইলের এক্সটেনশন চেঞ্জ করে নতুন পাথ তৈরি করা (আসল ফাইল রিনেম হবে না)
new_path = original.with_suffix('.json')

print("New Path:", new_path) # data_backup.json
```

### সারসংক্ষেপ (Conclusion)
মডার্ন পাইথন কোডে `os.path` এর ব্যবহার প্রায় বিলুপ্ত হয়ে গেছে। আপনি যদি লেটেস্ট জ্যাঙ্গো (Django) ফ্রেমওয়ার্কের `settings.py` ফাইলটি দেখেন, সেখানেও এখন `os` এর বদলে **`pathlib`** ব্যবহার করা হয়! এর অবজেক্ট-ওরিয়েন্টেড অ্যাপ্রোচ এবং `/` অপারেটর দিয়ে পাথ জোড়া লাগানোর সিস্টেমটি ডেভেলপারদের কোড লেখাকে অনেক বেশি ক্লিন (Clean) এবং বাগ-ফ্রি (Bug-free) করে তুলেছে!
