## ৩০. লাইভ প্রজেক্ট: সিকিউরড টু-ডু লিস্ট এপিআই (Secured To-Do API with Login)

ওয়েব বা ব্যাকএন্ড ডেভেলপমেন্টে ক্যারিয়ার গড়তে চাইলে শুধু ওয়েবসাইট বানালেই হবে না, আপনাকে API (Application Programming Interface) বানানো শিখতে হবে। যখন আপনি মোবাইল অ্যাপ বা ফ্রন্টএন্ড থেকে সার্ভারে ডেটা পাঠান, তখন এই API ই সেই ডেটা রিসিভ করে। এই প্রজেক্টে আমরা `FastAPI` ব্যবহার করে এমন একটি API বানাবো যেখানে ইউজাররা লগইন করতে পারবে এবং নিজেদের ব্যক্তিগত টাস্ক (Task) সেভ করতে পারবে। 

### কীভাবে কাজ করে? (How it works):
এখানে আমরা ইউজারের পাসওয়ার্ড সরাসরি সেভ না করে সেটিকে এনক্রিপ্ট (Encrypt) করে সেভ করবো। যখন ইউজার সঠিক পাসওয়ার্ড দিয়ে লগইন করবে, তখন API তাকে একটি `JWT (JSON Web Token)` দিবে। এই টোকেনটি অনেকটা ডিজিটাল আইডি কার্ডের মতো। এরপর ইউজার যখনই কোনো নতুন টাস্ক যোগ করতে বা দেখতে চাইবে, তাকে এই টোকেনটি দেখাতে হবে। ফলে একজনের টাস্ক অন্য কেউ দেখতে পারবে না!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের বেশ কয়েকটি আধুনিক লাইব্রেরি লাগবে। 
- **FastAPI:** সবচেয়ে দ্রুতগামী (Fastest) পাইথন ওয়েব ফ্রেমওয়ার্ক।
- **Uvicorn:** FastAPI সার্ভার রান করার জন্য।
- **PyJWT & Passlib:** পাসওয়ার্ড হ্যাশিং (Hashing) এবং টোকেন জেনারেট করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install fastapi uvicorn PyJWT passlib[bcrypt]
```

### প্রজেক্টের কোড:

নিচের কোডটি `main.py` নামে একটি ফাইলে সেভ করুন। এই প্রজেক্টটি তুলনামূলক বড়, কারণ এতে রিয়েল-ওয়ার্ল্ড ব্যাকএন্ডের সিকিউরিটি কনসেপ্টগুলো দেওয়া আছে।

```python
from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel
from passlib.context import CryptContext
import jwt
import datetime

# সিকিউরিটি কনফিগারেশন
SECRET_KEY = "my_super_secret_key"
ALGORITHM = "HS256"
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

app = FastAPI(title="Secured To-Do API")

# ডেটাবেসের বদলে ডিকশনারি ব্যবহার করছি (প্র্যাকটিসের জন্য)
fake_users_db = {}
fake_tasks_db = {}

# ডেটা মডেল (ইউজার ইনপুট কেমন হবে তা নির্ধারণ করা)
class UserSignup(BaseModel):
    username: str
    password: str

class UserLogin(BaseModel):
    username: str
    password: str

class Task(BaseModel):
    title: str
    description: str

# 1. পাসওয়ার্ড হ্যাশ (লুকানো) করার ফাংশন
def hash_password(password: str):
    return pwd_context.hash(password)

# 2. পাসওয়ার্ড চেক করার ফাংশন
def verify_password(plain_password, hashed_password):
    return pwd_context.verify(plain_password, hashed_password)

# 3. JWT টোকেন তৈরি করার ফাংশন
def create_jwt_token(username: str):
    expire = datetime.datetime.utcnow() + datetime.timedelta(hours=1)
    to_encode = {"sub": username, "exp": expire}
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

