# Tkinter & CustomTkinter (A to Z) মাস্টারক্লাস

আপনি যদি পাইথন দিয়ে এমন কোনো সফটওয়্যার বানাতে চান যা ইউজার ডাবল-ক্লিক করে উইন্ডোজ বা ম্যাকে ওপেন করতে পারবে, তবে **Tkinter** হলো সবচেয়ে বেস্ট অপশন। আর সাধারণ Tkinter দেখতে একটু পুরোনো (Windows 98) ডিজাইনের হওয়ায়, বর্তমানে ডার্ক মোড এবং মডার্ন UI এর জন্য **CustomTkinter** সবচেয়ে বেশি জনপ্রিয়!

এই টিউটোরিয়ালে আমরা একদম শূন্য থেকে শুরু করে অবজেক্ট-ওরিয়েন্টেড ডিজাইনে একটি প্রফেশনাল মডার্ন সফটওয়্যার তৈরি করা পর্যন্ত A to Z সবকিছু শিখবো।

---

## 🟢 পর্ব ১: বেসিক Tkinter (The Fundamentals)

### ১. উইন্ডো তৈরি করা (Main Window)
যেকোনো সফটওয়্যারের মূল ভিত্তি হলো তার উইন্ডো।
```python
import tkinter as tk

# ১. মূল উইন্ডো তৈরি
root = tk.Tk()

# ২. উইন্ডোর টাইটেল এবং সাইজ সেট করা
root.title("My App")
root.geometry("500x400") # Width x Height
# root.resizable(False, False) # উইন্ডো ছোট-বড় করা বন্ধ করতে চাইলে

# ৩. মেইন লুপ (উইন্ডো যেন বন্ধ না হয়ে যায়)
root.mainloop()
```

### ২. উইজেট (Widgets: Label, Button, Entry)
সফটওয়্যারের ভেতরের বাটন, টেক্সট বা ইনপুট বক্সকে উইজেট বলে।
```python
import tkinter as tk

root = tk.Tk()
root.geometry("400x300")

# ১. Label (শুধু টেক্সট দেখানোর জন্য)
tk.Label(root, text="আপনার নাম লিখুন:", font=("Arial", 14)).pack(pady=10)

# ২. Entry (ইউজার থেকে এক লাইনের ইনপুট নেওয়ার জন্য)
name_entry = tk.Entry(root, width=30, font=("Arial", 12))
name_entry.pack(pady=5)

# ৩. Button (ক্লিক করার জন্য)
def show_name():
    print("Welcome", name_entry.get())

tk.Button(root, text="সাবমিট", bg="blue", fg="white", command=show_name).pack(pady=10)

root.mainloop()
```

### ৩. লেআউট ম্যানেজমেন্ট (Pack, Grid, Place)
উইজেটগুলোকে কোথায় বসাবেন, তার ৩টি পদ্ধতি আছে:
* **`pack()`:** ওপর থেকে নিচে বা এক পাশে সাজানোর জন্য (সবচেয়ে সহজ)।
* **`grid()`:** এক্সেলের মতো সারি (row) এবং কলাম (column) আকারে সাজানোর জন্য (সবচেয়ে জনপ্রিয়)।
* **`place()`:** নির্দিষ্ট X এবং Y কোঅর্ডিনেট দিয়ে বসানোর জন্য।

**Grid এর উদাহরণ:**
```python
import tkinter as tk
root = tk.Tk()

tk.Label(root, text="Email:").grid(row=0, column=0, padx=10, pady=10)
tk.Entry(root).grid(row=0, column=1)

tk.Label(root, text="Password:").grid(row=1, column=0, padx=10, pady=10)
tk.Entry(root, show="*").grid(row=1, column=1) # show="*" দিলে পাসওয়ার্ড হাইড হবে

tk.Button(root, text="Login").grid(row=2, column=1, pady=10)

root.mainloop()
```

---

## 🟡 পর্ব ২: অ্যাডভান্সড Tkinter

### ৪. ফ্রেম (Frames)
বড় সফটওয়্যারে লেআউট ঠিক রাখার জন্য পুরো উইন্ডোকে ছোট ছোট ফ্রেমে ভাগ করে নিতে হয় (যেমন: সাইডবার ফ্রেম, মেইন কন্টেন্ট ফ্রেম)।

