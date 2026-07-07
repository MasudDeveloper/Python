# FastAPI (Zero to Hero) কমপ্লিট গাইড

**FastAPI** হলো পাইথনের তুলনামূলক নতুন, কিন্তু বর্তমানে সবচেয়ে দ্রুত বর্ধনশীল এবং তুমুল জনপ্রিয় একটি ওয়েব ফ্রেমওয়ার্ক। উবার (Uber) এবং নেটফ্লিক্স (Netflix) এর মতো কোম্পানিগুলো তাদের রিয়েল-টাইম সার্ভিসের জন্য এটি ব্যবহার করছে।

এর নামের মতোই, এটি **ভয়ানক লেভেলের ফাস্ট (Fast)!** NodeJS বা Go ল্যাঙ্গুয়েজের স্পিডের সাথে পাল্লা দেওয়ার জন্য এটি তৈরি করা হয়েছে। এটি মূলত `asyncio` (Asynchronous) এবং `Pydantic` এর ওপর ভিত্তি করে তৈরি।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের API তৈরি থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের WebSockets এবং JWT Authentication পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক সার্ভার (Setup)
FastAPI চালানোর জন্য ফ্রেমওয়ার্কটির পাশাপাশি একটি ASGI সার্ভার ইঞ্জিন লাগে, যার নাম `uvicorn`।
```bash
pip install fastapi uvicorn
```

এবার `main.py` নামে একটি ফাইলে নিচের কোডটি লিখুন:
```python
from fastapi import FastAPI

# অ্যাপ তৈরি করা
app = FastAPI()

# একটি বেসিক GET রুট (Route)
@app.get("/")
def home():
    # সরাসরি ডিকশনারি রিটার্ন করলে এটি অটোমেটিক JSON হয়ে রেসপন্স দিবে!
    return {"message": "Welcome to FastAPI!"}
```

**রান করার নিয়ম:**
টার্মিনালে লিখুন `uvicorn main:app --reload`
*(এখানে `--reload` দিলে কোডে কোনো পরিবর্তন করে সেভ করলেই সার্ভার নিজে রিস্টার্ট নিবে।)*

### ২. পাথ এবং কোয়েরি প্যারামিটার (Path & Query Parameters)
URL এর মাধ্যমে ডেটা রিসিভ করার দুটি উপায় আছে। FastAPI পাইথনের Type Hints (`int`, `str`) ব্যবহার করে ডেটা ভ্যালিডেট করে।

```python
from fastapi import FastAPI

app = FastAPI()

# ১. পাথ প্যারামিটার (Path Parameter): লিংকের ভেতরে সরাসরি ডেটা পাঠানো
@app.get("/users/{user_id}")
def get_user(user_id: int): 
    # user_id: int দেওয়ার কারণে লিংকে টেক্সট (যেমন: /users/abc) দিলে FastAPI অটোমেটিক এরর দিবে!
    return {"user_id": user_id, "status": "Active"}

# ২. কোয়েরি প্যারামিটার (Query Parameter): লিংকের শেষে ? দিয়ে ডেটা পাঠানো
# যেমন: /search?q=python&limit=5
@app.get("/search")
def search_items(q: str, limit: int = 10): 
    # limit এর ডিফল্ট ভ্যালু 10 দেওয়া আছে
    return {"search_query": q, "results_limit": limit}
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. অটোমেটিক ডকুমেন্টেশন (Swagger UI)
FastAPI এর সবচেয়ে বড় ম্যাজিক হলো এটি! আপনার সার্ভার চালু থাকা অবস্থায় ব্রাউজারে নিচের লিংকে যান:
👉 **`http://127.0.0.1:8000/docs`**

এখানে আপনি চমৎকার একটি ইউজার ইন্টারফেস দেখতে পাবেন (Swagger UI)। আপনি আপনার বানানো সবগুলো API দেখতে পারবেন এবং **Try it out** এ ক্লিক করে ব্রাউজার থেকেই API টেস্ট করতে পারবেন!

### ৪. Pydantic দিয়ে ডেটা ভ্যালিডেশন (Request Body)
ইউজার যখন POST রিকোয়েস্টের মাধ্যমে ডেটা পাঠায় (যেমন: রেজিস্ট্রেশন ফর্ম), তখন সেই ডেটা রিসিভ ও চেক করার জন্য `Pydantic` মডেল ব্যবহার করা হয়।

```python
from fastapi import FastAPI
from pydantic import BaseModel, EmailStr

app = FastAPI()

# ডেটার স্ট্রাকচার এবং রুলস তৈরি করা
class User(BaseModel):
    name: str
    age: int
    email: EmailStr # এটি চেক করবে ইমেইলটি ভ্যালিড কি না!
    is_active: bool = True # ডিফল্ট ভ্যালু

@app.post("/create-user/")
def create_user(user: User):
    # ইউজার যদি age এর জায়গায় "twenty" লেখে, FastAPI অটোমেটিক 422 Error দিবে।
    print(f"Creating user: {user.name}, Age: {user.age}")
    
    # ডেটাবেসে সেভ করার লজিক...
    
    return {"message": "User created!", "data": user}
```

