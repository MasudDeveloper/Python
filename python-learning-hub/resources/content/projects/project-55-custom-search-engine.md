# ৫৫. কাস্টম সার্চ ইঞ্জিন (Web Scraping Search Engine)

গুগল (Google) কীভাবে পুরো পৃথিবীর এত ওয়েবসাইট থেকে আপনার সার্চ করা তথ্যটি সেকেন্ডের মধ্যে খুঁজে এনে দেয়? তারা মূলত দুইটি কাজ করে: ১. ওয়েব স্ক্র্যাপিং (Web Scraping বা Crawling) করে ওয়েবসাইটের ডেটা পড়ে এবং ২. সেই ডেটাগুলোকে একটি দ্রুতগামী ডেটাবেসে ইনডেক্স (Index) করে রাখে।

এই প্রজেক্টে আমরা ঠিক গুগলের মতোই নিজস্ব একটি মিনি সার্চ ইঞ্জিন তৈরি করবো! আমরা **BeautifulSoup** ব্যবহার করে বিভিন্ন ওয়েবসাইট থেকে তথ্য স্ক্র্যাপ করবো এবং **Elasticsearch** ব্যবহার করে সেগুলো এমনভাবে ডেটাবেসে রাখবো যেন সেকেন্ডের ভগ্নাংশে সার্চ করা যায়।

### কীভাবে কাজ করে? (How it works):
1. **Web Crawler:** `requests` এবং `BeautifulSoup` ব্যবহার করে আমরা কয়েকটি নির্দিষ্ট ওয়েবসাইটের (যেমন: উইকিপিডিয়া বা কোনো নিউজ পোর্টাল) আর্টিকেল এবং টাইটেল ডাউনলোড করবো।
2. **Indexing:** সেই টেক্সটগুলোকে আমরা `Elasticsearch` ডেটাবেসে রাখবো। এটি সাধারণ SQL ডেটাবেসের মতো নয়, এটি মূলত টেক্সট সার্চ করার জন্য বিশেষভাবে তৈরি একটি 'Search Engine' ডেটাবেস।
3. **Query/Search:** ইউজার যখন কোনো কি-ওয়ার্ড (Keyword) লিখে সার্চ করবে, তখন এটি সাধারণ ডেটাবেসের মতো লাইন-বাই-লাইন না খুঁজে, ইনডেক্সিংয়ের মাধ্যমে মুহূর্তেই সবচেয়ে রেলেভেন্ট (Relevant) রেজাল্টটি বের করে আনবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install requests beautifulsoup4 elasticsearch
```
*(বিঃদ্রঃ এই প্রজেক্টটি রিয়েল-টাইমে রান করার জন্য আপনার কম্পিউটারে Elasticsearch সার্ভার ইনস্টল এবং চালু থাকতে হবে।)*

### প্রজেক্টের কোড:
নিচের কোডটি একটি ওয়েব স্ক্র্যাপার এবং সার্চ ইঞ্জিনের বেসিক আর্কিটেকচার:

```python
import requests
from bs4 import BeautifulSoup
from elasticsearch import Elasticsearch

# Elasticsearch এর সাথে কানেক্ট করা (লোকালহোস্ট)
# ধরে নিচ্ছি Elasticsearch পোর্ট 9200 এ চলছে
es = Elasticsearch([{'host': 'localhost', 'port': 9200, 'scheme': 'http'}])
INDEX_NAME = 'my_search_engine'

def scrape_and_index_url(url):
    """ওয়েবসাইট স্ক্র্যাপ করে ডেটাબેসে ইনডেক্স করা"""
    print(f"Crawling URL: {url}...")
    try:
        # ১. ওয়েবসাইট থেকে HTML ডাউনলোড করা
        response = requests.get(url, timeout=5)
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # ২. টাইটেল এবং মূল প্যারাগ্রাফগুলো (Text) এক্সট্র্যাক্ট করা
        title = soup.title.string if soup.title else 'No Title'
        paragraphs = soup.find_all('p')
        content = " ".join([p.text for p in paragraphs])
        
        # ৩. Elasticsearch এ ডকুমেন্ট হিসেবে ইনডেক্স করা (Save)
        doc = {
            'url': url,
            'title': title,
            'content': content
        }
        
        # 'my_search_engine' ইনডেক্সে ডেটা সেভ করা হচ্ছে
        es.index(index=INDEX_NAME, document=doc)
        print(f"✅ Indexed successfully: {title}")
        
    except Exception as e:
        print(f"❌ Error crawling {url}: {e}")

