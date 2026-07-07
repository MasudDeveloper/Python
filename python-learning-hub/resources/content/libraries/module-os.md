# OS (Zero to Hero) কমপ্লিট গাইড

পাইথন দিয়ে আপনি যদি কোনো স্ক্রিপ্ট বা বটের মাধ্যমে আপনার কম্পিউটারের ফাইল বা ফোল্ডারগুলো কন্ট্রোল করতে চান—যেমন: একসাথে ১০০টি ফোল্ডার বানানো, ফোল্ডারের ভেতরের সব ফাইলের নাম পরিবর্তন করা, বা কম্পিউটারের পরিবেশ (Environment Variables) কন্ট্রোল করা—তবে আপনার জন্য সবচেয়ে জরুরি মডিউল হলো **`os` (Operating System)**।

এই মডিউলটির সবচেয়ে বড় সুবিধা হলো, আপনি উইন্ডোজ (Windows) ব্যবহার করেন নাকি ম্যাক (Mac/Linux), তা নিয়ে আপনাকে ভাবতে হবে না। `os` মডিউল নিজে থেকেই বুঝে নিবে যে অপারেটিং সিস্টেম অনুযায়ী কমান্ড কেমন হবে!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ফোল্ডার বানানো থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Environment Variables এবং Directory Walk পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. লোকেশন দেখা এবং পরিবর্তন করা (CWD)
আপনি পাইথনের কোডটি কম্পিউটারের ঠিক কোন ফোল্ডার থেকে রান করছেন, তা জানা খুবই জরুরি।

```python
import os

# ১. বর্তমান ফোল্ডারের লোকেশন (Current Working Directory) দেখা
current_dir = os.getcwd()
print("আপনি এখন আছেন:", current_dir)

# ২. অন্য কোনো ফোল্ডারে যাওয়া (যেমন: C ഡ്രাইভের Desktop ফোল্ডার)
os.chdir("C:/Users/username/Desktop")
print("নতুন লোকেশন:", os.getcwd())
```

### ২. ফোল্ডারের ভেতরের ফাইলগুলো দেখা (`listdir`)
যেকোনো ফোল্ডারের ভেতরে কী কী ফাইল বা ফোল্ডার আছে, তা দেখার জন্য এটি ব্যবহৃত হয়।

```python
import os

# বর্তমান ফোল্ডারের সব ফাইল ও ফোল্ডারের নাম একটি লিস্ট হিসেবে দিবে
items = os.listdir('.')

print("এই ফোল্ডারের ফাইলসমূহ:")
for item in items:
    print("-", item)
```

### ৩. ফোল্ডার তৈরি, ডিলিট এবং রিনেম করা
```python
import os

# ১. নতুন একটি ফোল্ডার তৈরি করা (যদি আগে থেকে না থাকে)
if not os.path.exists("My_New_Folder"):
    os.mkdir("My_New_Folder")
    print("ফোল্ডার তৈরি হয়েছে!")

# ২. ফোল্ডার বা ফাইলের নাম পরিবর্তন করা (আগের নাম, নতুন নাম)
# os.rename("My_New_Folder", "Secret_Folder")

# ৩. ফোল্ডার ডিলিট করা (অবশ্যই ফোল্ডারটি খালি হতে হবে!)
# os.rmdir("Secret_Folder")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. পাথ (Path) নিয়ে সেফলি কাজ করা
উইন্ডোজে ফোল্ডারের লোকেশন লিখতে `\` ব্যবহার হয়, আর ম্যাক/লিনাক্সে `/` ব্যবহার হয়। আপনি যদি কোডে হার্ডকোড করে `folder\file.txt` লেখেন, তবে সেই কোড ম্যাক-এ গিয়ে ক্র্যাশ করবে! এই সমস্যা এড়াতে `os.path.join()` ব্যবহার করতে হয়।

```python
import os

folder = "images"
filename = "photo.jpg"

# অপারেটিং সিস্টেম অনুযায়ী পাইথন নিজেই সঠিক স্ল্যাশ (Slash) বসিয়ে নিবে!
safe_path = os.path.join(folder, filename)
print("Safe Path:", safe_path) 
# উইন্ডোজে আউটপুট: images\photo.jpg
# ম্যাক/লিনাক্সে: images/photo.jpg

# পাথ থেকে শুধু ফাইলের নাম বের করা
print("File Name:", os.path.basename(safe_path)) # photo.jpg

# পাথ থেকে শুধু ফোল্ডারের নাম বের করা
print("Folder Name:", os.path.dirname(safe_path)) # images
```

### ৫. ফাইল চেকিং এবং ফাইলের সাইজ দেখা
```python
import os

filepath = "app.py"

# এটি কি আদৌ কোনো ফাইল? (ফোল্ডার হলে False দিবে)
print("Is it a file?", os.path.isfile(filepath))

# এটি কি কোনো ফোল্ডার? 
print("Is it a directory?", os.path.isdir(filepath))

# ফাইলের সাইজ দেখা (বাইট হিসেবে)
if os.path.exists(filepath):
    size = os.path.getsize(filepath)
    print(f"File Size: {size / 1024:.2f} KB") # KB তে কনভার্ট করা হলো
