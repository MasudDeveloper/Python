## ১৮. লাইভ প্রজেক্ট: রিয়েল-টাইম কারেন্সি কনভার্টার GUI

এখন পর্যন্ত আমরা টার্মিনালে বা কমান্ড লাইনে কাজ করেছি। কিন্তু সাধারণ মানুষ তো আর টার্মিনাল বুঝে না, তারা বুঝে সফটওয়্যার উইন্ডো (GUI - Graphical User Interface)। এই প্রজেক্টটিতে আমরা একটি সুন্দর উইন্ডো বা সফটওয়্যার তৈরি করবো যা ইন্টারনেট থেকে ডলার, ইউরো বা টাকার লাইভ রেট নিয়ে আসবে এবং এক দেশের টাকা অন্য দেশের টাকায় কনভার্ট করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের লাগবে:
1. **Tkinter:** এটি পাইথনের বিল্ট-ইন লাইব্রেরি যা দিয়ে সফটওয়্যারের উইন্ডো (GUI) বানানো হয়। এটি ইনস্টল করার প্রয়োজন নেই।
2. **requests:** ইন্টারনেট থেকে লাইভ কারেন্সি রেট আনার জন্য।

টার্মিনালে এই কমান্ডটি লিখে `requests` ইনস্টল করে নিন (যদি আগে থেকে না থাকে):
```bash
pip install requests
```

### প্রজেক্টের কোড:

এই প্রজেক্টে আমরা লাইভ কারেন্সি রেট পাওয়ার জন্য `exchangerate-api.com` এর একটি ফ্রি API ব্যবহার করবো।

```python
import tkinter as tk
from tkinter import messagebox
import requests

def get_exchange_rate(from_currency, to_currency):
    # ফ্রি API (কোনো API Key ছাড়াই কাজ করে)
    url = f"https://api.exchangerate-api.com/v4/latest/{from_currency}"
    try:
        response = requests.get(url)
        data = response.json()
        rate = data['rates'][to_currency]
        return rate
    except Exception as e:
        messagebox.showerror("Error", "Could not fetch data. Check your internet connection.")
        return None

def convert_currency():
    try:
        # ইউজার থেকে ইনপুট নেওয়া
        amount = float(amount_entry.get())
        from_curr = from_currency_entry.get().upper()
        to_curr = to_currency_entry.get().upper()
        
        # লাইভ রেট বের করা
        rate = get_exchange_rate(from_curr, to_curr)
        
        if rate:
            converted_amount = round(amount * rate, 2)
            result_label.config(text=f"{amount} {from_curr} = {converted_amount} {to_curr}")
    except ValueError:
        messagebox.showwarning("Warning", "Please enter a valid number!")

# GUI উইন্ডো তৈরি করা
root = tk.Tk()
root.title("Real-Time Currency Converter")
root.geometry("350x300")
root.configure(bg="#2c3e50")

# উইন্ডোর ভেতরের ডিজাইন (Widgets)
title_label = tk.Label(root, text="Currency Converter", font=("Helvetica", 16, "bold"), bg="#2c3e50", fg="white")
title_label.pack(pady=15)

amount_label = tk.Label(root, text="Enter Amount:", bg="#2c3e50", fg="white")
amount_label.pack()
amount_entry = tk.Entry(root, font=("Helvetica", 12))
amount_entry.pack(pady=5)

from_currency_label = tk.Label(root, text="From Currency (e.g. USD):", bg="#2c3e50", fg="white")
from_currency_label.pack()
from_currency_entry = tk.Entry(root, font=("Helvetica", 12))
from_currency_entry.pack(pady=5)

to_currency_label = tk.Label(root, text="To Currency (e.g. BDT):", bg="#2c3e50", fg="white")
to_currency_label.pack()
to_currency_entry = tk.Entry(root, font=("Helvetica", 12))
to_currency_entry.pack(pady=5)

# কনভার্ট বাটন
convert_button = tk.Button(root, text="Convert", command=convert_currency, bg="#27ae60", fg="white", font=("Helvetica", 12, "bold"))
convert_button.pack(pady=15)

# রেজাল্ট দেখানোর লেবেল
result_label = tk.Label(root, text="", font=("Helvetica", 14, "bold"), bg="#2c3e50", fg="#f1c40f")
result_label.pack()

# সফটওয়্যার চালু রাখা
root.mainloop()
```

> [!TIP] 
> **বিঃদ্রঃ** কোডটি রান করলে আপনার কম্পিউটারে একটি সুন্দর সফটওয়্যার উইন্ডো ওপেন হবে। সেখানে `Amount`-এর জায়গায় টাকার পরিমাণ এবং `From Currency` তে `USD` ও `To Currency` তে `BDT` লিখে Convert বাটনে চাপ দিলে লাইভ ডলার রেট অনুযায়ী টাকার পরিমাণ দেখতে পাবেন!

---