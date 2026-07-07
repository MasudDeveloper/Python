## ২৩. লাইভ প্রজেক্ট: স্টক মার্কেট ডেটা অ্যানালাইজার (Stock Market Predictor)

ডেটা সায়েন্স এবং ফাইন্যান্সের জগতে এটি একটি অত্যন্ত জনপ্রিয় প্রজেক্ট। এই প্রজেক্টে আমরা ইয়াহু ফাইন্যান্স (Yahoo Finance) থেকে রিয়েল-টাইমে বিভিন্ন কোম্পানির (যেমন Apple, Google) শেয়ার বাজারের ডেটা নিয়ে আসবো এবং গ্রাফের মাধ্যমে অ্যানালাইসিস করে দেখাবো শেয়ারের দাম কীভাবে পরিবর্তন হচ্ছে এবং মুভিং এভারেজ (Moving Average) ব্যবহার করে এর ভবিষ্যৎ ট্রেন্ড বোঝার চেষ্টা করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ফিন্যান্সিয়াল ডেটা এবং ডেটা অ্যানালাইসিসের লাইব্রেরি লাগবে:
1. **yfinance:** ইয়াহু ফাইন্যান্স থেকে স্টক ডেটা ডাউনলোড করার জন্য।
2. **pandas:** ডেটা প্রসেস এবং অ্যানালাইসিস করার জন্য।
3. **matplotlib:** ডেটা দিয়ে সুন্দর চার্ট বা গ্রাফ তৈরি করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install yfinance pandas matplotlib
```

### প্রজেক্টের কোড:

এই প্রোগ্রামে আমরা Apple (AAPL) এর গত ১ বছরের স্টক ডেটা ডাউনলোড করে তার ক্লোজিং প্রাইস (Closing Price) এবং ৫০ দিনের মুভিং এভারেজ (50-Day Moving Average) গ্রাফে দেখাবো।

```python
import yfinance as yf
import pandas as pd
import matplotlib.pyplot as plt

def analyze_stock(ticker_symbol):
    print(f"Downloading data for {ticker_symbol}...")
    
    # স্টক ডেটা ডাউনলোড করা (গত ১ বছরের ডেটা)
    stock = yf.Ticker(ticker_symbol)
    df = stock.history(period="1y")
    
    if df.empty:
        print("No data found! Please check the ticker symbol.")
        return
        
    print("Data downloaded successfully! Generating chart...\n")
    
    # ৫০ দিনের মুভিং এভারেজ (Moving Average) ক্যালকুলেট করা
    # এটি ট্রেন্ড বুঝতে সাহায্য করে (দাম বাড়বে নাকি কমবে)
    df['50_MA'] = df['Close'].rolling(window=50).mean()
    
    # ২০০ দিনের মুভিং এভারেজ (দীর্ঘমেয়াদী ট্রেন্ডের জন্য)
    df['200_MA'] = df['Close'].rolling(window=200).mean()
    
    # চার্ট তৈরি করা
    plt.figure(figsize=(12, 6))
    
    # ক্লোজিং প্রাইস প্লট করা
    plt.plot(df.index, df['Close'], label='Closing Price', color='blue', linewidth=2)
    
    # ৫০ দিনের মুভিং এভারেজ প্লট করা
    plt.plot(df.index, df['50_MA'], label='50-Day Moving Average', color='orange', linestyle='--')
    
    # ২০০ দিনের মুভিং এভারেজ প্লট করা
    plt.plot(df.index, df['200_MA'], label='200-Day Moving Average', color='red', linestyle='--')
    
    # গ্রাফের লেবেল এবং টাইটেল
    plt.title(f"{ticker_symbol} Stock Price & Moving Averages (Last 1 Year)", fontsize=16)
    plt.xlabel("Date", fontsize=12)
    plt.ylabel("Price (USD)", fontsize=12)
    
    # লেজেন্ড এবং গ্রিড যোগ করা
    plt.legend()
    plt.grid(True)
    
    # গ্রাফটি স্ক্রিনে দেখানো
    plt.show()

# মূল প্রোগ্রাম
if __name__ == "__main__":
    print("=== Stock Market Analyzer ===")
    # Apple এর টিকার (Ticker) হলো AAPL. 
    # আপনি চাইলে GOOGL (Google) বা TSLA (Tesla) দিতে পারেন।
    ticker = input("Enter Stock Ticker Symbol (e.g., AAPL, GOOGL, TSLA): ").upper()
    analyze_stock(ticker)
```

> [!TIP] 
> **বিঃদ্রঃ** কোডটি রান করার পর `AAPL` (Apple এর জন্য) টাইপ করে এন্টার দিলে এটি ইন্টারনেট থেকে ডেটা নিয়ে একটি চমৎকার গ্রাফ তৈরি করবে। চার্টে আপনি দেখতে পাবেন কিভাবে শেয়ারের দাম ওঠানামা করছে। যখন 'Closing Price' লাইনটি '50_MA' লাইনের উপরে থাকে, তখন সাধারণত ধরা হয় যে স্টকটি ঊর্ধ্বমুখী (Upward trend) বা দাম বাড়ছে!

---