### ৫. ডিপেন্ডেন্সি ইনজেকশন (Dependency Injection)
কমন কোনো লজিক (যেমন ডেটাবেস কানেকশন বা ইউজার ভেরিফিকেশন) বারবার না লিখে রিইউজ করার জন্য `Depends` ব্যবহৃত হয়।

```python
from fastapi import FastAPI, Depends, HTTPException

app = FastAPI()

# এটি একটি ডিপেন্ডেন্সি ফাংশন
def verify_token(token: str):
    if token != "supersecret":
        raise HTTPException(status_code=401, detail="Invalid token!")
    return True

# API তে ঢোকার আগেই verify_token চেক হবে
@app.get("/secure-data", dependencies=[Depends(verify_token)])
def get_secure_data():
    return {"secret_data": "This is highly confidential!"}
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. ব্যাকগ্রাউন্ড টাস্ক (Background Tasks)
ধরি, ইউজার রেজিস্ট্রেশন করলে তাকে একটি ইমেইল পাঠাতে হবে। ইমেইল পাঠাতে ৫ সেকেন্ড লাগে। ইউজার যেন লোডিং স্ক্রিনে আটকে না থাকে, তাই কাজটি আমরা ব্যাকগ্রাউন্ডে পাঠিয়ে দিবো।

```python
from fastapi import FastAPI, BackgroundTasks
import time

app = FastAPI()

def send_welcome_email(email: str):
    time.sleep(5) # ভারী কাজ
    print(f"Email sent successfully to {email}")

@app.post("/register")
def register(email: str, bg_tasks: BackgroundTasks):
    # ব্যাকগ্রাউন্ডে কাজটি করার জন্য শিডিউল করে দেওয়া হলো
    bg_tasks.add_task(send_welcome_email, email)
    
    # ইউজার সাথে সাথেই রেসপন্স পেয়ে যাবে!
    return {"message": "Registration successful! Please check your email."}
```

### ৭. JWT অথেনটিকেশন (Authentication)
মডার্ন API তে লগিন সিস্টেমের জন্য Token (JWT - JSON Web Token) ব্যবহার করা হয়। FastAPI এর সিকিউরিটি মডিউল এটি খুব সহজে করতে পারে।

```python
from fastapi import FastAPI, Depends
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm

app = FastAPI()

# টোকেন রিসিভ করার রুট সেট করা
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="login")

# ১. লগিন API (যেখান থেকে ইউজার টোকেন পাবে)
@app.post("/login")
def login(form_data: OAuth2PasswordRequestForm = Depends()):
    # রিয়েল লাইফে এখানে ডেটাবেস চেক হবে
    if form_data.username == "admin" and form_data.password == "1234":
        return {"access_token": "my_fake_jwt_token", "token_type": "bearer"}
    return {"error": "Invalid login"}

# ২. প্রোটেক্টেড API (যেখানে টোকেন ছাড়া ঢোকা যাবে না)
@app.get("/my-profile")
def profile(token: str = Depends(oauth2_scheme)):
    # এখানে টোকেন ভ্যালিডেট করা হবে
    return {"user": "admin", "token_used": token}
```
*(Swagger UI তে গেলে দেখবেন ওপরের ডান কোনায় একটি **"Authorize"** বাটন চলে এসেছে!)*

### ৮. রিয়েল-টাইম WebSockets
চ্যাট অ্যাপ্লিকেশন বা লাইভ নোটিফিকেশনের জন্য WebSockets দরকার হয়। FastAPI তে এটি বিল্ট-ইন থাকে।

```python
from fastapi import FastAPI, WebSocket

app = FastAPI()

@app.websocket("/ws/chat")
async def websocket_endpoint(websocket: WebSocket):
    # ইউজারের কানেকশন অ্যাকসেপ্ট করা
    await websocket.accept()
    
    while True:
        # ইউজারের মেসেজ রিসিভ করা
        data = await websocket.receive_text()
        print(f"Message received: {data}")
        
        # ইউজারের কাছে মেসেজ পাঠানো
        await websocket.send_text(f"Server says: You sent -> {data}")
```

### সারসংক্ষেপ (Conclusion)
আপনি যদি মডার্ন কোনো SPA (Single Page Application) যেমন React, Vue বা Flutter মোবাইল অ্যাপের জন্য একটি ফাস্ট, সিকিউর এবং প্রোডাকশন-রেডি API ব্যাকএন্ড বানাতে চান, তবে **FastAPI** বর্তমানে আপনার জন্য সবচেয়ে সেরা পছন্দ। এর টাইপ হিন্টিং এবং অটোমেটিক ডকুমেন্টেশনের কারণে ডেভেলপারদের লাইফ অনেক সহজ হয়ে গেছে!
