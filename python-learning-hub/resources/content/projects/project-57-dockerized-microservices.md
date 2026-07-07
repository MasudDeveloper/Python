# ৫৭. ডকারাইজড মাইক্রোসার্ভিসেস (Dockerized Microservices)

গুগল, নেটফ্লিক্স বা উবারের মতো বড় কোম্পানিগুলো কোনো সিঙ্গেল (Monolithic) সার্ভার ব্যবহার করে না। তারা তাদের পুরো ওয়েবসাইটকে ছোট ছোট শত শত এপিআই (API) বা সার্ভারে ভাগ করে ফেলে (যাকে Microservices বলে)। এরপর সেগুলো **ডকার (Docker)** এর মাধ্যমে রান করে। 

ডকার হলো এমন একটি প্রযুক্তি যা আপনার পুরো প্রজেক্ট, পাইথনের ভার্সন এবং সব লাইব্রেরিকে একটি প্যাকেটের (Container) ভেতর ঢুকিয়ে ফেলে, যাতে এটি পৃথিবীর যেকোনো কম্পিউটারে হুবহু একইভাবে রান করে (কোনো "It works on my machine" এরর ছাড়াই!)। 

### কীভাবে কাজ করে? (How it works):
1. **Microservices (FastAPI):** আমরা পাইথনের `FastAPI` ব্যবহার করে দুটি আলাদা মিনি-সার্ভার বানাবো। একটি শুধু ইউজারদের ডেটা সামলাবে, আরেকটি পেমেন্ট সামলাবে।
2. **Dockerfile:** প্রতিটি সার্ভারের জন্য একটি করে `Dockerfile` লিখবো, যা বলে দিবে এই সার্ভার চালাতে কী কী সফটওয়্যার লাগবে।
3. **Docker Compose:** যেহেতু আমাদের সার্ভার একাধিক, তাই আমরা `docker-compose.yml` ফাইল ব্যবহার করে একটি কমান্ড দিয়েই সবগুলো সার্ভার একসাথে চালু করবো।

### প্রয়োজনীয় সফটওয়্যার:
এই প্রজেক্টের জন্য আপনার কম্পিউটারে **Docker Desktop** ইনস্টল থাকতে হবে (যা docker.com থেকে ডাউনলোড করা যায়)।

### প্রজেক্টের ফাইল স্ট্রাকচার:
এই প্রজেক্টটি একটি সিঙ্গেল পাইথন ফাইল নয়, এর জন্য একটি ফোল্ডার স্ট্রাকচার লাগবে:
```text
my_microservices/
├── user_service/
│   ├── main.py
│   ├── requirements.txt
│   └── Dockerfile
├── payment_service/
│   ├── main.py
│   ├── requirements.txt
│   └── Dockerfile
└── docker-compose.yml
```

### প্রজেক্টের কোড:

**১. User Service (ইউজার সার্ভিস):**
`user_service/main.py`
```python
from fastapi import FastAPI

app = FastAPI()

@app.get("/users")
def get_users():
    return {"users": ["Alice", "Bob", "Charlie"]}
```
`user_service/Dockerfile`
```dockerfile
# বেজ ইমেজ (Python 3.9)
FROM python:3.9-slim

# ফোল্ডার ডিরেক্টরি সেট করা
WORKDIR /app

# লাইব্রেরি ইনস্টল করা (যেমন fastapi, uvicorn)
COPY requirements.txt .
RUN pip install -r requirements.txt

# কোড কপি করা
COPY . .

# সার্ভার রান করার কমান্ড
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000"]
```

**২. Payment Service (পেমেন্ট সার্ভিস):**
`payment_service/main.py`
```python
from fastapi import FastAPI

app = FastAPI()

@app.get("/payments")
def get_payments():
    return {"status": "Success", "amount": 500}
```
`payment_service/Dockerfile`
*(ইউজার সার্ভিসের মতোই হুবহু একই কোড, শুধু পোর্টের জায়গায় `8001` হবে।)*

**৩. Docker Compose (সব একসাথে রান করার জাদুকরী ফাইল):**
`docker-compose.yml` (এটি মেইন ফোল্ডারে থাকবে)
```yaml
version: "3.8"
services:
  users_api:
    build: ./user_service
    ports:
      - "8000:8000"
      
  payments_api:
    build: ./payment_service
    ports:
      - "8001:8001"
```

### কীভাবে রান করবেন?
টার্মিনালে `my_microservices/` ফোল্ডারে গিয়ে শুধু একটি কমান্ড লিখতে হবে:
```bash
docker-compose up --build
```
ম্যাজিকের মতো ডকার নিজে থেকেই পাইথন ডাউনলোড করবে, আপনার দুটি সার্ভার তৈরি করবে এবং একটিকে ৮০০০ পোর্টে এবং অন্যটিকে ৮০০১ পোর্টে চালু করে দিবে! 

### কোডটি কীভাবে শিখবেন?
1. **FROM python:3.9-slim:** ডকারফাইলের এই লাইনটির মানে হলো, ডকার প্রথমে একটি ফ্রেশ লিনাক্স (Linux) অপারেটিং সিস্টেম নিবে যার ভেতর শুধু Python 3.9 ইনস্টল করা আছে। 
2. **WORKDIR /app:** কন্টেইনারের ভেতর সব ফাইল `/app` ফোল্ডারে রাখা হবে। 
3. **docker-compose:** এটি মূলত একাধিক ডকার কন্টেইনারকে একসাথে ম্যানেজ করার একটি টুল। যদি আমাদের ১০টি মাইক্রোসার্ভিস থাকতো, তবে ম্যানুয়ালি রান না করে আমরা এই একটি `yml` ফাইলে সবকিছুর নাম লিখে `up` কমান্ড দিলেই সব একসাথে চালু হয়ে যেতো! বড় কোম্পানিতে এভাবেই কাজ হয়।
