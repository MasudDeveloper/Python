## ২০. লাইভ প্রজেক্ট: এআই চ্যাটবট (AI Customer Support Bot)

আজকাল প্রায় সব ওয়েবসাইটেই একটি অটোমেটিক চ্যাটবট থাকে যা কাস্টমারদের প্রশ্নের উত্তর দেয়। এই প্রজেক্টে আমরা NLTK (Natural Language Toolkit) ব্যবহার করে এমন একটি চ্যাটবট বানাবো যা ইউজারের মেসেজ পড়ে তার উদ্দেশ্য (Intent) বুঝতে পারবে এবং সে অনুযায়ী রিপ্লাই দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ন্যাচারাল ল্যাঙ্গুয়েজ প্রসেসিংয়ের (NLP) একটি লাইব্রেরি লাগবে:
1. **nltk:** মানুষের ভাষাকে প্রসেস করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install nltk
```

### প্রজেক্টের কোড:

এই বেসিক চ্যাটবটটি কিছু প্রি-ডিফাইনড (আগে থেকে ঠিক করা) কি-ওয়ার্ড দেখে ইউজারের উদ্দেশ্য বুঝতে পারে এবং র‍্যান্ডমলি উত্তর দেয়।

```python
import nltk
from nltk.chat.util import Chat, reflections

# প্রথমবারের জন্য nltk এর কিছু প্রয়োজনীয় ডাটা ডাউনলোড করতে হবে
# কোডটি একবার রান করার পর নিচের লাইনটি কমেন্ট করে রাখতে পারেন
nltk.download('punkt')

# চ্যাটবটের জন্য কিছু প্যাটার্ন এবং রেসপন্স (Responses)
# এখানে (প্যাটার্ন, [রেসপন্সের লিস্ট]) এই ফরম্যাটে ডাটা দেওয়া হয়
pairs = [
    (r"hi|hello|hey", ["Hello! How can I help you today?", "Hi there! Welcome to our support."]),
    (r"what is your name?", ["I am an AI Customer Support Bot, created by a Python Developer!"]),
    (r"how are you?", ["I am just a computer program, but I am doing great! How can I assist you?"]),
    (r"(.*) help (.*)", ["I can help you with product information, shipping, and returns. What do you need?"]),
    (r"(.*) product (.*)", ["We have a variety of electronics and software products. Please visit our website for more details."]),
    (r"(.*) shipping (.*)", ["Shipping usually takes 3-5 business days. Do you have a tracking number?"]),
    (r"(.*) return (.*)", ["You can return any product within 30 days of purchase. Please provide your order ID."]),
    (r"thank you|thanks", ["You are welcome!", "Glad I could help!", "Anytime!"]),
    (r"quit|exit|bye", ["Goodbye! Have a great day.", "See you later!"])
]

def start_chat():
    print("Welcome to AI Support Bot! Type 'quit' to exit.")
    
    # চ্যাটবট তৈরি করা
    # reflections হলো কিছু কমন শব্দের পরিবর্তন (যেমন: 'I am' কে 'You are' করে দেওয়া)
    chat = Chat(pairs, reflections)
    
    # চ্যাট শুরু করা
    chat.converse()

if __name__ == "__main__":
    start_chat()
```

> [!TIP] 
> **বিঃদ্রঃ** এটি একটি রুল-বেসড (Rule-based) চ্যাটবট। আপনি `pairs` লিস্টের ভেতরে রেগুলার এক্সপ্রেশন (`r"..."`) ব্যবহার করে আরও অনেক ধরনের প্রশ্ন ও উত্তরের প্যাটার্ন যোগ করে আপনার চ্যাটবটকে আরও স্মার্ট করে তুলতে পারেন!

---