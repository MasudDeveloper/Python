# Scrapy (Zero to Hero) কমপ্লিট গাইড

`BeautifulSoup` বা `Requests` দিয়ে আপনি ছোটখাটো স্ক্র্যাপিং করতে পারেন। কিন্তু যখন আপনার হাজার হাজার বা লাখ লাখ পেজ স্ক্র্যাপ করতে হবে, তখন এগুলো অনেক স্লো হয়ে যায়। 

বড় স্কেলের (Enterprise Level) প্রফেশনাল ডেটা স্ক্র্যাপিংয়ের জন্য পাইথনের সবচেয়ে পাওয়ারফুল, দ্রুত এবং জনপ্রিয় **ফ্রেমওয়ার্ক (Framework)** হলো **Scrapy**। এটি অন্যান্য লাইব্রেরির মতো নয়; এটি একটি সম্পূর্ণ ইকোসিস্টেম যা নিজে থেকেই রিকোয়েস্ট ম্যানেজ করে, প্যারালাল প্রসেসিং করে এবং ডেটা সরাসরি ফাইলে বা ডেটাবেসে সেভ করতে পারে!

এই টিউটোরিয়ালে আমরা একদম শূন্য থেকে শুরু করে **Scrapy** দিয়ে প্রো-লেভেলের মাকড়সা (Spider) বানানো শিখবো।

---

## 🟢 পর্ব ১: বিগিনার লেভেল (Fundamentals)

### ১. ইনস্টলেশন এবং প্রজেক্ট তৈরি
Scrapy সাধারণ লাইব্রেরির মতো একটি সিঙ্গেল ফাইলে কাজ করে না। এটি লারাভেল বা জ্যাঙ্গোর মতো একটি প্রজেক্ট তৈরি করে নেয়।

প্রথমে টার্মিনালে Scrapy ইনস্টল করুন:
```bash
pip install scrapy
```

এবার আপনার কম্পিউটারে একটি ফোল্ডার খুলে টার্মিনালে নিচের কমান্ডগুলো দিন:
```bash
# ১. 'my_scraper' নামে একটি নতুন স্ক্র্যাপিং প্রজেক্ট তৈরি করা
scrapy startproject my_scraper

# ২. প্রজেক্ট ফোল্ডারে ঢোকা
cd my_scraper

# ৩. 'bookspider' নামে একটি স্পাইডার (Spider) তৈরি করা, যা books.toscrape.com স্ক্র্যাপ করবে
scrapy genspider bookspider books.toscrape.com
```

### ২. প্রথম স্পাইডার (Spider) কোডিং
`scrapy genspider` দেওয়ার পর `spiders` ফোল্ডারের ভেতরে `bookspider.py` নামে একটি ফাইল তৈরি হবে। চলুন সেই ফাইলে গিয়ে কোড লিখি:

```python
import scrapy

class BookspiderSpider(scrapy.Spider):
    name = "bookspider" # স্পাইডারের নাম
    allowed_domains = ["books.toscrape.com"] # এই ডোমেইনের বাইরে সে স্ক্র্যাপ করবে না
    start_urls = ["https://books.toscrape.com/"] # এখান থেকে স্ক্র্যাপিং শুরু হবে

    def parse(self, response):
        # response হলো ওয়েবসাইটের HTML কোড। আমরা CSS সিলেক্টর দিয়ে ডেটা ধরবো।
        books = response.css('article.product_pod')
        
        for book in books:
            yield {
                # ::text দিলে শুধু টেক্সট আসবে, ::attr(href) দিলে লিংক আসবে
                'title': book.css('h3 a::text').get(),
                'price': book.css('.price_color::text').get(),
                'link': book.css('h3 a::attr(href)').get(),
            }
```

### ৩. স্পাইডার রান করা এবং ডেটা সেভ করা (CSV/JSON)
Scrapy তে কোড রান করতে `python file.py` লেখা যায় না। টার্মিনাল থেকে রান করতে হয়। সবচেয়ে মজার বিষয় হলো, আপনি চাইলে কোনো এক্সট্রা কোড ছাড়াই ডেটা সরাসরি CSV বা JSON ফাইলে সেভ করতে পারবেন!

