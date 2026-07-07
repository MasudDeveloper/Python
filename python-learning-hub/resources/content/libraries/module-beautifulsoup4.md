# BeautifulSoup4 (Zero to Hero) কমপ্লিট গাইড

ইন্টারনেটে বিলিয়ন বিলিয়ন ওয়েবসাইট আছে, আর এই ওয়েবসাইটগুলোর ভেতরে লুকিয়ে আছে মূল্যবান সব ডেটা (যেমন: ই-কমার্সের প্রোডাক্টের দাম, শেয়ার বাজারের রেট, বা নিউজ পোর্টালের খবর)। 

এই ওয়েবসাইটগুলো থেকে অটোমেটিকভাবে ডেটা চুরি বা সংগ্রহ করে আনার (Web Scraping) জন্য পাইথনের সবচেয়ে সহজ, জনপ্রিয় এবং জাদুকরী লাইব্রেরি হলো **BeautifulSoup4 (বা bs4)**।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের DOM ট্রি নেভিগেশন এবং ট্যাগ মডিফিকেশন পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক পার্সিং (Parsing)
প্রথমে টার্মিনালে `beautifulsoup4` এবং দ্রুত প্রসেস করার জন্য `lxml` পার্সার ইনস্টল করে নিন:
```bash
pip install beautifulsoup4 lxml requests
```

ধরি আমাদের কাছে একটি সিম্পল HTML কোড আছে। চলুন সেটাকে পার্স (Parse) বা প্রসেস করি:
```python
from bs4 import BeautifulSoup

html_doc = """
<html>
    <head><title>My Tech Blog</title></head>
    <body>
        <h1 class="main-title">Latest Laptops in 2024</h1>
        
        <div class="product" id="macbook">
            <h2 class="name">MacBook Pro M3</h2>
            <p class="price">$1500</p>
            <a href="https://apple.com/macbook">Buy Now</a>
        </div>
        
        <div class="product" id="dell">
            <h2 class="name">Dell XPS 15</h2>
            <p class="price">$1200</p>
            <a href="https://dell.com/xps">Buy Now</a>
        </div>
    </body>
</html>
"""

# HTML কে BeautifulSoup এর কাছে দেওয়া হলো
soup = BeautifulSoup(html_doc, 'lxml')

# ওয়েবসাইটের টাইটেল বের করা
print("Page Title:", soup.title.text) # আউটপুট: My Tech Blog
```

### ২. নির্দিষ্ট এলিমেন্ট খোঁজা (`find` এবং `find_all`)
`find()` শুধু প্রথম ম্যাচ হওয়া ট্যাগটি দেয়, আর `find_all()` সবগুলো ট্যাগের একটি লিস্ট দেয়।

```python
# প্রথম <h1> ট্যাগটি খোঁজা
header = soup.find('h1')
print("Header:", header.text)

# নির্দিষ্ট ক্লাস (Class) দিয়ে খোঁজা (লক্ষ্য করুন: class এর শেষে আন্ডারস্কোর '_' আছে)
macbook = soup.find('div', class_='product')
print("First Product Name:", macbook.find('h2').text)

# নির্দিষ্ট আইডি (ID) দিয়ে খোঁজা
dell_product = soup.find(id='dell')
print("Dell Price:", dell_product.find('p', class_='price').text)

# সবগুলো প্রোডাক্ট (<div>) একসাথে বের করা
all_products = soup.find_all('div', class_='product')

print("\n--- All Laptops ---")
for product in all_products:
    name = product.find('h2').text
    price = product.find('p').text
    print(f"{name} -> {price}")
```

### ৩. লিংক এবং ইমেজের সোর্স বের করা (Extracting Attributes)
শুধু টেক্সট নয়, অনেক সময় আমাদের লিংকের অ্যাড্রেস (href) বা ছবির লিংক (src) দরকার হয়। 
```python
# find() দিয়ে শুধু প্রথম লিংকটি বের করা
link = soup.find('a')

# ট্যাগের ভেতর থেকে অ্যাট্রিবিউট বের করার জন্য ডিকশনারির মতো ['অ্যাট্রিবিউটের_নাম'] দিতে হয়
print("Single URL:", link['href']) 
# আউটপুট: https://apple.com/macbook

# find_all() দিয়ে ওয়েবসাইটের সবগুলো লিংকের href একসাথে বের করা
all_links = soup.find_all('a')

print("\n--- All Links ---")
for l in all_links:
    # অনেক সময় <a> ট্যাগে href নাও থাকতে পারে, তাই সরাসরি l['href'] না দিয়ে l.get('href') ব্যবহার করা বেশি সেইফ!
    href = l.get('href')
    if href:
        print("URL:", href)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. CSS সিলেক্টর দিয়ে খোঁজা (`select`)
আপনি যদি ফ্রন্টএন্ড বা CSS জানেন, তবে `find` এর চেয়ে `select` আপনার বেশি ভালো লাগবে। 
* `.` (ডট) মানে Class
* `#` (হ্যাশ) মানে ID

```python
# শুধু প্রথম প্রোডাক্টের লিংক খোঁজা (select_one)
first_link = soup.select_one('.product a')
print("First Link:", first_link['href'])

# সবগুলো প্রোডাক্টের নাম একসাথে খোঁজা (select)
all_names = soup.select('.product .name')

for name in all_names:
    print("Name:", name.text)
```

