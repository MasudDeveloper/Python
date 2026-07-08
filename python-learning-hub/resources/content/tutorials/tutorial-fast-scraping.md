# Fast Web Scraping (Multi-threading & Asynchronous) গাইড

যখন আমরা কোনো ওয়েবসাইট থেকে অনেক ডেটা কালেক্ট করতে চাই, তখন সাধারণ (Sequential) স্ক্রিপ্ট অনেক ধীরগতির মনে হতে পারে। কারণ সাধারণ স্ক্রিপ্ট একটি পেজ পুরোপুরি লোড হওয়ার পর পরের পেজে যায়। 

ধরি, আপনি ১০০০ পেজ স্ক্র্যাপ করতে চান। প্রতিটি পেজ লোড হতে যদি ১ সেকেন্ড সময় লাগে, তবে ১০০০ পেজ স্ক্র্যাপ করতে ১০০০ সেকেন্ড (প্রায় ১৬ মিনিট) সময় লাগবে! 

এই টিউটোরিয়ালে আমরা শিখবো কীভাবে **Multi-threading** এবং **Asynchronous** প্রোগ্রামিং ব্যবহার করে এই সময়কে ১০-২০ গুণ কমিয়ে আনা যায়।

---

## 🟢 বিগিনার লেভেল (সমস্যা এবং কনসেপ্ট)

### ১. সাধারণ স্ক্র্যাপিং (Sequential) কেন ধীরগতির?
সাধারণ স্ক্র্যাপিং অনেকটা ব্যাঙ্কের লাইনে দাঁড়িয়ে থাকার মতো। একজন কাস্টমারের কাজ শেষ না হওয়া পর্যন্ত কাউন্টার থেকে পরের কাস্টমারকে ডাকা হয় না। 

```python
import time
import requests

def scrape_page(url):
    response = requests.get(url)
    return response.status_code

urls = ["https://books.toscrape.com/"] * 20  # ২০ বার একই পেজে রিকোয়েস্ট

start_time = time.time()
for url in urls:
    scrape_page(url)  # এক এক করে রিকোয়েস্ট পাঠাচ্ছে
end_time = time.time()

print(f"Sequential Time: {end_time - start_time} seconds")
# আউটপুট: প্রায় 15-20 সেকেন্ড লাগবে!
```

### ২. সমাধান কী? (Multi-threading)
Multi-threading হলো ব্যাঙ্কে ২০টি নতুন কাউন্টার খুলে দেওয়ার মতো! ২০ জন কাস্টমারের কাজ একসাথে ২০টি কাউন্টারে শুরু হয়ে যাবে। 

পাইথনে এটি করার জন্য সবচেয়ে সহজ উপায় হলো `concurrent.futures` লাইব্রেরির **ThreadPoolExecutor** ব্যবহার করা।

---

## 🟡 ইন্টারমিডিয়েট লেভেল (ThreadPoolExecutor এর ব্যবহার)

চলুন আগের কোডটিকেই আমরা `ThreadPoolExecutor` দিয়ে ফাস্ট করে দেখি।

```python
import time
import requests
import concurrent.futures

def scrape_page(url):
    response = requests.get(url)
    return response.status_code

urls = ["https://books.toscrape.com/"] * 20

start_time = time.time()

# max_workers=20 মানে একসাথে ২০টি থ্রেড (বা ব্রাউজার ট্যাব) কাজ করবে
with concurrent.futures.ThreadPoolExecutor(max_workers=20) as executor:
    # executor.map ফাংশনটি আমাদের লিস্টের প্রতিটি লিংকের জন্য scrape_page চালাবে
    results = executor.map(scrape_page, urls)

end_time = time.time()

print(f"Multi-threading Time: {end_time - start_time} seconds")
# আউটপুট: মাত্র 1-2 সেকেন্ড লাগবে!
```

দেখলেন তো ম্যাজিক! যেখানে আগে ২০ সেকেন্ড লাগতো, সেখানে মাত্র ২ সেকেন্ডে কাজ শেষ।

---

## 🔴 অ্যাডভান্সড লেভেল (রিয়েল-লাইফ প্রোজেক্ট আর্কিটেকচার)

রিয়েল-লাইফে আমরা শুধু পেজে রিকোয়েস্ট পাঠাই না, সেখান থেকে ডাটা এক্সট্র্যাক্ট করে লিস্ট বা ডিকশনারিতে সেভ করি। নিচে একটি পারফেক্ট রিয়েল-লাইফ টেমপ্লেট দেওয়া হলো:

### থ্রেডিং দিয়ে রিয়েল-লাইফ স্ক্র্যাপিং টেমপ্লেট:

