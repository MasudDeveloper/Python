# MySQL (Zero to Hero) কমপ্লিট গাইড

যেকোনো প্রজেক্ট, ওয়েবসাইট বা অ্যাপ্লিকেশন বানাতে গেলে ডেটা সেভ করে রাখার জন্য ডেটাবেস প্রয়োজন হয়। আর রিলেশনাল ডেটাবেসগুলোর মধ্যে **MySQL** হলো সারা পৃথিবীতে সবচেয়ে জনপ্রিয় এবং বহুল ব্যবহৃত একটি ডেটাবেস।

পাইথন ব্যবহার করে MySQL-এর সাথে কাজ করার জন্য মূলত দুটি জনপ্রিয় পদ্ধতি আছে:
1. **`mysql-connector-python`** অথবা **`PyMySQL`**: বেসিক SQL কুয়েরি (Query) লেখার জন্য।
2. **`SQLAlchemy` + `pandas`**: অ্যাডভান্সড ডেটা অ্যানালাইসিস, স্ক্র্যাপিং বা এক্সেলে ডেটা ট্রান্সফার করার জন্য।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেল পর্যন্ত পাইথনের সাথে MySQL-এর সব কাজ শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন (Installation)
প্রথমে প্রয়োজনীয় লাইব্রেরিগুলো ইনস্টল করে নিতে হবে। আপনার টার্মিনাল বা কমান্ড প্রম্পটে নিচের কোডটি রান করুন:
```bash
pip install mysql-connector-python pandas sqlalchemy openpyxl
```

### ২. ডাটাবেসে কানেকশন তৈরি করা (Database Connection)
MySQL সার্ভারের সাথে পাইথনকে কানেক্ট করতে হবে। (ধরে নিচ্ছি আপনার পিসিতে XAMPP বা MySQL সার্ভার ইন্সটল করা আছে এবং চলছে)।

```python
import mysql.connector

# কানেকশন তৈরি করা
db = mysql.connector.connect(
    host="localhost",       # সাধারণত localhost থাকে
    user="root",            # আপনার MySQL ইউজারনেম (ডিফল্ট root)
    password="",            # আপনার পাসওয়ার্ড (XAMPP-তে ডিফল্ট ব্ল্যাঙ্ক থাকে)
)

cursor = db.cursor()

# নতুন একটি ডাটাবেস তৈরি করা (যদি না থাকে)
cursor.execute("CREATE DATABASE IF NOT EXISTS python_hub")
print("Database connected and created!")
```

### ৩. টেবিল তৈরি করা (Create Table)
এখন আমরা কানেকশনটিতে সরাসরি নির্দিষ্ট ডাটাবেসের নাম দিয়ে কানেক্ট করবো এবং একটি টেবিল বানাবো।

```python
import mysql.connector

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="python_hub"   # আমাদের ডাটাবেসের নাম
)
cursor = db.cursor()

# users নামে একটি টেবিল তৈরি করা
create_table_query = """
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    age INT
)
"""
cursor.execute(create_table_query)
print("Table created successfully!")
```

### ৪. ডাটা ইনসার্ট করা (Insert Data)
টেবিলে ডাটা রাখার জন্য `INSERT INTO` কুয়েরি ব্যবহার করা হয়।

**ক) সিঙ্গেল ডাটা ইনসার্ট (Single Insert):**
```python
sql = "INSERT INTO users (name, email, age) VALUES (%s, %s, %s)"
val = ("Rahim", "rahim@email.com", 25)

cursor.execute(sql, val)
db.commit()  # ডেটাবেসে পরিবর্তন সেভ করার জন্য commit() কল করতে হয়!

print(cursor.rowcount, "record inserted.")
```

**খ) একসাথে একাধিক ডাটা ইনসার্ট (Multiple Insert):**
```python
sql = "INSERT INTO users (name, email, age) VALUES (%s, %s, %s)"
values = [
  ("Karim", "karim@email.com", 30),
  ("Jamal", "jamal@email.com", 22),
  ("Salam", "salam@email.com", 28)
]

cursor.executemany(sql, values)  # executemany ব্যবহার করতে হয়
db.commit()

print(cursor.rowcount, "records inserted.")
```