টার্মিনালে লিখুন:
```bash
# শুধু স্ক্রিনে আউটপুট দেখার জন্য
scrapy crawl bookspider

# ডেটা সরাসরি JSON ফাইলে সেভ করার জন্য
scrapy crawl bookspider -o books_data.json

# ডেটা সরাসরি CSV ফাইলে সেভ করার জন্য
scrapy crawl bookspider -o books_data.csv
```

---

## 🟡 পর্ব ২: ইন্টারমিডিয়েট (Pagination & Crawling)

### ৪. পেজিনেশন (পরের পেজে যাওয়া)
একটি ওয়েবসাইট স্ক্র্যাপ করে তো লাভ নেই, আমাদের সব পেজ স্ক্র্যাপ করতে হবে। Scrapy তে "Next Page" বাটনের লিংক ধরে অটোমেটিক সব পেজ ঘোরার কাজটি খুব সহজ।

```python
import scrapy

class BookspiderSpider(scrapy.Spider):
    name = "bookspider"
    allowed_domains = ["books.toscrape.com"]
    start_urls = ["https://books.toscrape.com/"]

    def parse(self, response):
        books = response.css('article.product_pod')
        
        for book in books:
            yield {
                'title': book.css('h3 a::title').get(),
                'price': book.css('.price_color::text').get(),
            }
            
        # 'next' বাটনের লিংক খোঁজা
        next_page = response.css('li.next a::attr(href)').get()
        
        if next_page is not None:
            # যদি নেক্সট পেজ থাকে, তবে সেই লিংকে আবার রিকোয়েস্ট পাঠানো এবং parse ফাংশনটিকেই আবার কল করা!
            yield response.follow(next_page, callback=self.parse)
```

### ৫. ডেটা ক্লিন করার জন্য Items ব্যবহার করা
বড় প্রজেক্টে ডেটা অগোছালো হয়ে যেতে পারে। তাই ডেটাকে স্ট্রাকচারড করার জন্য `items.py` ফাইলে মডেল তৈরি করে নেওয়া হয়।

প্রথমে `items.py` ফাইলে:
```python
import scrapy

class BookItem(scrapy.Item):
    title = scrapy.Field()
    price = scrapy.Field()
    stock_status = scrapy.Field()
```

এরপর স্পাইডার ফাইলে:
```python
from my_scraper.items import BookItem # ইমপোর্ট করা

def parse(self, response):
    books = response.css('article.product_pod')
    
    for book in books:
        book_item = BookItem() # অবজেক্ট তৈরি
        
        book_item['title'] = book.css('h3 a::text').get()
        # দামের আগে থাকা '£' সাইন রিমুভ করে ডেটা ক্লিন করা
        book_item['price'] = book.css('.price_color::text').get().replace('£', '')
        
        yield book_item
```

---

## 🔴 পর্ব ৩: অ্যাডভান্সড (Pro Ninja Level)

### ৬. Pipelines এর মাধ্যমে ডেটাবেসে (Database) সেভ করা
JSON এ ডেটা সেভ করা বিগিনারদের কাজ। প্রো স্ক্র্যাপাররা ডেটা সরাসরি MySQL বা MongoDB ডেটাবেসে সেভ করে। এর জন্য `pipelines.py` ব্যবহার করা হয়।

`pipelines.py` ফাইলে:
```python
import sqlite3

class SaveToSQLPipeline:
    def __init__(self):
        # স্পাইডার রান হলে ডেটাবেস কানেকশন তৈরি হবে
        self.con = sqlite3.connect('scraped_books.db')
        self.cur = self.con.cursor()
        self.cur.execute("""
        CREATE TABLE IF NOT EXISTS books(
            title TEXT,
            price TEXT
        )
        """)
        
    def process_item(self, item, spider):
        # প্রতিটি আইটেম পাওয়ার পর ডেটাবেসে সেভ হবে
        self.cur.execute("INSERT INTO books VALUES (?, ?)", (item['title'], item['price']))
        self.con.commit()
        return item
```
সবশেষে `settings.py` ফাইলে পাইপলাইনটি চালু (Uncomment) করে দিতে হবে:
```python
ITEM_PIPELINES = {
   "my_scraper.pipelines.SaveToSQLPipeline": 300,
}
```