```python
import tkinter as tk

root = tk.Tk()
root.geometry("500x400")

# বাম দিকের সাইডবার ফ্রেম
sidebar = tk.Frame(root, bg="gray", width=150, height=400)
sidebar.pack(side="left", fill="y") # fill="y" দিলে নিচ পর্যন্ত লম্বা হবে

tk.Button(sidebar, text="Home").pack(pady=10, padx=10, fill="x")
tk.Button(sidebar, text="Settings").pack(pady=10, padx=10, fill="x")

# ডান দিকের মেইন ফ্রেম
main_area = tk.Frame(root, bg="white")
main_area.pack(side="right", fill="both", expand=True)

tk.Label(main_area, text="Main Content Area", font=("Arial", 20), bg="white").pack(pady=50)

root.mainloop()
```

### ৫. মেসেজ বক্স এবং ফাইল ডায়ালগ (Dialogs)
পপ-আপ মেসেজ দেখানো বা কম্পিউটার থেকে ফাইল সিলেক্ট করা।

```python
import tkinter as tk
from tkinter import messagebox, filedialog

def show_alert():
    # ইনফরমেশন মেসেজ
    messagebox.showinfo("Success", "কাজটি সফল হয়েছে!")
    # সতর্কবার্তা
    messagebox.showwarning("Warning", "ফাইলটি পাওয়া যায়নি!")
    # প্রশ্ন করা (Yes/No)
    response = messagebox.askyesno("Exit", "আপনি কি বের হতে চান?")
    if response:
        root.quit()

def open_file():
    # ফাইল সিলেক্ট করার ডায়ালগ বক্স
    filepath = filedialog.askopenfilename(title="Select an image", filetypes=[("Images", "*.png *.jpg")])
    print("Selected File:", filepath)

root = tk.Tk()
tk.Button(root, text="Show Alerts", command=show_alert).pack(pady=20)
tk.Button(root, text="Open File", command=open_file).pack(pady=20)
root.mainloop()
```

### ৬. ইভেন্ট বাইন্ডিং (Key Bindings)
বাটনে ক্লিক করা ছাড়াও কীবোর্ডের এন্টার (Enter) বা মাউসের হোভার দিয়ে কাজ করানো।

```python
import tkinter as tk

def on_enter(event):
    print("Enter Key Pressed!")
    
def on_mouse_hover(event):
    btn.config(bg="red")

root = tk.Tk()
btn = tk.Button(root, text="Hover me!")
btn.pack(pady=50)

# <Return> মানে কীবোর্ডের Enter বাটন
root.bind("<Return>", on_enter)
# <Enter> মানে মাউস হোভার করা
btn.bind("<Enter>", on_mouse_hover)

root.mainloop()
```

---

## 🔴 পর্ব ৩: CustomTkinter (The Modern UI)

CustomTkinter (সংক্ষেপে CTk) হলো সাধারণ Tkinter এর ওপর ভিত্তি করে তৈরি একটি মডার্ন লাইব্রেরি। এটি ডার্ক মোড সাপোর্ট করে এবং এর বাটনগুলোর কর্নার রাউন্ড (Rounded Corners) থাকে, যা দেখতে হুবহু মডার্ন ওয়েব বা ম্যাক অ্যাপের মতো লাগে!

প্রথমে ইন্সটল করে নিন: `pip install customtkinter`

### ৭. CTk এর বেসিক এবং থিম সেটআপ
সাধারণ `tk.Tk()` এর জায়গায় `ctk.CTk()` ব্যবহার করতে হয়।

```python
import customtkinter as ctk

# ১. সিস্টেম অনুযায়ী থিম সেট করা (Dark, Light, বা System)
ctk.set_appearance_mode("dark")  
# ২. কালার থিম (blue, green, dark-blue)
ctk.set_default_color_theme("blue")

root = ctk.CTk()
root.geometry("400x300")
root.title("Modern CTk App")

# সাধারণ Label এর বদলে CTkLabel
label = ctk.CTkLabel(root, text="Welcome to CustomTkinter!", font=("Arial", 20, "bold"))
label.pack(pady=40)

# মডার্ন বাটন (রাউন্ড কর্নার)
btn = ctk.CTkButton(root, text="Click Me", corner_radius=32)
btn.pack(pady=20)

root.mainloop()
```

### ৮. মডার্ন উইজেটসমূহ (Switch, CheckBox, Slider)
CustomTkinter এ সব মডার্ন UI কম্পোনেন্ট দেওয়া আছে।