# রুট ১: ইউজার সাইনআপ (অ্যাকাউন্ট খোলা)
@app.post("/signup")
def signup(user: UserSignup):
    if user.username in fake_users_db:
        raise HTTPException(status_code=400, detail="Username already exists")
    
    # পাসওয়ার্ড হ্যাশ করে সেভ করা হচ্ছে
    fake_users_db[user.username] = {
        "username": user.username,
        "password": hash_password(user.password)
    }
    # ইউজারের জন্য একটি খালি টাস্ক লিস্ট তৈরি করে দেওয়া হলো
    fake_tasks_db[user.username] = []
    
    return {"message": "User created successfully! Please login."}

# রুট ২: ইউজার লগইন এবং টোকেন জেনারেট
@app.post("/login")
def login(user: UserLogin):
    db_user = fake_users_db.get(user.username)
    
    # ইউজারনেম এবং পাসওয়ার্ড চেক করা
    if not db_user or not verify_password(user.password, db_user["password"]):
        raise HTTPException(status_code=401, detail="Invalid username or password")
    
    # সঠিক হলে টোকেন দিয়ে দেওয়া
    token = create_jwt_token(user.username)
    return {"access_token": token, "token_type": "bearer"}

# রুট ৩: টাস্ক যোগ করা (এখানে টোকেন লাগবে)
@app.post("/tasks")
def add_task(task: Task, token: str):
    try:
        # টোকেন থেকে ইউজারের নাম বের করা
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username = payload.get("sub")
        
        # ইউজারের লিস্টে টাস্ক যোগ করা
        fake_tasks_db[username].append(task.dict())
        return {"message": "Task added successfully!", "task": task}
        
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Token has expired")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

# রুট ৪: নিজের টাস্ক দেখা (এখানে টোকেন লাগবে)
@app.get("/tasks")
def get_tasks(token: str):
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username = payload.get("sub")
        return {"user": username, "tasks": fake_tasks_db.get(username, [])}
        
    except Exception:
        raise HTTPException(status_code=401, detail="Invalid token")

if __name__ == "__main__":
    import uvicorn
    # সার্ভারটি 8000 পোর্টে রান করা হবে
    uvicorn.run(app, host="127.0.0.1", port=8000)
```

> [!TIP]
> **টিপস:** কোডটি রান করার পর আপনার ব্রাউজারে `http://127.0.0.1:8000/docs` লিংকে যান। FastAPI এর সবচেয়ে দারুণ ফিচার হলো, এটি অটোমেটিকভাবে আপনার API এর জন্য একটি সুন্দর ইউজার-ইন্টারফেস (Swagger UI) তৈরি করে দেয়! সেখান থেকেই আপনি সাইনআপ, লগইন এবং টাস্ক অ্যাড করার কাজগুলো টেস্ট করতে পারবেন।

### কোডটি কীভাবে শিখবেন?
1. **Password Hashing:** আমরা সরাসরি `1234` এর মতো পাসওয়ার্ড ডেটাবেসে রাখি না, কারণ হ্যাকাররা ডেটাবেস চুরি করলে পাসওয়ার্ড পেয়ে যাবে। `passlib` ব্যবহার করে কীভাবে পাসওয়ার্ডকে হিব্রিজিবি লেখায় (Hash) রূপান্তর করতে হয়, তা আপনি শিখতে পারবেন।
2. **JWT Authentication:** একবার লগইন করার পর সার্ভার কীভাবে ইউজারকে চিনতে পারে? `PyJWT` ব্যবহার করে একটি টাইম-লিমিটেড ডিজিটাল টোকেন তৈরি করা এবং সেই টোকেন চেক করে অ্যাক্সেস দেওয়া হলো মডার্ন ওয়েব ডেভেলপমেন্টের সবচেয়ে গুরুত্বপূর্ণ পার্ট।
3. **FastAPI Routing:** `@app.post()` এবং `@app.get()` ব্যবহার করে কীভাবে আলাদা আলাদা ইউআরএল (যেমন `/signup`, `/login`) এর জন্য আলাদা ফাংশন তৈরি করতে হয় এবং JSON ডেটা আদানপ্রদান করতে হয়, তা আয়ত্ত করতে পারবেন।

---