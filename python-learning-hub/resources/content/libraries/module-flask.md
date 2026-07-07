# Flask (Zero to Hero) কমপ্লিট গাইড

পাইথন দিয়ে ওয়েবসাইট বা ওয়েব অ্যাপ্লিকেশন বানানোর জন্য সবচেয়ে সহজ, সিম্পল এবং বিগিনার-ফ্রেন্ডলি ফ্রেমওয়ার্ক হলো **Flask** (ফ্লাস্ক)। 

জ্যাঙ্গো (Django) যেখানে সবকিছু নিজে থেকেই দিয়ে দেয়, ফ্লাস্ক সেখানে সম্পূর্ণ বিপরীত! ফ্লাস্ক হলো একটি **Microframework (মাইক্রোফ্রেমওয়ার্ক)**। এটি আপনাকে শুধু একটি খালি ক্যানভাস দিবে। আপনার যা যা লাগবে (যেমন ডেটাবেস, ফর্ম ভ্যালিডেশন, লগিন), আপনি শুধু প্রয়োজনীয় এক্সটেনশনগুলো বাইরে থেকে অ্যাড করে নিবেন। এই স্বাধীনতার কারণেই ডেভেলপাররা ফ্লাস্ককে এত ভালোবাসেন!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের রাউটিং থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Flask-SQLAlchemy এবং Blueprints পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং প্রথম ফ্লাস্ক অ্যাপ (Setup)
প্রথমে আপনার কম্পিউটারে ফ্লাস্ক ইনস্টল করতে হবে:
```bash
pip install Flask
```

এবার `app.py` নামে একটি ফাইল বানিয়ে বেসিক কোড লিখুন:
```python
from flask import Flask

# ১. ফ্লাস্ক অ্যাপ তৈরি করা
app = Flask(__name__)

# ২. রাউটিং (URL এ গেলে কী দেখাবে)
@app.route("/")
def home():
    return "<h1>Hello, Welcome to Flask!</h1>"

# ৩. অ্যাপ রান করা
if __name__ == "__main__":
    app.run(debug=True)
```
**রান করার নিয়ম:** টার্মিনালে শুধু লিখুন `python app.py`। `debug=True` থাকার কারণে কোডে কোনো পরিবর্তন করে সেভ করলেই সার্ভার নিজে রিস্টার্ট নিবে।

### ২. ডাইনামিক রাউটিং (Dynamic Routes)
লিংকের (URL) ভেতর থেকে ডাইনামিকভাবে ডেটা রিসিভ করা ফ্লাস্কে খুবই সহজ।

```python
# /user/Rahim লিংকে গেলে name এর ভ্যালু হবে Rahim
@app.route("/user/<name>")
def user_profile(name):
    return f"<h3>Hello, {name}! This is your profile.</h3>"

# লিংকে ইন্টিজার (Number) রিসিভ করা (যেমন: /post/12)
@app.route("/post/<int:post_id>")
def show_post(post_id):
    return f"Reading Post ID: {post_id}"
```

### ৩. HTML টেমপ্লেট রেন্ডারিং (Jinja2 Templates)
আলাদা HTML ফাইল শো করানোর জন্য ফ্লাস্কে `templates` নামে একটি ফোল্ডার বানাতে হয়।

**`templates/index.html` ফাইল:**
```html
<!DOCTYPE html>
<html>
<body>
    <!-- {{ }} এর ভেতর পাইথনের ভ্যারিয়েবল লেখা যায় (Jinja2 Syntax) -->
    <h1>Welcome, {{ user_name }}!</h1>
    <p>Your age is {{ age }}.</p>
</body>
</html>
```

**`app.py` ফাইল:**
```python
from flask import Flask, render_template

app = Flask(__name__)

@app.route("/")
def home():
    # render_template দিয়ে HTML ফাইল এবং সাথে ভ্যারিয়েবল পাঠানো হলো
    return render_template("index.html", user_name="Alice", age=25)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৪. HTTP Methods এবং ফর্ম হ্যান্ডলিং (GET vs POST)
যখন ইউজার কোনো লিংক ভিজিট করে, সেটি `GET` রিকোয়েস্ট। আর যখন সে লগিন ফর্ম সাবমিট করে, সেটি `POST` রিকোয়েস্ট।

```python
from flask import Flask, request, render_template

app = Flask(__name__)

# methods=['GET', 'POST'] দিয়ে বলে দেওয়া হলো যে এই লিংকটি দুটি রিকোয়েস্টই রিসিভ করবে
@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        # ফর্ম থেকে ডেটা রিসিভ করা (request.form)
        username = request.form.get("username")
        password = request.form.get("password")
        
        if username == "admin" and password == "1234":
            return "Login Successful!"
        else:
            return "Invalid Credentials!"
            
    # যদি GET রিকোয়েস্ট হয়, তবে ফর্মের HTML পেজ দেখাবে
    return render_template("login.html")
```

### ৫. সেশন এবং কুকিজ (Sessions)
ইউজার লগিন করার পর তাকে সিস্টেমে মনে রাখার জন্য (যেন বারবার লগিন করতে না হয়) সেশন ব্যবহার করা হয়।

```python
from flask import Flask, session, redirect, url_for

app = Flask(__name__)
# সেশনের সিকিউরিটির জন্য একটি সিক্রেট কি (Secret Key) দিতে হয়
app.secret_key = "super_secret_key"

