# Shutil (Zero to Hero) কমপ্লিট গাইড

পাইথনের `os` মডিউল দিয়ে আমরা ফাইল বা ফোল্ডার তৈরি এবং ডিলিট করতে পারি ঠিকই, কিন্তু ফাইল কপি (Copy), মুভ (Move / Cut), বা পুরো একটি ফোল্ডারের সবকিছু জিপ (Zip Archive) করে ফেলার মতো হাই-লেভেলের কাজগুলো `os` মডিউলে করা যায় না।

এই কাজগুলো করার জন্যই পাইথনে দেওয়া হয়েছে **`shutil` (Shell Utilities)** মডিউল। 

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ফাইল কপি-পেস্ট থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের জিপ ফাইল বানানো (Archiving) এবং ডিস্ক মেমোরি চেক করা বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ফাইল কপি করা (Copy)
একটি ফাইলকে এক জায়গা থেকে আরেক জায়গায় কপি করার জন্য `copy` ব্যবহৃত হয়।

```python
import shutil
import os

# ধরি আমাদের কাছে একটি 'report.pdf' ফাইল আছে
source = 'report.pdf'

# ১. ফাইলটিকে 'backup' ফোল্ডারে কপি করা
# (backup ফোল্ডারটি আগে থেকে থাকতে হবে)
destination = 'backup/'
shutil.copy(source, destination)

# ২. কপি করার সময় ফাইলের নাম পরিবর্তন করে দেওয়া
shutil.copy('report.pdf', 'backup/report_2026.pdf')

print("File copied successfully!")
```
*(বিঃদ্রঃ `shutil.copy` ফাইলের কনটেন্ট কপি করে, কিন্তু ফাইলের মেটাডেটা (কখন তৈরি হয়েছে) কপি করে না। মেটাডেটাসহ কপি করতে চাইলে `shutil.copy2()` ব্যবহার করতে হয়।)*

### ২. ফাইল বা ফোল্ডার মুভ / কাট করা (Move & Rename)
ফাইলকে কপি না করে কাট (Cut/Paste) করার জন্য `move` ব্যবহার করা হয়। মজার ব্যাপার হলো, পাইথনে ফাইল বা ফোল্ডার রিনেম (Rename) করার সবচেয়ে সেফ উপায় হলো এই `move` ফাংশন!

```python
import shutil

source = 'old_video.mp4'

# ১. ফাইলটিকে কাট করে অন্য ফোল্ডারে পাঠানো
shutil.move(source, 'videos_folder/')

# ২. ফাইলের নাম পরিবর্তন করা (একই ফোল্ডারের ভেতর কাট-পেস্ট করা মানেই রিনেম!)
shutil.move('videos_folder/old_video.mp4', 'videos_folder/new_video.mp4')

print("File moved and renamed!")
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. পুরো একটি ফোল্ডার কপি করা (`copytree`)
ধরি আপনার একটি প্রোজেক্ট ফোল্ডার আছে, যার ভেতর অনেকগুলো ফোল্ডার এবং শত শত ফাইল আছে। আপনি পুরো ফোল্ডারটিকে কপি করে অন্য জায়গায় নিতে চান।

```python
import shutil

source_folder = 'my_python_project'

# নতুন যে ফোল্ডারটি তৈরি হবে তার নাম (এটি আগে থেকে থাকলে পাইথন এরর দিবে)
backup_folder = 'E:/backups/project_backup'

# ফোল্ডারের ভেতরের অলিগলির সব ফাইলসহ পুরোটা কপি হয়ে যাবে!
shutil.copytree(source_folder, backup_folder)