```python
import customtkinter as ctk

def switch_event():
    print("Switch value:", switch_var.get())

root = ctk.CTk()
root.geometry("300x400")

# ১. CheckBox
checkbox = ctk.CTkCheckBox(root, text="Remember Me")
checkbox.pack(pady=20)

# ২. Switch (অন/অফ টগল)
switch_var = ctk.StringVar(value="on")
switch = ctk.CTkSwitch(root, text="Dark Mode", command=switch_event, variable=switch_var, onvalue="on", offvalue="off")
switch.pack(pady=20)

# ৩. Slider (ভলিউম বা সাইজ কমানো-বাড়ানোর জন্য)
slider = ctk.CTkSlider(root, from_=0, to=100)
slider.set(50) # ডিফল্ট ভ্যালু
slider.pack(pady=20)

# ৪. ProgressBar (লোডিং দেখানোর জন্য)
progressbar = ctk.CTkProgressBar(root, orientation="horizontal")
progressbar.set(0.7) # ৭০% লোড হয়েছে
progressbar.pack(pady=20)

root.mainloop()
```

### ৯. স্ক্রলেবল ফ্রেম (ScrollableFrame)
সাধারণ Tkinter এ স্ক্রলিং করাটা একটা বিশাল ঝামেলার কাজ। কিন্তু CTk তে এটি বিল্ট-ইন!

```python
import customtkinter as ctk

root = ctk.CTk()
root.geometry("400x400")

# এমন একটি ফ্রেম যেখানে কন্টেন্ট বেশি হলে অটোমেটিক স্ক্রলবার চলে আসবে
scrollable_frame = ctk.CTkScrollableFrame(root, width=300, height=200)
scrollable_frame.pack(pady=20)

# ফ্রেমে ২০টি বাটন লুপ চালিয়ে বসানো
for i in range(20):
    btn = ctk.CTkButton(scrollable_frame, text=f"Button {i+1}")
    btn.pack(pady=5)

root.mainloop()
```

---

## 🚀 প্রো প্রজেক্ট: অবজেক্ট-ওরিয়েন্টেড (OOP) মডার্ন লগিন অ্যাপ!
প্রফেশনাল ডেভেলপাররা কোনোদিনই সব কোড একসাথে লেখে না। তারা ক্লাস (Class) ব্যবহার করে অবজেক্ট-ওরিয়েন্টেড স্টাইলে কোড লেখে। চলুন CTk দিয়ে একটি মডার্ন লগিন পেজ বানাই!

```python
import customtkinter as ctk

# CTk ক্লাসকে ইনহেরিট (Inherit) করে নিজের অ্যাপ তৈরি করা
class ModernLoginApp(ctk.CTk):
    def __init__(self):
        super().__init__()

        self.title("Modern Login System")
        self.geometry("400x500")
        
        # UI তৈরি করার জন্য আলাদা মেথড কল করা
        self.create_widgets()

    def create_widgets(self):
        # টাইটেল
        self.title_label = ctk.CTkLabel(self, text="Login to Your Account", font=ctk.CTkFont(size=24, weight="bold"))
        self.title_label.pack(pady=(50, 20))

        # ইউজারনেম ইনপুট
        self.username_entry = ctk.CTkEntry(self, placeholder_text="Username", width=250, height=40)
        self.username_entry.pack(pady=10)

        # পাসওয়ার্ড ইনপুট
        self.password_entry = ctk.CTkEntry(self, placeholder_text="Password", show="*", width=250, height=40)
        self.password_entry.pack(pady=10)

        # লগিন বাটন
        self.login_btn = ctk.CTkButton(self, text="Login", width=250, height=40, command=self.login_action)
        self.login_btn.pack(pady=20)

        # এরর মেসেজ দেখানোর জন্য লেবেল
        self.error_label = ctk.CTkLabel(self, text="", text_color="red")
        self.error_label.pack()

    def login_action(self):
        # বাটনে ক্লিক করলে এই ফাংশনটি কাজ করবে
        username = self.username_entry.get()
        password = self.password_entry.get()

        if username == "admin" and password == "1234":
            self.error_label.configure(text="Login Successful!", text_color="green")
        else:
            self.error_label.configure(text="Invalid Username or Password!", text_color="red")

# অ্যাপ রান করা
if __name__ == "__main__":
    ctk.set_appearance_mode("dark")
    app = ModernLoginApp()
    app.mainloop()
```

### সারসংক্ষেপ (Conclusion)
আপনি যদি একদম জিরো থেকে ডেস্কটপ সফটওয়্যার বানানো শুরু করতে চান, তবে সাধারণ **Tkinter** এর লেআউট (`pack`, `grid`) এবং উইজেটগুলোর কাজ শিখে নেওয়া জরুরি। একবার সেগুলোর বেসিক ক্লিয়ার হয়ে গেলে, সরাসরি **CustomTkinter** এ শিফট করুন এবং অবজেক্ট-ওরিয়েন্টেড স্টাইলে প্রো-লেভেলের মডার্ন সফটওয়্যার বানানো শুরু করুন!