### ৭. এন্টি-বট বাইপাস (Rotating User-Agents & Proxies)
Scrapy যেহেতু অনেক ফাস্ট, তাই এটি দিয়ে এক মিনিটে হাজার হাজার পেজ স্ক্র্যাপ করা যায়। কিন্তু সার্ভার এত স্পিড দেখে আপনাকে ব্যান করে দিবে! এটি ঠেকানোর জন্য আমরা `settings.py` এ কিছু ট্রিকস অ্যাপ্লাই করি:

**`settings.py` ফাইলে যুক্ত করুন:**
```python
# ১. রোবট রুলস অমান্য করা (ROBOTSTXT_OBEY = False)
ROBOTSTXT_OBEY = False

# ২. সার্ভারকে ফোল্ড করার জন্য একটি ফেক ইউজার-এজেন্ট দেওয়া
USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36"

# ৩. রিকোয়েস্টের মাঝে একটু গ্যাপ দেওয়া যেন স্প্যাম মনে না হয় (1 second)
DOWNLOAD_DELAY = 1
```
*(বিঃদ্রঃ প্রোজেক্ট অনেক বড় হলে **ScrapeOps** বা অন্য কোনো Proxy Provider এর API ব্যবহার করে প্রতি রিকোয়েস্টে আইপি (IP) পরিবর্তন করতে হয়।)*

---

## 📚 Scrapy শেখার সেরা রিসোর্স (Tutorial Recommendations)

যেহেতু Scrapy একটি ফ্রেমওয়ার্ক (যাতে Settings, Middleware, Pipeline সব আলাদা থাকে), তাই এটি কোনো ব্লগ পড়ে পুরোটা আয়ত্ত করা কঠিন। ভিডিও টিউটোরিয়াল দেখে প্র্যাকটিস করাটা সবচেয়ে ভালো হবে:

### ১. ইউটিউব চ্যানেল (YouTube Tutorials)
*   **John Watson Rooney:** ওয়েব স্ক্র্যাপিং এবং বিশেষ করে **Scrapy** এর জন্য ইউটিউবে এই লোকটির চ্যানেলটি হলো একটি গোল্ডমাইন (স্বর্ণখনি)! তার Scrapy নিয়ে একটি প্লেলিস্ট আছে যা বিগিনার থেকে প্রো লেভেলের জন্য সেরা।
*   **FreeCodeCamp:** "Web Scraping with Python - Full Course" নামে একটি লম্বা ভিডিও আছে যেখানে BeautifulSoup, Selenium এবং Scrapy তিনটিরই বিস্তারিত দেখানো হয়েছে।
*   **Tech With Tim:** তারও Scrapy এর ওপর চমৎকার ক্র্যাশ কোর্স আছে।

### ২. ওয়েবসাইট এবং ডকুমেন্টেশন
*   **[Scrapy Official Docs](https://docs.scrapy.org/en/latest/):** ফ্রেমওয়ার্ক শেখার জন্য অফিশিয়াল ডকুমেন্টেশনের কোনো বিকল্প নেই। Scrapy এর ডকুমেন্টেশন পাইথন কমিউনিটিতে অন্যতম সেরা হিসেবে পরিচিত। 

### সারসংক্ষেপ (Conclusion)
১-২ পেজ স্ক্র্যাপ করার জন্য BeautifulSoup ভালো। কিন্তু যখন আপনার লক্ষ্য হবে হাজার হাজার পেজ থেকে লাখ লাখ ডেটা কালেক্ট করে ডেটাবেসে জমা করা, তখন **Scrapy** ছাড়া অন্য কোনো অপশন নেই! এটি একটু কঠিন মনে হলেও একবার এর স্ট্রাকচার বুঝে গেলে আপনি স্ক্র্যাপিং ইন্ডাস্ট্রিতে রাজত্ব করবেন।
