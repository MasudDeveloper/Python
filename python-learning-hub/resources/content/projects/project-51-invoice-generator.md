# ৫১. অ্যাডভান্সড ইনভয়েস ও বিলিং সিস্টেম (Invoice Generator System)

যেকোনো ব্যবসা বা দোকানের জন্য একটি অটোমেটেড ইনভয়েস বা বিলিং সিস্টেম থাকা খুবই জরুরি। আপনি হয়তো ম্যানুয়ালি এক্সেলে বিল বানান, কিন্তু পাইথন দিয়ে আপনি এমন একটি সফটওয়্যার বানাতে পারেন, যা ডেটাবেস থেকে কাস্টমারের তথ্য নিবে, সুন্দর ডিজাইনের একটি পিডিএফ (PDF) ইনভয়েস বানাবে এবং সাথে সাথে কাস্টমারের ইমেইলে পাঠিয়ে দিবে!

এই প্রজেক্টে আমরা পিডিএফ বানানোর জন্য **ReportLab** এবং ডেটাবেসের জন্য **SQLite** ব্যবহার করবো।

### কীভাবে কাজ করে? (How it works):
1. **Database (SQLite):** পাইথনের বিল্ট-ইন SQLite ডেটাবেস থেকে কাস্টমারের নাম, কেনা পণ্যের লিস্ট এবং দাম রিড করা হবে।
2. **Dynamic PDF Generation:** `ReportLab` লাইব্রেরি ব্যবহার করে একটি প্রফেশনাল পিডিএফ ডিজাইন করা হবে, যেখানে ডাইনামিকভাবে ডেটাবেসের তথ্যগুলো বসে যাবে।
3. **Automated Email:** পিডিএফ তৈরি হয়ে গেলে `smtplib` ব্যবহার করে সেটি সরাসরি কাস্টমারের ইমেইলে অ্যাটাচমেন্ট (Attachment) হিসেবে চলে যাবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install reportlab
```

### প্রজেক্টের কোড:
নিচের কোডটি ডেটাবেস থেকে তথ্য নিয়ে একটি পিডিএফ তৈরি করার বেসিক স্ট্রাকচার:

```python
import sqlite3
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
import os

def create_database():
    """ডেমো ডেটাবেস তৈরি করে কিছু ডেটা ইনসার্ট করা"""
    conn = sqlite3.connect('billing.db')
    cursor = conn.cursor()
    cursor.execute('''CREATE TABLE IF NOT EXISTS orders
                      (id INTEGER PRIMARY KEY, customer TEXT, item TEXT, price REAL)''')
    # ডেমো ডেটা ইনসার্ট
    cursor.execute("INSERT OR IGNORE INTO orders (id, customer, item, price) VALUES (1, 'Rahim', 'Laptop', 1200.00)")
    cursor.execute("INSERT OR IGNORE INTO orders (id, customer, item, price) VALUES (2, 'Rahim', 'Mouse', 25.50)")
    conn.commit()
    return conn

def generate_invoice(customer_name, orders):
    """ReportLab ব্যবহার করে পিডিএফ ইনভয়েস তৈরি করা"""
    filename = f"Invoice_{customer_name}.pdf"
    
    # পিডিএফ ক্যানভাস তৈরি
    c = canvas.Canvas(filename, pagesize=letter)
    
    # ইনভয়েসের হেডার
    c.setFont("Helvetica-Bold", 20)
    c.drawString(200, 750, "INVOICE / BILL")
    
    c.setFont("Helvetica", 12)
    c.drawString(50, 700, f"Customer Name: {customer_name}")
    c.drawString(50, 680, "----------------------------------------------------")
    
    # আইটেমগুলো প্রিন্ট করা
    y_position = 650
    total_amount = 0
    
    c.drawString(50, y_position, "Item Description")
    c.drawString(400, y_position, "Price ($)")
    y_position -= 20
    
    for order in orders:
        item_name = order[2]
        price = order[3]
        total_amount += price
        
        c.drawString(50, y_position, item_name)
        c.drawString(400, y_position, f"${price:.2f}")
        y_position -= 20
        
    c.drawString(50, y_position - 10, "----------------------------------------------------")
    
    # টোটাল বিল
    c.setFont("Helvetica-Bold", 14)
    c.drawString(300, y_position - 30, f"Total Amount: ${total_amount:.2f}")
    
    c.save()
    print(f"✅ Invoice generated successfully: {filename}")
    
    return filename

def invoice_system():
    print("=== Automated Billing & Invoice System ===\n")
    
    # ১. ডেটাবেস কানেকশন
    conn = create_database()
    cursor = conn.cursor()
    
    customer = "Rahim"
    
    # ২. ডেটাবেস থেকে কাস্টমারের অর্ডারগুলো খুঁজে বের করা
    cursor.execute("SELECT * FROM orders WHERE customer=?", (customer,))
    orders = cursor.fetchall()
    
    if len(orders) > 0:
        # ৩. পিডিএফ তৈরি করা
        pdf_file = generate_invoice(customer, orders)
        print("\n[!] Ready to send via Email using smtplib (Code not included for security).")
    else:
        print("No orders found for this customer.")
        
    conn.close()

if __name__ == "__main__":
    invoice_system()
```

### কোডটি কীভাবে শিখবেন?
1. **SQLite Database:** পাইথনে ডেটাবেসের কাজ শেখার জন্য `sqlite3` সবচেয়ে ভালো অপশন, কারণ এর জন্য আলাদা কোনো সার্ভার ইনস্টল করতে হয় না। কোড রান করলেই `billing.db` নামে একটি ফাইল তৈরি হয়ে যায়, যা একটি সম্পূর্ণ SQL ডেটাবেস!
2. **ReportLab Canvas:** `canvas.Canvas()` এর মাধ্যমে আমরা একটি ফাঁকা সাদা পেইজ পাই। এরপর `drawString(x, y, text)` ব্যবহার করে পেইজের যেকোনো কো-অর্ডিনেটে (x, y) আমরা টেক্সট বসাতে পারি। 
3. **Dynamic Y-Position:** একটি কাস্টমার ১০টি আইটেম কিনতে পারে, আবার ১টিও কিনতে পারে। তাই লুপের ভেতর `y_position -= 20` ব্যবহার করা হয়েছে, যাতে প্রতিটি নতুন আইটেম পিডিএফের একটু নিচে নিচে প্রিন্ট হয়।