print("Entire directory tree copied!")
```

### ৪. বিপজ্জনক ফোল্ডার ডিলিট করা (`rmtree`)
`os.rmdir()` দিয়ে শুধু খালি ফোল্ডার ডিলিট করা যায়। কিন্তু ফোল্ডারের ভেতর যদি একটি ফাইলও থাকে, তবে পাইথন এরর দিবে। 

ভরা ফোল্ডারকে (ফোল্ডার এবং তার ভেতরের সব ফাইল) চিরতরে ডিলিট করার একমাত্র উপায় হলো `shutil.rmtree`।
*(সতর্কতা: এটি রিসাইকেল বিনে যায় না, একেবারে ডিলিট হয়ে যায়!)*

```python
import shutil

target_folder = 'temp_files'

# target_folder এবং তার ভেতরের সব ফাইল চিরতরে ডিলিট!
# ignore_errors=True দিলে কোনো পারমিশন এরর আসলে প্রোগ্রাম ক্র্যাশ করবে না।
shutil.rmtree(target_folder, ignore_errors=True)

print("Folder completely destroyed!")
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. জিপ ফাইল তৈরি করা (Make Archive)
ধরুন আপনার একটি সফটওয়্যার বা ফোল্ডারকে ইন্টারনেটে আপলোড করতে হবে। আপনি চাচ্ছেন পুরো ফোল্ডারটিকে কম্প্রেস করে `.zip` ফাইল বানিয়ে ফেলতে।

```python
import shutil

# যে ফোল্ডারটিকে জিপ করতে চান
folder_to_zip = 'my_python_project'

# জিপ ফাইলের নাম (my_archive.zip তৈরি হবে)
output_filename = 'my_archive'

# ১. জিপ ফাইল তৈরি করা (format হিসেবে 'zip' বা 'tar' দেওয়া যায়)
shutil.make_archive(base_name=output_filename, format='zip', root_dir=folder_to_zip)

print("Zip file created successfully!")
```

### ৬. জিপ ফাইল আনজিপ বা এক্সট্রাক্ট করা (Unpack Archive)
ইন্টারনেট থেকে ডাউনলোড করা কোনো `.zip` ফাইলকে এক্সট্রাক্ট করা।

```python
import shutil

zip_file = 'downloaded_data.zip'

# যে ফোল্ডারে এক্সট্রাক্ট করতে চান (ফোল্ডার না থাকলে সে বানিয়ে নিবে)
extract_folder = 'extracted_data'

# আনজিপ বা এক্সট্রাক্ট করা
shutil.unpack_archive(filename=zip_file, extract_dir=extract_folder)

print("Zip file unpacked!")
```

### ৭. হার্ডডিস্কের স্টোরেজ স্পেস চেক করা (`disk_usage`)
বড় কোনো ফাইল বা ডেটাসেট ডাউনলোড করার আগে চেক করা উচিত যে কম্পিউটারে পর্যাপ্ত জায়গা (Free Space) আছে কি না।

```python
import shutil

# উইন্ডোজে "C:/" বা ম্যাক/লিনাক্সে "/" এর স্টোরেজ চেক করা
disk_info = shutil.disk_usage("C:/")

# এটি বাইট হিসেবে ডেটা দিবে, আমরা গিগাবাইট (GB) এ কনভার্ট করে নিবো
total_gb = disk_info.total / (1024**3)
used_gb = disk_info.used / (1024**3)
free_gb = disk_info.free / (1024**3)

print(f"Total Storage: {total_gb:.2f} GB")
print(f"Used Storage: {used_gb:.2f} GB")
print(f"Free Storage: {free_gb:.2f} GB")
```

### সারসংক্ষেপ (Conclusion)
অটোমেশন স্ক্রিপ্ট (Automation Script), ব্যাকআপ বট (Backup Bot) বা অপারেটিং সিস্টেমের যেকোনো রুটিন কাজের জন্য **`os`** এবং **`shutil`** মডিউল দুটি একে অপরের পরিপূরক হিসেবে কাজ করে। বিশেষ করে **`copytree`** এবং **`make_archive`** এর ব্যবহার জানা থাকলে আপনাকে আর ফোল্ডার ব্যাকআপ নেওয়ার জন্য থার্ড-পার্টি কোনো সফটওয়্যার ব্যবহার করতে হবে না!