### ৫. ডাটা রিড করা (Select Data)
ডাটাবেস থেকে ডাটা নিয়ে আসার জন্য `SELECT` ব্যবহার করা হয়।

```python
cursor.execute("SELECT * FROM users")

# সবগুলো ডাটা একসাথে আনার জন্য fetchall()
results = cursor.fetchall()

for row in results:
    print(row)
    # আউটপুট হবে টুপল (Tuple) আকারে: (1, 'Rahim', 'rahim@email.com', 25)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ১. ডাটা আপডেট করা (Update Data)
কোনো নির্দিষ্ট ইউজারের বয়স পরিবর্তন করতে চাইলে:

```python
sql = "UPDATE users SET age = 26 WHERE name = 'Rahim'"
cursor.execute(sql)
db.commit()

print(cursor.rowcount, "record(s) updated.")
```

### ২. ডাটা ডিলিট করা (Delete Data)
নির্দিষ্ট কাউকে ডিলিট করতে চাইলে:

```python
sql = "DELETE FROM users WHERE name = 'Karim'"
cursor.execute(sql)
db.commit()

print(cursor.rowcount, "record(s) deleted.")
```

### ৩. নির্দিষ্ট শর্তে ডাটা খোঁজা (WHERE, LIKE, ORDER BY)
আপনি চাইলে ফিল্টার করে ডাটা আনতে পারেন।

```python
# যাদের বয়স ২৫ এর বেশি তাদের নাম নিয়ে আসা
cursor.execute("SELECT name, age FROM users WHERE age > 25 ORDER BY age DESC")

for x in cursor.fetchall():
    print(x)

# যাদের নামের শুরুতে 'S' আছে
cursor.execute("SELECT * FROM users WHERE name LIKE 'S%'")
print(cursor.fetchall())
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

রিয়েল লাইফ প্রজেক্টে ডেটা অ্যানালাইসিস বা মেশিন লার্নিংয়ের জন্য আমরা লুপ চালিয়ে ডেটা ইনসার্ট না করে সরাসরি **Pandas** ব্যবহার করি। এটি অনেক ফাস্ট এবং মাত্র কয়েক লাইনের কোড।

### ১. Excel বা CSV থেকে সরাসরি MySQL-এ ডাটা রাখা
ধরি আপনার কাছে `data.xlsx` নামে একটি এক্সেল ফাইল আছে এবং আপনি সেখানের সব ডাটা MySQL-এ নিতে চান।

```python
import pandas as pd
from sqlalchemy import create_engine

# ১. Excel ফাইল রিড করা
df = pd.read_excel("data.xlsx")

# ২. ডাটাবেস ইঞ্জিন তৈরি করা (ফরম্যাট: mysql+mysqlconnector://user:password@host/db_name)
engine = create_engine('mysql+mysqlconnector://root:@localhost/python_hub')

# ৩. ডাটা সরাসরি টেবিলে পাঠানো (index=False মানে হলো এক্সেলের সিরিয়াল নম্বর সেভ হবে না)
# if_exists='append' (নতুন ডাটা যোগ হবে), 'replace' (আগের ডাটা মুছে নতুনগুলো বসবে)
df.to_sql(name='users_from_excel', con=engine, if_exists='replace', index=False)

print("Excel data successfully imported to MySQL table!")
```

### ২. MySQL থেকে ডাটা Excel বা CSV-তে এক্সপোর্ট করা
ডাটাবেস থেকে সব ডাটা একটি এক্সেল ফাইলে সেভ করা:

```python
import pandas as pd
from sqlalchemy import create_engine

engine = create_engine('mysql+mysqlconnector://root:@localhost/python_hub')

# SQL কুয়েরি দিয়ে ডাটা আনা
query = "SELECT * FROM users"
df = pd.read_sql(query, con=engine)

# Excel-এ এক্সপোর্ট করা
df.to_excel("exported_users.xlsx", index=False)
# CSV তে করতে চাইলে: df.to_csv("exported_users.csv", index=False)

print("Data successfully exported to Excel!")
```