```

### ৬. একসাথে অনেক ফোল্ডার তৈরি ও ডিলিট করা (`makedirs`)
আপনি যদি `os.mkdir('a/b/c')` লেখেন, কিন্তু `a` এবং `b` ফোল্ডার আগে থেকে না থাকে, তবে পাইথন এরর দিবে! এই সমস্যার সমাধানে `makedirs` ব্যবহার করতে হয়, যা দরকারি সব ফোল্ডার নিজে নিজেই চেইন আকারে তৈরি করে নিবে।

```python
import os

# parent ফোল্ডার, তার ভেতরে child, তার ভেতরে sub_child
# exist_ok=True দিলে আগে থেকে তৈরি থাকলে আর এরর দিবে না
os.makedirs("parent/child/sub_child", exist_ok=True)
print("Multiple directories created!")

# পুরো চেইন ডিলিট করতে চাইলে:
# os.removedirs("parent/child/sub_child")
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. ডিরেক্টরি ট্রি স্ক্যান করা (`os.walk`)
ধরুন, আপনার `Downloads` ফোল্ডারের ভেতরে হাজার হাজার ফাইল এবং অনেক ফোল্ডার আছে। আর সেই ফোল্ডারগুলোর ভেতরেও আরও অনেক ফোল্ডার আছে। আপনি চাচ্ছেন একদম ভেতরের ফোল্ডারগুলো পর্যন্ত খুঁজে সবগুলো `.pdf` ফাইল বের করতে।

এই কাজটিকে বলা হয় "Recursively Tree Walk"। `os.walk()` দিয়ে এটি সবচেয়ে সহজে করা যায়!

```python
import os

# যে ফোল্ডার স্ক্যান করতে চান তার নাম
target_folder = "." # ডট মানে বর্তমান ফোল্ডার

for dirpath, dirnames, filenames in os.walk(target_folder):
    print(f"\nScanning: {dirpath}")
    
    # শুধু PDF ফাইলগুলো ফিল্টার করা
    for f in filenames:
        if f.endswith(".pdf"):
            print("Found PDF:", os.path.join(dirpath, f))
```
*(এই কোডটি ভাইরাসের মতো কম্পিউটারের প্রতিটি ফোল্ডারের অলিগলিতে ঢুকে সব ফাইল চেক করবে!)*

### ৮. এনভায়রনমেন্ট ভ্যারিয়েবল (Environment Variables)
প্রফেশনাল প্রোজেক্টে (যেমন Django বা ফ্লাস্কে) ডেটাবেসের পাসওয়ার্ড বা API Key কখনো সরাসরি কোডে লেখা হয় না, কারণ গিটহাবে পুশ করলে হ্যাকাররা তা পেয়ে যাবে। এই সিক্রেট ডেটাগুলো অপারেটিং সিস্টেমের Environment Variables এ সেভ রাখা হয় এবং `os` মডিউল দিয়ে তা রিড করা হয়।

```python
import os

# ১. সিস্টেমের সব Environment Variable দেখা
# print(os.environ)

# ২. নির্দিষ্ট কোনো ভ্যারিয়েবল রিড করা (না পেলে None রিটার্ন করবে)
api_key = os.getenv("MY_SECRET_API_KEY")

if api_key:
    print("API Key Found!")
else:
    print("No API Key configured. Please set MY_SECRET_API_KEY in your system.")
    
# ৩. কোডের ভেতর থেকে নতুন Environment Variable সেট করা
os.environ["TEMPORARY_DB_PASS"] = "super_secure_pass123"
```

### ৯. টার্মিনাল কমান্ড রান করা (`os.system`)
আপনি চাইলে পাইথনের ভেতর থেকেই সরাসরি কমান্ড প্রম্পট বা লিনাক্সের ব্যাশ (Bash) কমান্ড রান করাতে পারেন!
*(তবে প্রোডাকশনে এই কাজের জন্য `subprocess` মডিউল ব্যবহার করা বেশি ভালো।)*

```python
import os

# স্ক্রিন পরিষ্কার (Clear) করার কমান্ড
# উইন্ডোজের জন্য 'cls', ম্যাক/লিনাক্সের জন্য 'clear'
if os.name == 'nt': # উইন্ডোজ হলে 
    os.system('cls')
else:
    os.system('clear')

# নতুন একটি নোটপ্যাড ওপেন করা (শুধু উইন্ডোজে কাজ করবে)
# os.system('notepad.exe')
```

### সারসংক্ষেপ (Conclusion)
সিস্টেম অটোমেশন স্ক্রিপ্ট (System Automation Script) বা কোনো প্রোজেক্টে হাজার হাজার ফাইল নিয়ে কাজ করার জন্য **`os`** মডিউলটি জানা অপরিহার্য। বিশেষ করে **`os.path.join()`** দিয়ে সেফলি পাথ তৈরি করা এবং **`os.walk()`** দিয়ে ফোল্ডারের ভেতর থেকে ডেটা খুঁজে বের করার কৌশলটি সব পাইথন ডেভেলপারের জানা থাকা উচিত!