@app.route("/login")
def set_session():
    # সেশনে ইউজারের নাম সেভ করা
    session['user'] = 'admin'
    return redirect(url_for('dashboard'))

@app.route("/dashboard")
def dashboard():
    # সেশন থেকে ইউজারের নাম পড়া
    if 'user' in session:
        return f"Welcome to Dashboard, {session['user']}!"
    return "You are not logged in!"

@app.route("/logout")
def logout():
    # সেশন ডিলিট করা
    session.pop('user', None)
    return "Logged out successfully!"
```

### ৬. ফ্ল্যাশ মেসেজ (Flash Messages)
ইউজার ভুল পাসওয়ার্ড দিলে বা সাকসেসফুলি কোনো ডেটা সেভ করলে স্ক্রিনে একবারের জন্য একটি পপআপ বা এলার্ট দেখানোর জন্য `flash()` ব্যবহার করা হয়।

```python
from flask import Flask, flash, redirect, url_for, render_template

app = Flask(__name__)
app.secret_key = "secret123" # ফ্ল্যাশ মেসেজ সেশন ব্যবহার করে, তাই এটি লাগবেই

@app.route("/save")
def save_data():
    # ডেটা সেভ করার পর একটি মেসেজ তৈরি করা
    flash("Data saved successfully!", "success")
    return redirect(url_for('home'))
```
*(পরবর্তীতে HTML ফাইলে `get_flashed_messages()` লুপ চালিয়ে মেসেজগুলো দেখাতে হয়।)*

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৭. ফ্লাস্কে ডেটাবেস যুক্ত করা (Flask-SQLAlchemy)
ফ্লাস্কে বাই-ডিফল্ট কোনো ডেটাবেস থাকে না। ডেটাবেস নিয়ে কাজ করার জন্য আমরা সবচেয়ে জনপ্রিয় এক্সটেনশন **Flask-SQLAlchemy** ব্যবহার করি।

প্রথমে ইনস্টল করুন: `pip install flask-sqlalchemy`

```python
from flask import Flask
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
# SQLite ডেটাবেসের কনফিগারেশন
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///database.db'
db = SQLAlchemy(app)

# ১. ডেটাবেস টেবিল বা মডেল তৈরি করা
class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)

# টার্মিনাল থেকে `python` এ ঢুকে `db.create_all()` রান করলে টেবিল তৈরি হয়ে যাবে!

# ২. ডেটাবেসে ইনসার্ট (Insert) করা
@app.route("/add")
def add_user():
    new_user = User(username="JohnDoe")
    db.session.add(new_user)
    db.session.commit()
    return "User added!"

# ৩. ডেটাবেস থেকে রিড (Read) করা
@app.route("/users")
def get_users():
    users = User.query.all()
    names = [u.username for u in users]
    return f"All users: {names}"
```

### ৮. ব্লুপ্রিন্টস (Blueprints for Large Apps)
আপনার ওয়েবসাইট যখন অনেক বড় হয়ে যাবে, তখন সব কোড একটি `app.py` ফাইলে রাখলে সেটি পড়া অসম্ভব হয়ে যাবে। কোডকে বিভিন্ন মডিউল বা অ্যাপে ভাগ করার জন্য **Blueprint** ব্যবহার করা হয় (এটি জ্যাঙ্গোর 'App' এর মতো কাজ করে)।

```python
# auth.py (আলাদা ফাইলে)
from flask import Blueprint

# একটি ব্লুপ্রিন্ট তৈরি করা
auth_bp = Blueprint('auth', __name__)

@auth_bp.route('/login')
def login():
    return "Login Page"

@auth_bp.route('/register')
def register():
    return "Register Page"
```

এবার মেইন `app.py` তে এই ব্লুপ্রিন্টটিকে রেজিস্টার করে নিতে হবে:
```python
# app.py
from flask import Flask
from auth import auth_bp

app = Flask(__name__)

# ব্লুপ্রিন্ট যুক্ত করা এবং লিংকের প্রিফিক্স দেওয়া
app.register_blueprint(auth_bp, url_prefix='/auth')

# এখন ইউজারের লিংকগুলো হবে: http://127.0.0.1:5000/auth/login
```

### ৯. JSON API তৈরি করা (Building REST APIs)
ফ্লাস্ক দিয়ে API বানানো একদম পানির মতো সোজা। `jsonify` ব্যবহার করে আপনি যেকোনো ডিকশনারিকে JSON এ রূপান্তর করে রিটার্ন করতে পারেন।

```python
from flask import Flask, jsonify

app = Flask(__name__)

@app.route("/api/v1/products")
def get_products():
    products = [
        {"id": 1, "name": "Laptop", "price": 1000},
        {"id": 2, "name": "Phone", "price": 500}
    ]
    # লিস্ট বা ডিকশনারিকে JSON ফরম্যাটে কনভার্ট করে রেসপন্স দেওয়া
    return jsonify(products)
```

### সারসংক্ষেপ (Conclusion)
আপনি যদি খুব দ্রুত একটি প্রোটোটাইপ বা ছোট-মাঝারি সাইজের ওয়েবসাইট বানাতে চান, অথবা মেশিন লার্নিং মডেলকে API এর মাধ্যমে সার্ভ করতে চান, তবে **Flask** এর চেয়ে সহজ আর কোনো ফ্রেমওয়ার্ক নেই! এটি আপনাকে শেখাবে কীভাবে একটি ওয়েব ফ্রেমওয়ার্ক ব্যাকএন্ডে কাজ করে।
