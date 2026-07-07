# ৪৭. ফেক নিউজ ডিটেক্টর (Fake News Detector using Deep Learning)

সোশ্যাল মিডিয়ার এই যুগে কোনটা আসল খবর আর কোনটা গুজব, তা বোঝা খুবই মুশকিল। কিন্তু আর্টিফিশিয়াল ইন্টেলিজেন্স বা ডিপ লার্নিং (Deep Learning) খুব সহজেই নিউজের প্যাটার্ন অ্যানালাইসিস করে বলে দিতে পারে কোনটি ফেক নিউজ! 

এই প্রজেক্টে আমরা **TensorFlow/Keras** এবং ন্যাচারাল ল্যাঙ্গুয়েজ প্রসেসিং (NLP) ব্যবহার করে একটি ডিপ লার্নিং মডেল তৈরি করবো, যা নিউজের টেক্সট পড়ে ফেক নিউজ ডিটেক্ট করবে।

### কীভাবে কাজ করে? (How it works):
1. **Dataset:** প্রথমে আমরা হাজার হাজার রিয়েল এবং ফেক নিউজের একটি ডেটাসেট (Kaggle থেকে) সংগ্রহ করবো।
2. **Text Preprocessing (NLP):** নিউজের টেক্সটগুলোকে (Words) টোকেনাইজেশনের (Tokenization) মাধ্যমে গাণিতিক সংখ্যায় (Numbers/Vectors) রূপান্তর করা হবে, কারণ এআই সরাসরি শব্দ বোঝে না।
3. **LSTM Model:** ডিপ লার্নিংয়ের `LSTM (Long Short-Term Memory)` মডেল ব্যবহার করা হবে, যা বাক্যের আগের শব্দের সাথে পরের শব্দের সম্পর্ক বুঝতে পারে।
4. **Prediction:** ট্রেনিং শেষে মডেলকে নতুন কোনো খবর দিলে সে ০ থেকে ১ এর মধ্যে একটি স্কোর দিবে। স্কোর ১ এর কাছাকাছি হলে সেটি ফেক নিউজ!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install tensorflow pandas numpy scikit-learn
```

### প্রজেক্টের কোড:
নিচের কোডটি হলো ফেক নিউজ ডিটেকশনের মূল এআই মডেল তৈরির স্ট্রাকচার:

```python
import pandas as pd
import numpy as np
from tensorflow.keras.preprocessing.text import Tokenizer
from tensorflow.keras.preprocessing.sequence import pad_sequences
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Embedding, LSTM, Dense, Dropout

def build_fake_news_detector():
    print("=== Fake News Detector (Deep Learning) ===")
    
    # এআই মডেলের কিছু প্যারামিটার
    vocab_size = 10000  # সর্বোচ্চ কতগুলো ইউনিক শব্দ এআই শিখবে
    max_length = 200    # একটি খবরের সর্বোচ্চ কতগুলো শব্দ অ্যানালাইসিস করবে
    
    # ১. ডেটাসেট লোড করা (ধরে নিচ্ছি 'news_dataset.csv' আছে)
    # df = pd.read_csv('news_dataset.csv')
    # texts = df['text'].values
    # labels = df['label'].values  # 1=Fake, 0=Real
    print("[1] Loading and preprocessing dataset...")
    
    # (ডেমো টেক্সট)
    texts = ["Scientists discover a new planet", "Aliens invaded Earth yesterday!"]
    
    # ২. টেক্সটকে নাম্বারে রূপান্তর (Tokenization)
    tokenizer = Tokenizer(num_words=vocab_size, oov_token="<OOV>")
    tokenizer.fit_on_texts(texts)
    
    sequences = tokenizer.texts_to_sequences(texts)
    padded_sequences = pad_sequences(sequences, maxlen=max_length, padding='post')
    
    print("[2] Building LSTM Neural Network...")
    # ৩. ডিপ লার্নিং মডেল তৈরি করা
    model = Sequential([
        Embedding(vocab_size, 64, input_length=max_length), # শব্দগুলোকে ভেক্টরে রূপান্তর
        LSTM(64, return_sequences=True), # বাক্যের সিকোয়েন্স মনে রাখা
        Dropout(0.2), # ওভারফিটিং রোধ করা
        LSTM(32),
        Dense(32, activation='relu'),
        Dense(1, activation='sigmoid') # আউটপুট 0 (Real) বা 1 (Fake)
    ])
    
    model.compile(loss='binary_crossentropy', optimizer='adam', metrics=['accuracy'])
    print("✅ Model compiled successfully!\n")
    
    # ৪. মডেল সামারি দেখা
    model.summary()
    
    print("\n[!] Ready for training (Requires actual dataset with model.fit())")

if __name__ == "__main__":
    build_fake_news_detector()
```

### কোডটি কীভাবে শিখবেন?
1. **Tokenizer & Pad Sequences:** এআই শব্দ বোঝে না, তাই `Tokenizer` প্রতিটি শব্দকে একটি আইডিতে (যেমন: "Apple" = 5) রূপান্তর করে। আর সব খবরের দৈর্ঘ্য সমান হয় না, তাই `pad_sequences` ছোট খবরগুলোর শেষে '0' বসিয়ে সবগুলোকে সমান সাইজ (`max_length=200`) করে দেয়।
2. **Embedding Layer:** এটি শব্দগুলোর অর্থ বোঝে! "King" এবং "Queen" শব্দ দুটির অর্থ যে কাছাকাছি, এম্বেডিং লেয়ার তা গাণিতিক ভেক্টরের মাধ্যমে মডেলকে বুঝিয়ে দেয়।
3. **LSTM (Long Short-Term Memory):** এটি সাধারণ নিউরাল নেটওয়ার্ক থেকে আলাদা। বাক্যের শুরুতে কী বলা হয়েছিল, তা বাক্যের শেষে গিয়েও এটি মনে রাখতে পারে, যা টেক্সট অ্যানালাইসিসের জন্য অপরিহার্য।