```python
import requests
from bs4 import BeautifulSoup
import concurrent.futures
import pandas as pd
import time

# ১. ডেটা বের করার মূল ফাংশন (একটি লিংকের জন্য)
def scrape_book(book_link):
    try:
        response = requests.get(book_link, timeout=10) # Timeout দেওয়া ভালো
        soup = BeautifulSoup(response.text, "html.parser")
        
        # ধরি আমরা শুধু টাইটেল আর প্রাইস নিচ্ছি
        title = soup.find("h1").get_text(strip=True)
        price = soup.find("p", class_="price_color").get_text(strip=True)
        
        # সফল হলে ডেটা রিটার্ন করো
        return {"Title": title, "Price": price, "Link": book_link}
        
    except Exception as e:
        print(f"Error on {book_link}: {e}")
        return None # এরর হলে None রিটার্ন করো, যাতে কোড ক্র্যাশ না করে

def main():
    # কিছু ডেমো লিংক
    book_links = [
        "https://books.toscrape.com/catalogue/a-light-in-the-attic_1000/index.html",
        "https://books.toscrape.com/catalogue/tipping-the-velvet_999/index.html",
        "https://books.toscrape.com/catalogue/soumission_998/index.html"
    ]
    
    # অনেক বেশি রিকোয়েস্টের জন্য লিংকগুলো ডাইনামিকালি কালেক্ট করে এখানে দিতে হবে
    
    scraped_data = []
    
    print("Scraping started...")
    
    # থ্রেডিং শুরু
    with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrape_book, book_links)
        
        for result in results:
            if result is not None:
                scraped_data.append(result)
                
    # ডেটা সেভ করা
    df = pd.DataFrame(scraped_data)
    df.to_csv("fast_books.csv", index=False)
    print("Scraping and Saving Completed!")

if __name__ == "__main__":
    main()
```

### কিছু গুরুত্বপূর্ণ টিপস (Best Practices):
1. **`max_workers` কত দিবেন?** 
   এটি খুব বেশি (যেমন: ৫০০) দেওয়া ঠিক নয়। আপনার পিসির র‍্যাম ও প্রসেসরের ওপর প্রেশার পড়বে। তাছাড়া অনেক ওয়েবসাইট একসাথে এত রিকোয়েস্ট দেখলে আপনাকে **DDoS Attacker বা Bot** ভেবে ব্লক করে দেবে। সাধারণত ১০ থেকে ২০ হলো নিরাপদ সংখ্যা।
2. **Timeout ব্যবহার করুন:** `requests.get(url, timeout=10)` ব্যবহার করুন। থ্রেডিংয়ের সময় কোনো একটি সাইট রেসপন্স না করলে পুরো থ্রেডটি ব্লক হয়ে বসে থাকে। Timeout দিলে ১০ সেকেন্ড পর নিজে থেকেই সেটি ক্যান্সেল হয়ে পরেরটিতে চলে যাবে।
3. **Try-Except ব্লক:** থ্রেডিংয়ের ভেতরে অবশ্যই Try-Except ব্যবহার করতে হবে। না হলে যেকোনো একটি লিংকের এররের কারণে পুরো প্রোগ্রামের বাকি থ্রেডগুলো ক্র্যাশ করতে পারে।

---

## 🚀 আরও অ্যাডভান্সড: Asynchronous Programming (asyncio + aiohttp)

থ্রেডিং ফাস্ট, তবে এর চেয়েও ফাস্ট হলো **Asynchronous** প্রোগ্রামিং। থ্রেডিং মূলত ওএসের (OS) উপর নির্ভর করে মাল্টিপল প্রসেস বানায়। কিন্তু Asynchronous প্রোগ্রামিং মাত্র **১টি থ্রেডের ভেতরেই** নন-স্টপ কাজ করতে পারে (যাকে Event Loop বলা হয়)।

**কখন কোনটি ব্যবহার করবেন?**
- যদি কোড সহজ রাখতে চান এবং স্পিড ১০-২০ গুণ বাড়ালেই চলে, তবে `concurrent.futures` (Threading) ব্যবহার করুন।
- যদি হাজার হাজার রিকোয়েস্ট একসাথে পাঠাতে হয় এবং একদম রকেটের মতো স্পিড লাগে, তবে `asyncio` এবং `aiohttp` ব্যবহার করতে হবে (এটি একটু জটিল)।
- আর যদি ফ্রেমওয়ার্ক ব্যবহার করতে চান, তবে সরাসরি `Scrapy` তে চলে যান, কারণ Scrapy ডিফল্টভাবেই Asynchronous!