### ৩. ওয়েব স্ক্র্যাপিং করে ডাটা MySQL-এ রাখা (Scraping + Database)
ওয়েবসাইট থেকে ডাটা স্ক্র্যাপ করে সরাসরি ডাটাবেসে ইনসার্ট করার একটি রিয়েল-লাইফ আর্কিটেকচার:

```python
import mysql.connector
import requests
from bs4 import BeautifulSoup

# ডাটাবেস কানেকশন
db = mysql.connector.connect(host="localhost", user="root", password="", database="python_hub")
cursor = db.cursor()

# একটি টেবিল তৈরি (যদি না থাকে)
cursor.execute("CREATE TABLE IF NOT EXISTS scraped_quotes (id INT AUTO_INCREMENT PRIMARY KEY, text TEXT, author VARCHAR(255))")

# স্ক্র্যাপিং শুরু
url = 'http://quotes.toscrape.com/'
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

quotes = soup.find_all('div', class_='quote')

# ডাটাবেসে সেভ করার কুয়েরি
sql = "INSERT INTO scraped_quotes (text, author) VALUES (%s, %s)"

for quote in quotes:
    text = quote.find('span', class_='text').text
    author = quote.find('small', class_='author').text
    
    # এক্সিকিউট করা
    cursor.execute(sql, (text, author))

# লুপ শেষ হওয়ার পর একসাথে সব কমিট করা ভালো, এতে স্পিড বাড়ে
db.commit()

print("All scraped quotes saved to database!")
```

### ৪. Transactions এবং SQL Injection রোধ করা (Parameterized Queries)
**Security Tip:** ইউজার থেকে ইনপুট নিয়ে সরাসরি ফ্লোতে বসালে "SQL Injection" হ্যাকিং এর শিকার হতে পারেন। এর থেকে বাঁচতে সবসময় `(%s)` প্যারামিটারাইজড পদ্ধতি ব্যবহার করতে হবে।

**ভুল পদ্ধতি (হ্যাক হতে পারে):**
```python
name = input("Enter Name: ")
# ইনপুটে কেউ "DROP TABLE users;" দিলে পুরো ডাটাবেস ডিলিট হয়ে যেতে পারে!
cursor.execute(f"SELECT * FROM users WHERE name = '{name}'") 
```

**সঠিক পদ্ধতি (সিকিউর):**
```python
name = input("Enter Name: ")
# %s দিয়ে পাঠানো অনেক নিরাপদ
cursor.execute("SELECT * FROM users WHERE name = %s", (name, ))
```

### ৫. Transaction (Commit and Rollback)
ব্যাংকের ট্রানজেকশনের মতো, যদি একসাথে ৩টি টেবিলে কাজ করতে হয় এবং ৩ নম্বর টেবিলে গিয়ে এরর দেয়, তাহলে আগের ২টির পরিবর্তনও বাতিল (Rollback) করে দিতে হয়।

```python
try:
    cursor.execute("UPDATE account_a SET balance = balance - 500 WHERE id = 1")
    cursor.execute("UPDATE account_b SET balance = balance + 500 WHERE id = 2")
    
    # সব ঠিক থাকলে সেভ করো
    db.commit()
    print("Transaction Successful!")

except Exception as e:
    # কোথাও ভুল হলে সব পরিবর্তন বাতিল করো
    db.rollback()
    print(f"Transaction Failed! Rolled back. Error: {e}")
```

### উপসংহার
MySQL পাইথনের সাথে খুবই চমৎকার কাজ করে। আপনি যদি শুধুমাত্র Data Engineering বা Analysis করেন, তবে **Pandas + SQLAlchemy** আপনার সবচেয়ে ভালো বন্ধু। আর যদি আপনি স্ক্র্যাপিং বা ওয়েবসাইটের ব্যাকএন্ড (FastAPI/Flask) বানান, তবে **mysql-connector-python** অথবা **PyMySQL** আপনার দৈনন্দিন কাজে সবচেয়ে বেশি ব্যবহৃত হবে।
