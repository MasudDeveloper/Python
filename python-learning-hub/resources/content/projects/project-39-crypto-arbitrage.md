# ৩৯. ক্রিপ্টোকারেন্সি আর্বিট্রেজ বট (Crypto Arbitrage Bot)

আর্থিক বাজার বা শেয়ার মার্কেট নিয়ে যাদের আগ্রহ আছে, তাদের জন্য এই প্রজেক্টটি অত্যন্ত আকর্ষণীয়! **আর্বিট্রেজ (Arbitrage)** মানে হলো কোনো একটি জিনিস এক জায়গা থেকে কম দামে কিনে সাথে সাথেই অন্য জায়গায় বেশি দামে বিক্রি করে প্রফিট করা। 

ক্রিপ্টোকারেন্সির (যেমন বিটকয়েন) দাম বিভিন্ন এক্সচেঞ্জে (যেমন Binance, Kraken, Coinbase) একটু হলেও আলাদা থাকে। এই প্রজেক্টে আমরা একটি বট তৈরি করবো, যা স্বয়ংক্রিয়ভাবে দুটি ভিন্ন এক্সচেঞ্জ থেকে বিটকয়েনের দাম চেক করবে এবং দামের পার্থক্য একটি নির্দিষ্ট পরিমাণের চেয়ে বেশি হলে আমাদেরকে অ্যালার্ট দিবে (বা আপনি চাইলে স্বয়ংক্রিয় ট্রেডিংয়ের কোডও অ্যাড করতে পারবেন)।

### কীভাবে কাজ করে? (How it works):
1. **Public APIs:** ক্রিপ্টো এক্সচেঞ্জগুলো তাদের রিয়েল-টাইম প্রাইস জানার জন্য ফ্রি API প্রোভাইড করে।
2. **Data Fetching:** আমাদের বট নির্দিষ্ট সময় পরপর (যেমন প্রতি ৫ সেকেন্ডে) Binance এবং Kraken এর API থেকে বিটকয়েনের (BTC) বর্তমান দাম নিয়ে আসবে।
3. **Comparison:** দুটি দামের মধ্যে পার্থক্য (Difference) বের করবে।
4. **Action:** যদি দেখা যায় Binance এ দাম কম কিন্তু Kraken এ দাম বেশি, তবে সে প্রফিটের সুযোগ হিসেবে সিগন্যাল দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের শুধু API রিকোয়েস্ট করার জন্য `requests` লাইব্রেরি লাগবে।

```bash
pip install requests
```

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে রান করুন। এটি রিয়েল-টাইমে Binance এবং Kraken থেকে বিটকয়েনের দাম এনে তুলনা করবে।

```python
import requests
import time

def get_binance_price(symbol="BTCUSDT"):
    """Binance API থেকে রিয়েল-টাইম প্রাইস নিয়ে আসা"""
    url = f"https://api.binance.com/api/v3/ticker/price?symbol={symbol}"
    try:
        response = requests.get(url)
        data = response.json()
        return float(data['price'])
    except Exception as e:
        print(f"Error fetching from Binance: {e}")
        return None

def get_kraken_price(pair="XBTUSDT"):
    """Kraken API থেকে রিয়েল-টাইম প্রাইস নিয়ে আসা"""
    url = f"https://api.kraken.com/0/public/Ticker?pair={pair}"
    try:
        response = requests.get(url)
        data = response.json()
        # Kraken এর রেসপন্স একটু জটিল, তাই নির্দিষ্ট ফিল্ড থেকে দাম নিতে হয়
        key = list(data['result'].keys())[0]
        price = data['result'][key]['c'][0]
        return float(price)
    except Exception as e:
        print(f"Error fetching from Kraken: {e}")
        return None

def arbitrage_bot():
    print("=== Crypto Arbitrage Bot Started ===")
    print("Monitoring BTC/USDT prices on Binance and Kraken...\n")
    
    # কত ডলার প্রফিট হলে বট সিগন্যাল দিবে
    PROFIT_THRESHOLD = 20.0  

    while True:
        binance_price = get_binance_price()
        kraken_price = get_kraken_price()

        if binance_price and kraken_price:
            print(f"Binance: ${binance_price:.2f} | Kraken: ${kraken_price:.2f}")
            
            # দামের পার্থক্য বের করা
            diff = abs(binance_price - kraken_price)
            
            if diff >= PROFIT_THRESHOLD:
                print("🚨 ARBITRAGE OPPORTUNITY DETECTED! 🚨")
                if binance_price < kraken_price:
                    print(f"Buy on Binance (${binance_price:.2f}) -> Sell on Kraken (${kraken_price:.2f})")
                else:
                    print(f"Buy on Kraken (${kraken_price:.2f}) -> Sell on Binance (${binance_price:.2f})")
                print(f"Estimated Gross Profit per BTC: ${diff:.2f}\n")
            else:
                print(f"Price difference (${diff:.2f}) is too low to cover trading fees.\n")
                
        # প্রতি ৫ সেকেন্ড পরপর দাম চেক করবে
        time.sleep(5)

if __name__ == "__main__":
    # বট রান করার কমান্ড
    arbitrage_bot()
```

### কোডটি কীভাবে শিখবেন?
1. **JSON Parsing:** `data.json()` ব্যবহার করে আমরা API থেকে আসা ডেটাকে পাইথন ডিকশনারিতে (Dictionary) রূপান্তর করেছি। ক্রিপ্টোর ডেটা সাধারণত JSON ফরম্যাটেই আসে। 
2. **Absolute Value (abs):** `abs(binance_price - kraken_price)` এর কাজ হলো ফলাফল পজিটিভ রাখা। দামের পার্থক্য নেগেটিভ আসুক বা পজিটিভ, `abs()` ফাংশন সবসময় পজিটিভ পার্থক্যটিই দিবে।
3. **While Loop & Time Sleep:** ট্রেডিং বটগুলোকে সবসময় চালু থাকতে হয়। তাই `while True:` দিয়ে অসীম লুপ চালানো হয়েছে এবং API লিমিট যেন ক্রস না করে তাই `time.sleep(5)` দিয়ে প্রতি রিকোয়েস্টের মাঝে ৫ সেকেন্ডের বিরতি দেওয়া হয়েছে।

> [!CAUTION]
> **সতর্কতা:** বাস্তবে আর্বিট্রেজ ট্রেডিং করতে গেলে এক্সচেঞ্জের উইথড্র ফি (Withdrawal Fee) এবং ট্রেডিং ফি (Trading Fee) হিসাব করতে হয়। তাই সরাসরি কোডে নিজের টাকা যুক্ত করার আগে অবশ্যই মার্কেট ভালোভাবে অ্যানালাইসিস করে নিবেন!