### ৫. DOM ট্রি নেভিগেশন (Tree Navigation)
অনেক সময় ওয়েবসাইটে নির্দিষ্ট ক্লাসের নাম থাকে না। তখন আমাদেরকে "পিতামাতা-সন্তান" (Parent-Child) বা "ভাই-বোন" (Sibling) সম্পর্কের মাধ্যমে ডেটা খুঁজতে হয়।

```python
html = """
<ul class="menu">
    <li>Home</li>
    <li class="active">Blog</li>
    <li>About</li>
    <li>Contact</li>
</ul>
"""
soup = BeautifulSoup(html, 'lxml')

active_item = soup.find('li', class_='active')
print("Current:", active_item.text) # Blog

# আগের ভাই/বোন ট্যাগটি বের করা (Previous Sibling)
prev_item = active_item.find_previous_sibling('li')
print("Previous:", prev_item.text) # Home

# পরের ভাই/বোন ট্যাগটি বের করা (Next Sibling)
next_item = active_item.find_next_sibling('li')
print("Next:", next_item.text) # About

# পিতা ট্যাগটি বের করা (Parent)
parent_ul = active_item.parent
print("Parent Class:", parent_ul['class']) # ['menu']
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. রিয়েল লাইফ প্রোজেক্ট (Requests + BeautifulSoup)
চলুন সত্যি সত্যিই ইন্টারনেট থেকে একটি সাইটের পেজিনেশনসহ ডেটা স্ক্র্যাপ করি।

```python
import requests
from bs4 import BeautifulSoup

def scrape_books(page_number):
    # এটি মূলত স্ক্র্যাপিং প্র্যাকটিস করার জন্য একটি ফ্রি ওয়েবসাইট
    url = f"https://books.toscrape.com/catalogue/page-{page_number}.html"
    response = requests.get(url)
    
    if response.status_code != 200:
        return False
        
    soup = BeautifulSoup(response.text, 'lxml')
    books = soup.select('article.product_pod')
    
    for book in books:
        # টাইটেল বের করা
        title = book.select_one('h3 a')['title']
        
        # দাম বের করা
        price = book.select_one('.price_color').text
        
        print(f"Book: {title} | Price: {price}")
    
    return True

# প্রথম ২টি পেজের ডেটা স্ক্র্যাপ করা (Pagination)
for page in range(1, 3):
    print(f"\n--- Scraping Page {page} ---")
    scrape_books(page)
```

### ৭. ওয়েবসাইটের HTML পরিবর্তন করা (Modifying the Tree)
BeautifulSoup শুধু ডেটা পড়তেই পারে না, এটি ওয়েবসাইটের HTML কোড ডিলেট বা মডিফাইও করতে পারে! এটি ডেটা ক্লিনিংয়ের জন্য অনেক কাজে লাগে।

```python
html = """
<div class="article">
    <h1>Secret Data</h1>
    <script>alert('I am annoying ad!');</script>
    <p>This is the actual useful content.</p>
    <span class="ad">Buy our premium plan!</span>
</div>
"""
soup = BeautifulSoup(html, 'lxml')

# ১. தேவাহীন স্ক্রিপ্ট (JS) বা অ্যাড (Ad) ট্যাগগুলো রিমুভ বা ডিলিট করা (decompose)
for script in soup.find_all('script'):
    script.decompose() # এটি চিরতরে HTML থেকে ট্যাগটি মুছে ফেলবে
    
for ad in soup.find_all('span', class_='ad'):
    ad.decompose()

# ২. কোনো ট্যাগকে নতুন ট্যাগ দিয়ে রিপ্লেস করা (replace_with)
h1_tag = soup.find('h1')
new_tag = soup.new_tag("h2")
new_tag.string = "Cleaned Secret Data"
h1_tag.replace_with(new_tag)

print("\n--- Cleaned HTML ---")
print(soup.prettify()) # prettify() দিয়ে সুন্দর ফরমেটে HTML প্রিন্ট করা যায়
```

### ৮. মাল্টিপল ক্লাস (Multiple Classes) এবং রেগুলার এক্সপ্রেশন (Regex)
মাঝেমধ্যে ওয়েবসাইটের ক্লাসগুলো রেন্ডমলি চেঞ্জ হয় (যেমন `price-123`, `price-456`)। তখন রেগুলার এক্সপ্রেশন (Regex) ব্যবহার করতে হয়।

```python
import re

html = """
<div class="price-a2b">100</div>
<div class="price-x9y">200</div>
<div class="title">My Product</div>
"""
soup = BeautifulSoup(html, 'lxml')

# যে div গুলোর ক্লাস 'price-' দিয়ে শুরু হয়েছে, শুধু তাদেরকেই খুঁজবে!
prices = soup.find_all('div', class_=re.compile("^price-"))

for p in prices:
    print("Price Found:", p.text)
```

### সারসংক্ষেপ (Conclusion)
যদি কোনো ওয়েবসাইট সরাসরি HTML এ তাদের ডেটা দিয়ে দেয় (অর্থাৎ জাভাস্ক্রিপ্ট দিয়ে লোড করে না), তবে ডেটা স্ক্র্যাপ করার জন্য **`requests` + `BeautifulSoup`** এর চেয়ে ফাস্ট, সহজ এবং পাওয়ারফুল কম্বিনেশন আর কিছুই হতে পারে না! 

এটি ডেটা সায়েন্টিস্ট এবং স্ক্র্যাপারদের প্রথম এবং প্রধান হাতিয়ার!
