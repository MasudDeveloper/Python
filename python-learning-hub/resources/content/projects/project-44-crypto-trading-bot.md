# ৪৪. অটোমেটেড ক্রিপ্টো ট্রেডিং বট (Crypto Trading Bot)

শেয়ার বাজার বা ক্রিপ্টোকারেন্সিতে ট্রেডিং করার ক্ষেত্রে মানুষের সবচেয়ে বড় দুর্বলতা হলো 'আবেগ' বা ইমোশন। অনেক সময় লস হতে থাকলে আমরা ভয়ে সেল (Sell) করে দিই। কিন্তু একটি ট্রেডিং বটের কোনো আবেগ নেই, সে শুধুমাত্র ডেটা এবং লজিকের ওপর ভিত্তি করে কাজ করে। 

এই প্রজেক্টে আমরা **ccxt** এবং **Pandas** ব্যবহার করে এমন একটি বট তৈরি করবো, যা স্বয়ংক্রিয়ভাবে মার্কেট অ্যানালাইসিস করবে এবং একটি নির্দিষ্ট লজিক (Trading Strategy) মিলে গেলে নিজে নিজেই বাই (Buy) বা সেল (Sell) করবে। 

### কীভাবে কাজ করে? (How it works):
1. **Exchange Connection:** `ccxt` লাইব্রেরি ব্যবহার করে বটটি Binance বা Coinbase এর মতো এক্সচেঞ্জের সাথে কানেক্ট করবে।
2. **Data Fetching:** মার্কেট থেকে ক্যান্ডেলস্টিক (Candlestick) বা OHLCV (Open, High, Low, Close, Volume) ডেটা নিয়ে আসবে।
3. **Data Analysis:** `pandas` ব্যবহার করে আমরা ডেটাগুলোকে অ্যানালাইসিস করবো এবং একটি ইন্ডিকেটর (যেমন: Moving Average) ক্যালকুলেট করবো।
4. **Order Execution:** যদি বর্তমান দাম আমাদের সেট করা টার্গেটের ওপরে বা নিচে যায়, তবে বট এক্সচেঞ্জে স্বয়ংক্রিয়ভাবে বাই বা সেল অর্ডার প্লেস করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:

```bash
pip install ccxt pandas
```

### প্রজেক্টের কোড:

নিচের কোডটিতে **SMA (Simple Moving Average) Crossover** স্ট্র্যাটেজি ব্যবহার করা হয়েছে। 

```python
import ccxt
import pandas as pd
import time

# এক্সচেঞ্জ সেটআপ (এখানে Binance এর Testnet বা ডেমো অ্যাকাউন্ট ব্যবহার করা হচ্ছে)
exchange = ccxt.binance({
    'apiKey': 'YOUR_TESTNET_API_KEY',
    'secret': 'YOUR_TESTNET_SECRET_KEY',
    'enableRateLimit': True,
})
# টেস্টনেট এনাবল করা (যাতে আপনার আসল টাকা খরচ না হয়)
exchange.set_sandbox_mode(True)

def fetch_data(symbol, timeframe, limit=100):
    """মার্কেট থেকে লাইভ ক্যান্ডেলস্টিক ডেটা নিয়ে আসা"""
    print(f"Fetching {limit} candles for {symbol} ({timeframe})...")
    ohlcv = exchange.fetch_ohlcv(symbol, timeframe, limit=limit)
    
    # ডেটাকে Pandas DataFrame এ রূপান্তর করা
    df = pd.DataFrame(ohlcv, columns=['timestamp', 'open', 'high', 'low', 'close', 'volume'])
    df['timestamp'] = pd.to_datetime(df['timestamp'], unit='ms')
    return df

def apply_trading_strategy(df):
    """SMA (Simple Moving Average) লজিক সেট করা"""
    # ২০ দিনের মুভিং এভারেজ
    df['SMA_20'] = df['close'].rolling(window=20).mean()
    # ৫০ দিনের মুভিং এভারেজ
    df['SMA_50'] = df['close'].rolling(window=50).mean()
    
    # বর্তমান ডেটা
    latest_data = df.iloc[-1]
    
    print(f"Current Price: {latest_data['close']}")
    print(f"SMA 20: {latest_data['SMA_20']:.2f} | SMA 50: {latest_data['SMA_50']:.2f}")
    
    # লজিক: যদি শর্ট টার্ম (২০) লং টার্ম (৫০) এর উপরে যায়, তবে বাই (Buy)
    if latest_data['SMA_20'] > latest_data['SMA_50']:
        return "BUY"
    # লজিক: যদি শর্ট টার্ম (২০) লং টার্ম (৫০) এর নিচে যায়, তবে সেল (Sell)
    elif latest_data['SMA_20'] < latest_data['SMA_50']:
        return "SELL"
    else:
        return "HOLD"

def automated_trading_bot():
    print("=== Automated Crypto Trading Bot ===")
    symbol = "BTC/USDT"
    trade_amount = 0.001 # কতগুলো বিটকয়েন কিনবে
    
    while True:
        try:
            df = fetch_data(symbol, '1h')
            signal = apply_trading_strategy(df)
            
            print(f"Trading Signal: {signal}")
            
            if signal == "BUY":
                print("Executing BUY Order...")
                # exchange.create_market_buy_order(symbol, trade_amount)
                print("✅ BUY order placed successfully!")
                # একবার কিনলে ১ ঘণ্টা অপেক্ষা করবে
                time.sleep(3600) 
                
            elif signal == "SELL":
                print("Executing SELL Order...")
                # exchange.create_market_sell_order(symbol, trade_amount)
                print("✅ SELL order placed successfully!")
                time.sleep(3600)
                
            else:
                print("Waiting for the next signal...")
                
        except Exception as e:
            print(f"Error occurred: {e}")
            
        # প্রতি ১ মিনিট পরপর মার্কেট চেক করবে
        time.sleep(60)
        print("-" * 40)

if __name__ == "__main__":
    automated_trading_bot()
```

### কোডটি কীভাবে শিখবেন?
1. **ccxt (CryptoCurrency eXchange Trading Library):** এটি ক্রিপ্টো ট্রেডিংয়ের জন্য সবচেয়ে শক্তিশালী লাইব্রেরি। এটি দিয়ে আপনি মাত্র একটি কোড লিখে Binance, Kraken, Coinbase সহ ১০০টির বেশি এক্সচেঞ্জে ট্রেড করতে পারবেন!
2. **Pandas DataFrame:** ডেটা অ্যানালাইসিসের জন্য `pandas` অতুলনীয়। `df['close'].rolling(window=20).mean()` এই এক লাইনের কোড দিয়ে আমরা গত ২০ দিনের দামের গড় (Moving Average) বের করে ফেলেছি, যা ম্যানুয়ালি করতে অনেকগুলো লুপ লিখতে হতো।
3. **Testnet / Sandbox:** `set_sandbox_mode(True)` ফাংশনটি আমাদের আসল টাকা বাঁচিয়ে দেয়! এর মাধ্যমে আমরা ক্রিপ্টো এক্সচেঞ্জের "ডেমো (Demo)" মোডে ট্রেড করতে পারি।

> [!WARNING]
> **সতর্কতা:** ট্রেডিং বটে যেকোনো ছোট ভুলের কারণে আপনার বড় আর্থিক ক্ষতি হতে পারে। তাই রিয়েল মানি (Real Money) ইনভেস্ট করার আগে সবসময় **Testnet/Paper Trading** ব্যবহার করে বটটিকে কয়েক সপ্তাহ টেস্ট করে নিবেন!
