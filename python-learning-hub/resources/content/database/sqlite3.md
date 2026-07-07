# SQLite3 (Zero to Hero) ডেটাবেস গাইড

যেকোনো অ্যাপ্লিকেশন বানানোর মূল শর্তই হলো—ডেটা সেভ করে রাখা (যাতে কম্পিউটার বন্ধ করলেও ডেটা মুছে না যায়)। ডেটা সেভ করার জন্য আমরা সাধারণত MySQL, PostgreSQL বা MongoDB ব্যবহার করি। কিন্তু এগুলোর জন্য আলাদা সার্ভার ইনস্টল করতে হয়।

খুব মজার ব্যাপার হলো, পাইথনের ভেতরে **`sqlite3`** নামে একটি সম্পূর্ণ ডেটাবেস সিস্টেম রেডিমেড দেওয়াই থাকে! এটি কোনো সার্ভার ছাড়াই একটি সাধারণ ফাইলের (.db) ভেতরে ডেটাবেস সেভ করে রাখে। ছোটখাটো প্রোজেক্ট, ডেস্কটপ সফটওয়্যার বা বট বানানোর জন্য এটিই বেস্ট চয়েস!

এই টিউটোরিয়ালে আমরা ডেটাবেসের ৪টি প্রধান কাজ বা **CRUD (Create, Read, Update, Delete)** অপারেশন শিখবো।

---

## ১. কানেকশন তৈরি এবং টেবিল বানানো (Create Table)
প্রথমে আমরা ডেটাবেস ফাইলের সাথে কানেক্ট করবো এবং ইউজারদের ডেটা রাখার জন্য একটি টেবিল বানাবো।

```python
import sqlite3

# ১. ডেটাবেস কানেকশন তৈরি (ফাইলটি না থাকলে পাইথন নিজে বানিয়ে নিবে!)
conn = sqlite3.connect('my_database.db')

# ২. কাজ করার জন্য একটি Cursor (পয়েন্টার) তৈরি করা
cursor = conn.cursor()

# ৩. টেবিল তৈরি করার SQL কমান্ড (যদি আগে থেকে না থাকে)
# এখানে id হলো প্রাইমারি কী (অটোমেটিক বাড়বে)
cursor.execute('''
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        age INTEGER,
        email TEXT UNIQUE
    )
''')

# ৪. চেঞ্জগুলো সেভ (Commit) করা
conn.commit()

print("Database and Table created successfully!")
```

## ২. ডেটা ইনসার্ট বা সেভ করা (Insert Data - Create)
টেবিলে নতুন ইউজারের ডেটা যোগ করা। হ্যাকিং বা SQL Injection থেকে বাঁচার জন্য আমরা সরাসরি স্ট্রিং জোড়া না লাগিয়ে `?` (প্যারামিটারাইজড কোয়েরি) ব্যবহার করবো।

```python
import sqlite3

conn = sqlite3.connect('my_database.db')
cursor = conn.cursor()

# ১. একজন ইউজারের ডেটা ইনসার্ট করা
user_data = ("Rahim", 25, "rahim@gmail.com")
cursor.execute("INSERT INTO users (name, age, email) VALUES (?, ?, ?)", user_data)

# ২. একসাথে অনেকগুলো ইউজারের ডেটা ইনসার্ট করা (executemany)
multiple_users = [
    ("Karim", 30, "karim@yahoo.com"),
    ("Jamal", 22, "jamal@test.com")
]
cursor.executemany("INSERT INTO users (name, age, email) VALUES (?, ?, ?)", multiple_users)

conn.commit()
print("Users inserted successfully!")
conn.close()
```

## ৩. ডেটা রিড বা বের করে আনা (Select Data - Read)
টেবিলে সেভ থাকা ডেটাগুলো খুঁজে বের করে পাইথনে প্রিন্ট করা।

```python
import sqlite3

conn = sqlite3.connect('my_database.db')
cursor = conn.cursor()

# ১. সব ডেটা সিলেক্ট করা
cursor.execute("SELECT * FROM users")

# fetchall() দিয়ে সব রেজাল্ট একটি লিস্ট হিসেবে বের করে আনা
all_users = cursor.fetchall()

print("--- All Users ---")
for user in all_users:
    # user হলো একটি Tuple: (id, name, age, email)
    print(f"ID: {user[0]}, Name: {user[1]}, Age: {user[2]}, Email: {user[3]}")

# ২. নির্দিষ্ট শর্ত দিয়ে খোঁজা (যেমন: যাদের বয়স ২৫ এর বেশি)
print("\n--- Users over 25 ---")
cursor.execute("SELECT name, email FROM users WHERE age > ?", (25,))

for row in cursor.fetchall():
    print(row)

conn.close()
```

## ৪. ডেটা আপডেট করা (Update Data)
ধরুন, রহিমের বয়স ২৫ থেকে বাড়িয়ে ২৬ করতে হবে।

```python
import sqlite3

conn = sqlite3.connect('my_database.db')
cursor = conn.cursor()

# UPDATE কমান্ড (অবশ্যই WHERE ব্যবহার করবেন, নাহলে সবার বয়স ২৬ হয়ে যাবে!)
cursor.execute("UPDATE users SET age = ? WHERE name = ?", (26, "Rahim"))

# আপডেট করার পর কমিট করতে হয়
conn.commit()
print(cursor.rowcount, "row(s) updated!")
conn.close()
```

## ৫. ডেটা ডিলিট করা (Delete Data)
অপ্রয়োজনীয় ডেটা ডেটাবেস থেকে মুছে ফেলা।

```python
import sqlite3

conn = sqlite3.connect('my_database.db')
cursor = conn.cursor()

# DELETE কমান্ড (অবশ্যই WHERE ব্যবহার করবেন!)
cursor.execute("DELETE FROM users WHERE name = ?", ("Jamal",))

conn.commit()
print(cursor.rowcount, "row(s) deleted!")
conn.close()
```

## ৬. (অ্যাডভান্সড) Context Manager দিয়ে সেফ কানেকশন
বারবার `conn.commit()` এবং `conn.close()` লিখতে ভুলে গেলে ডেটাবেস লক (Lock) হয়ে যেতে পারে। `with` ব্লক ব্যবহার করলে পাইথন নিজে থেকেই এই কাজগুলো করে নেয়।

```python
import sqlite3

# with ব্লক ব্যবহার করলে অটোমেটিক কমিট এবং ক্লোজ হয়ে যাবে!
with sqlite3.connect('my_database.db') as conn:
    cursor = conn.cursor()
    cursor.execute("INSERT INTO users (name, age, email) VALUES (?, ?, ?)", ("Salam", 28, "salam@example.com"))
    
    # ডেটা রিড করা
    cursor.execute("SELECT * FROM users")
    print("Final Database State:", cursor.fetchall())
```

### সারসংক্ষেপ (Conclusion)
যদিও বিশাল ওয়েব অ্যাপ্লিকেশনের জন্য PostgreSQL বা MySQL ব্যবহার করা হয়, কিন্তু মোবাইল অ্যাপ, ডেস্কটপ সফটওয়্যার, লোকাল টেস্টিং বা ছোট ডেটা অ্যানালাইসিস প্রোজেক্টের জন্য **`sqlite3`** এর কোনো তুলনা নেই। সবচেয়ে বড় কথা, এটি পাইথনের সাথে একদম ফ্রি এবং জিরো-কনফিগারেশনে কাজ করে!
