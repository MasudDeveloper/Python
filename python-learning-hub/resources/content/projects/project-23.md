## ২২. লাইভ প্রজেক্ট: ইউটিউব ভিডিও ডাউনলোডার সফটওয়্যার (YouTube Downloader GUI)

ইউটিউব থেকে ভিডিও ডাউনলোড করার জন্য আমরা অনেকেই বিভিন্ন থার্ড-পার্টি ওয়েবসাইট ব্যবহার করি, যেগুলো অনেক সময় বিরক্তিকর অ্যাড দেখায়। এই প্রজেক্টে আমরা নিজেদের একটি প্রফেশনাল ডাউনলোডার সফটওয়্যার তৈরি করবো! এখানে ইউটিউব ভিডিওর লিংক পেস্ট করে ডাউনলোড বাটনে ক্লিক করলেই ভিডিওটি আপনার কম্পিউটারে ডাউনলোড হয়ে যাবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের লাগবে:
1. **yt-dlp:** ইউটিউব থেকে ভিডিও ডাউনলোড করার জন্য সবচেয়ে বেস্ট এবং আপডেটেড লাইব্রেরি।
2. **customtkinter:** обычный Tkinter এর চেয়ে অনেক বেশি মডার্ন এবং সুন্দর ইউজার ইন্টারফেস (GUI) তৈরি করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install yt-dlp customtkinter
```

### প্রজেক্টের কোড:

এই সফটওয়্যারটি দেখতে অনেক আধুনিক হবে এবং এটি ভিডিও ডাউনলোড করার সময় একটি প্রোগ্রেস বার (Progress bar) দেখাবে।

```python
import customtkinter as ctk
from tkinter import messagebox
import yt_dlp
import threading
import os

# সফটওয়্যারের থিম সেট করা (ডার্ক মোড)
ctk.set_appearance_mode("dark")
ctk.set_default_color_theme("blue")

def download_video():
    url = url_entry.get()
    
    if not url:
        messagebox.showerror("Error", "Please enter a valid YouTube URL!")
        return
        
    status_label.configure(text="Downloading...", text_color="yellow")
    progress_bar.set(0)
    download_btn.configure(state="disabled")
    
    # ডাউনলোডের কাজ ব্যাকগ্রাউন্ডে করার জন্য Threading ব্যবহার করা হচ্ছে 
    # যাতে সফটওয়্যার হ্যাং না করে
    thread = threading.Thread(target=process_download, args=(url,))
    thread.start()

def process_download(url):
    try:
        # ডাউনলোডের অপশন সেট করা
        ydl_opts = {
            'outtmpl': '%(title)s.%(ext)s',
            'format': 'best',
            'progress_hooks': [progress_hook],
        }
        
        with yt_dlp.YoutubeDL(ydl_opts) as ydl:
            ydl.download([url])
            
        status_label.configure(text="Download Complete! Check your folder.", text_color="green")
    except Exception as e:
        status_label.configure(text="Error occurred during download.", text_color="red")
        print(e)
    finally:
        download_btn.configure(state="normal")
        url_entry.delete(0, 'end')

def progress_hook(d):
    """ডাউনলোডের প্রোগ্রেস আপডেট করার ফাংশন"""
    if d['status'] == 'downloading':
        # প্রোগ্রেস হিসাব করা
        downloaded = d.get('downloaded_bytes', 0)
        total = d.get('total_bytes', 1)
        percentage = downloaded / total
        
        # প্রোগ্রেস বার আপডেট করা
        progress_bar.set(percentage)
        
        # স্পিড এবং পার্সেন্টেজ দেখানো
        percent_str = d.get('_percent_str', '0.0%')
        speed_str = d.get('_speed_str', '0KiB/s')
        status_label.configure(text=f"Downloading: {percent_str} at {speed_str}", text_color="yellow")

# GUI উইন্ডো তৈরি করা
root = ctk.CTk()
root.title("YouTube Downloader Pro")
root.geometry("500x300")
root.resizable(False, False)

# টাইটেল
title_label = ctk.CTkLabel(root, text="YouTube Video Downloader", font=ctk.CTkFont(size=20, weight="bold"))
title_label.pack(pady=20)

# URL ইনপুট ফিল্ড
url_entry = ctk.CTkEntry(root, width=400, height=40, placeholder_text="Paste YouTube URL here...")
url_entry.pack(pady=10)

# প্রোগ্রেস বার
progress_bar = ctk.CTkProgressBar(root, width=400, mode="determinate")
progress_bar.pack(pady=10)
progress_bar.set(0)

# স্ট্যাটাস লেবেল
status_label = ctk.CTkLabel(root, text="Ready to download", font=ctk.CTkFont(size=14))
status_label.pack(pady=5)

# ডাউনলোড বাটন
download_btn = ctk.CTkButton(root, text="Download Video", width=200, height=40, font=ctk.CTkFont(size=15, weight="bold"), command=download_video)
download_btn.pack(pady=15)

# সফটওয়্যার চালু রাখা
root.mainloop()
```

> [!TIP] 
> **বিঃদ্রঃ** কোডটি রান করলে `CustomTkinter` এর তৈরি একটি সুন্দর ডার্ক-মোড সফটওয়্যার ওপেন হবে। সেখানে ইউটিউব ভিডিওর লিংক দিয়ে ডাউনলোড বাটনে ক্লিক করলেই দেখবেন প্রোগ্রেস বার সহ ভিডিওটি আপনার কম্পিউটারে সেভ হয়ে যাচ্ছে!

---