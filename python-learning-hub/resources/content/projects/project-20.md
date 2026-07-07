## ১৯. লাইভ প্রজেক্ট: ইউআরএল শর্টনার (URL Shortener API)

আপনি হয়তো প্রায়ই `bit.ly` বা `tinyurl` এর নাম শুনেছেন, যেগুলো বড় বড় লিংককে ছোট করে দেয়। এই প্রজেক্টে আমরা নিজেদের একটি URL Shortener তৈরি করবো। এটি পাইথনের একটি চমৎকার ব্যাকএন্ড ডেভেলপমেন্ট প্রজেক্ট যেখানে আমরা **FastAPI** ব্যবহার করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের ফাস্ট এবং আধুনিক ওয়েব ফ্রেমওয়ার্ক **FastAPI** এবং এর সার্ভার **Uvicorn** লাগবে:
1. **fastapi:** API তৈরি করার জন্য।
2. **uvicorn:** FastAPI এর কোডগুলো ব্রাউজারে রান করানোর সার্ভার।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install fastapi uvicorn
```

### প্রজেক্টের কোড:

এই প্রোগ্রামে আমরা একটি মেমরি ডিকশনারি (`url_db`) ব্যবহার করেছি লিংকগুলো সেভ করার জন্য।

```python
from fastapi import FastAPI
from fastapi.responses import RedirectResponse
import random
import string
import uvicorn

# FastAPI অ্যাপ তৈরি করা
app = FastAPI()

# ডাটাবেসের বদলে একটি ডিকশনারি ব্যবহার করছি (যেখানে ডাটা সেভ থাকবে)
url_db = {}

def generate_short_id():
    """6 ক্যারেক্টারের একটি র‍্যান্ডম শর্ট আইডি তৈরি করার ফাংশন"""
    characters = string.ascii_letters + string.digits
    return ''.join(random.choices(characters, k=6))

@app.post("/shorten/")
def shorten_url(original_url: str):
    """বড় ইউআরএল দিলে সেটি শর্ট করে সেভ করবে"""
    short_id = generate_short_id()
    # শর্ট আইডির বিপরীতে বড় লিংকটি ডিকশনারিতে সেভ করা হচ্ছে
    url_db[short_id] = original_url
    
    # আপনার লোকাল সার্ভারের শর্ট লিংক
    short_url = f"http://127.0.0.1:8000/{short_id}"
    
    return {"original_url": original_url, "short_url": short_url}

@app.get("/{short_id}")
def redirect_to_url(short_id: str):
    """শর্ট আইডিতে গেলে আসল ওয়েবসাইটে রিডাইরেক্ট (Redirect) করে দিবে"""
    original_url = url_db.get(short_id)
    
    if original_url:
        return RedirectResponse(url=original_url)
    return {"error": "URL not found!"}

# স্ক্রিপ্টটি রান করার জন্য
if __name__ == "__main__":
    print("Starting FastAPI Server...")
    uvicorn.run(app, host="127.0.0.1", port=8000)
```

> [!TIP] 
> **বিঃদ্রঃ** কোডটি রান করার পর ব্রাউজারে গিয়ে `http://127.0.0.1:8000/docs` লিংকে গেলে আপনি একটি চমৎকার ইউজার ইন্টারফেস দেখতে পাবেন (যাকে Swagger UI বলে)। সেখান থেকে আপনি `POST /shorten/` এ ক্লিক করে যেকোনো বড় লিংক দিলে সেটি আপনাকে একটি ছোট লিংক তৈরি করে দিবে। সেই ছোট লিংক ব্রাউজারে পেস্ট করলেই আপনাকে আসল সাইটে নিয়ে যাবে!

---