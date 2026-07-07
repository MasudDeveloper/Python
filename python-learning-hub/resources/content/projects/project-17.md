## ১৬. লাইভ প্রজেক্ট: পার্সোনাল এক্সপেন্স ট্র্যাকার ও ভিজ্যুয়ালাইজার

আমরা অনেকেই মাসের শেষে বুঝতে পারি না যে আমাদের টাকাগুলো কোন খাতে সবচেয়ে বেশি খরচ হয়েছে। এই প্রজেক্টটি আপনার প্রতিদিনের খরচের হিসাব রাখবে এবং মাস শেষে কোন খাতে কত টাকা খরচ হয়েছে তার একটি সুন্দর গ্রাফ বা পাই-চার্ট তৈরি করে দেখাবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ডেটা অ্যানালাইসিসের সবচেয়ে জনপ্রিয় দুটি লাইব্রেরি লাগবে:
1. **pandas:** ডেটা সেভ করা এবং হিসাব-নিকাশ করার জন্য।
2. **matplotlib:** খরচের ডেটা দিয়ে সুন্দর গ্রাফ বা চার্ট তৈরি করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install pandas matplotlib
```

### প্রজেক্টের কোড:

এই প্রোগ্রামে ইউজার তার খরচের পরিমাণ এবং খাতের নাম ইনপুট দিবে, যা একটি CSV ফাইলে সেভ হতে থাকবে। এরপর যখন সে গ্রাফ দেখতে চাইবে, তখন প্রোগ্রামটি সেই ফাইলের ডেটা দিয়ে একটি পাই-চার্ট তৈরি করবে।

```python
import pandas as pd
import matplotlib.pyplot as plt
import os
from datetime import datetime

# যে ফাইলে খরচের হিসাব সেভ থাকবে
FILE_NAME = "expenses.csv"

def add_expense():
    """খরচের হিসাব ফাইলে সেভ করার ফাংশন"""
    amount = float(input("Enter expense amount (Tk): "))
    category = input("Enter category (e.g., Food, Travel, Rent, Shopping): ").title()
    date = datetime.now().strftime("%Y-%m-%d")
    
    # ডেটা ডিকশনারি তৈরি
    data = {"Date": [date], "Category": [category], "Amount": [amount]}
    df = pd.DataFrame(data)
    
    # ফাইল আগে থেকে থাকলে নতুন ডেটা যোগ করা, না থাকলে নতুন ফাইল তৈরি করা
    if os.path.exists(FILE_NAME):
        df.to_csv(FILE_NAME, mode='a', header=False, index=False)
    else:
        df.to_csv(FILE_NAME, mode='w', header=True, index=False)
        
    print("Expense added successfully!\n")

def show_summary():
    """খরচের গ্রাফ বা চার্ট দেখানোর ফাংশন"""
    if not os.path.exists(FILE_NAME):
        print("No expenses recorded yet!\n")
        return
        
    # ফাইল থেকে ডেটা পড়া
    df = pd.read_csv(FILE_NAME)
    
    # খাত অনুযায়ী খরচের যোগফল বের করা
    summary = df.groupby("Category")["Amount"].sum()
    
    print("\n--- Expense Summary ---")
    print(summary)
    print("-----------------------\n")
    
    # পাই-চার্ট (Pie Chart) তৈরি করা
    plt.figure(figsize=(8, 6))
    summary.plot(kind='pie', autopct='%1.1f%%', startangle=140, colormap='Set3')
    
    plt.title("Expense Distribution by Category")
    plt.ylabel("") # y-অক্ষ এর লেবেল রিমুভ করা
    plt.show()

# মূল মেনু
while True:
    print("1. Add Expense")
    print("2. Show Expense Chart")
    print("3. Exit")
    
    choice = input("Enter your choice (1/2/3): ")
    
    if choice == '1':
        add_expense()
    elif choice == '2':
        show_summary()
    elif choice == '3':
        print("Exiting Expense Tracker. Have a good day!")
        break
    else:
        print("Invalid choice! Please try again.\n")
```

> [!TIP] 
> **বিঃদ্রঃ** এই প্রজেক্টটি করার মাধ্যমে আপনি `pandas` লাইব্রেরির `DataFrame` এবং `groupby` এর মতো গুরুত্বপূর্ণ কনসেপ্টগুলো শিখতে পারবেন, যা ডেটা সায়েন্স এবং ডেটা অ্যানালাইসিসে প্রচুর ব্যবহার করা হয়।

---