def search(query):
    """ইউজারের দেওয়া কি-ওয়ার্ড দিয়ে সার্চ করা"""
    print(f"\n🔍 Searching for: '{query}'...")
    
    # Elasticsearch এর সার্চ কুয়েরি (Query)
    search_body = {
        "query": {
            "multi_match": {
                "query": query,
                "fields": ["title^2", "content"] # টাইটেলের গুরুত্ব (Weight) ২ গুণ বেশি
            }
        }
    }
    
    try:
        # ডেটাবেসে সার্চ করা
        response = es.search(index=INDEX_NAME, body=search_body)
        hits = response['hits']['hits']
        
        print(f"Found {len(hits)} result(s) in {response['took']} ms:")
        print("-" * 50)
        
        for idx, hit in enumerate(hits):
            score = hit['_score']
            title = hit['_source']['title']
            url = hit['_source']['url']
            print(f"{idx+1}. {title} (Relevance Score: {score})")
            print(f"   Link: {url}")
            
    except Exception as e:
        print(f"Error during search: {e}. Is Elasticsearch running?")

def run_search_engine():
    print("=== Custom Web Search Engine ===")
    
    # ডেমো হিসেবে আমরা উইকিপিডিয়ার কয়েকটি পেজ ক্রল করবো
    urls_to_crawl = [
        "https://en.wikipedia.org/wiki/Python_(programming_language)",
        "https://en.wikipedia.org/wiki/Artificial_intelligence",
        "https://en.wikipedia.org/wiki/Machine_learning"
    ]
    
    # ডেটাবেসে পেজগুলো ইনডেক্স করা
    for url in urls_to_crawl:
        scrape_and_index_url(url)
        
    # ইউজার ইন্টারফেস
    while True:
        user_query = input("\nEnter search keyword (or type 'exit' to quit): ")
        if user_query.lower() == 'exit':
            break
        search(user_query)

if __name__ == "__main__":
    # run_search_engine()
    print("To run this project, make sure Elasticsearch is installed and running on localhost:9200")
```

### কোডটি কীভাবে শিখবেন?
1. **BeautifulSoup (bs4):** একটি ওয়েব পেজে হাজার হাজার লাইন HTML কোড থাকে। `soup.find_all('p')` এর মাধ্যমে আমরা মুহূর্তের মধ্যে পেজের সমস্ত `<p>` বা প্যারাগ্রাফ ট্যাগগুলোকে আলাদা করে ফেলি এবং সেখান থেকে শুধু মানুষের পড়ার যোগ্য টেক্সট (Plain text) বের করে আনি। 
2. **Elasticsearch Indexing:** সাধারণ ডেটাবেসে (যেমন SQL) আমরা 'Table' এ ডেটা সেভ করি, কিন্তু Elasticsearch এ আমরা ডেটা সেভ করি 'Index' এ। এখানে ইনডেক্সিং এমনভাবে হয় যে প্রতিটি শব্দের জন্য একটি ম্যাপ তৈরি হয়ে যায়, ফলে সার্চ করলে তা সাথে সাথে খুঁজে পাওয়া যায় (যাকে Inverted Index বলে)।
3. **Multi-Match & Weighting:** `"fields": ["title^2", "content"]` এই লজিকটি খুবই চমৎকার! আমরা সার্চ ইঞ্জিনকে বলে দিচ্ছি যে তুমি ইউজারের কি-ওয়ার্ডটি টাইটেল এবং কন্টেন্ট—দুই জায়গাতেই খুঁজবে। তবে যদি শব্দটি টাইটেলে পাওয়া যায়, তবে তাকে দ্বিগুণ গুরুত্ব (`^2`) দিবে এবং সার্চ রেজাল্টে তাকে উপরের দিকে দেখাবে! গুগলের সার্চ অ্যালগরিদমও অনেকটা এভাবেই কাজ করে।
