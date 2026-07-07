# Object-Oriented Programming (OOP) - জিরো টু হিরো গাইড

প্রোগ্রামিংয়ের সবচেয়ে গুরুত্বপূর্ণ এবং শক্তিশালী কনসেপ্ট হলো **Object-Oriented Programming (OOP)**। আপনি যখন বড় কোনো প্রজেক্ট (যেমন: ওয়েব অ্যাপ্লিকেশন, গেম বা মেশিন লার্নিং মডেল) বানাবেন, তখন সাধারণ ফাংশন দিয়ে কোড লিখলে তা একসময় জগাখিচুড়ি হয়ে যাবে।

OOP এর মাধ্যমে আমরা কোডকে বাস্তব জীবনের বস্তুর (Object) সাথে তুলনা করে সাজিয়ে লিখতে পারি। 

এই টিউটোরিয়ালে আমরা একদম বেসিক ক্লাস তৈরি থেকে শুরু করে ইনহেরিটেন্স (Inheritance) এবং ম্যাজিক মেথড (Magic Methods) পর্যন্ত সবকিছু শিখবো।

---

## ১. ক্লাস (Class) এবং অবজেক্ট (Object)
**ক্লাস (Class)** হলো একটি নকশা বা ব্লু-প্রিন্ট। আর **অবজেক্ট (Object)** হলো সেই নকশা দেখে তৈরি করা বাস্তব জিনিস।
যেমন: "গাড়ি" হলো একটি ক্লাস। আর আপনার বাসার "লাল রঙের টয়োটা গাড়িটি" হলো একটি অবজেক্ট।

```python
# ১. একটি ক্লাস তৈরি করা (ক্লাসের নাম সাধারণত বড় হাতের অক্ষর দিয়ে শুরু হয়)
class Car:
    # ক্লাসের ভেতরের ফাংশনগুলোকে মেথড (Method) বলে
    def start_engine(self):
        print("ভুরররুম! ইঞ্জিন চালু হয়েছে!")

# ২. ক্লাস থেকে অবজেক্ট তৈরি করা
my_car = Car()
friend_car = Car()

# ৩. অবজেক্টের মেথড কল করা
my_car.start_engine()
```

## ২. `__init__` এবং `self` (ম্যাজিক কনস্ট্রাক্টর)
প্রতিটি গাড়ির রং এবং মডেল আলাদা হয়। এগুলোকে বলে প্রোপার্টি (Property) বা অ্যাট্রিবিউট। অবজেক্ট তৈরি হওয়ার সাথে সাথেই যেন প্রোপার্টিগুলো সেট হয়ে যায়, সেজন্য `__init__` মেথড ব্যবহার করা হয়। একে **Constructor** বলে।

আর `self` মানে হলো "আমি নিজে"। অর্থাৎ কোন অবজেক্টটি এই মুহূর্তে কাজ করছে, তা বোঝানোর জন্য `self` লেখা বাধ্যতামূলক।

```python
class Car:
    # অবজেক্ট তৈরি হলেই এই ফাংশনটি অটোমেটিক রান হবে
    def __init__(self, brand, color):
        self.brand = brand # আমার (self) ব্র্যান্ড হলো ইউজারের দেওয়া brand
        self.color = color
        print(f"একটি নতুন {self.color} রঙের {self.brand} গাড়ি তৈরি হয়েছে!")

    def show_info(self):
        print(f"এটি একটি {self.color} {self.brand} গাড়ি।")

# অবজেক্ট তৈরির সময় ডেটা পাস করা
car1 = Car("Toyota", "Red")
car2 = Car("BMW", "Black")

# মেথড কল করা
car1.show_info()
```

## ৩. ইনহেরিটেন্স (Inheritance - উত্তরাধিকার)
ধরুন আমরা একটি `ElectricCar` ক্লাস বানাবো। ইলেকট্রিক গাড়িরও তো কালার, ব্র্যান্ড এবং `show_info` মেথড থাকবে। আমরা চাইলে আগের `Car` ক্লাসের সবকিছু নতুন ক্লাসে কপি (Inherit) করে নিয়ে আসতে পারি! এতে কোড ডুপ্লিকেট হয় না।

```python
# আগের Car ক্লাসটি
class Car:
    def __init__(self, brand):
        self.brand = brand
        
    def drive(self):
        print(f"{self.brand} গাড়িটি চলছে...")

# নতুন ক্লাস (ব্র্যাকেটে আগের ক্লাসের নাম দিলে তার সব ক্ষমতা পেয়ে যাবে)
class ElectricCar(Car):
    def charge_battery(self):
        print(f"{self.brand} এর ব্যাটারি চার্জ হচ্ছে!")

# ইলেকট্রিক গাড়ির অবজেক্ট তৈরি
tesla = ElectricCar("Tesla")

# এটি Car ক্লাসের মেথডও ব্যবহার করতে পারবে!
tesla.drive() 
# এটি নিজের স্পেশাল মেথডও ব্যবহার করতে পারবে!
tesla.charge_battery()
```

## ৪. এনক্যাপসুলেশন (Encapsulation - ডেটা লুকিয়ে রাখা)
ক্লাসের ভেতরের কিছু সিক্রেট ডেটা (যেমন: ব্যাংকের ব্যালেন্স, পাসওয়ার্ড) যেন বাইরের কেউ সরাসরি অ্যাকসেস বা পরিবর্তন করতে না পারে, সেজন্য ভ্যারিয়েবলের আগে দুটি আন্ডারস্কোর `__` দেওয়া হয়। একে Private Variable বলে।

```python
class BankAccount:
    def __init__(self, owner, balance):
        self.owner = owner
        self.__balance = balance # প্রাইভেট ভ্যারিয়েবল (বাইরে থেকে দেখা যাবে না)

    # প্রাইভেট ডেটা দেখার জন্য একটি সেফ মেথড
    def get_balance(self):
        return self.__balance

    # প্রাইভেট ডেটা পরিবর্তন করার সেফ মেথড
    def deposit(self, amount):
        if amount > 0:
            self.__balance += amount
            print(f"{amount} টাকা জমা হয়েছে।")
        else:
            print("ভুল এমাউন্ট!")

account = BankAccount("Rahim", 5000)

print(account.owner) # এটি কাজ করবে
# print(account.__balance) # এটি এরর দিবে! (ডেটা হ্যাক করা সম্ভব না)

# সঠিক নিয়মে ব্যালেন্স দেখা
print("বর্তমান ব্যালেন্স:", account.get_balance())
```

## ৫. পলিমরফিজম (Polymorphism - বহুরূপিতা)
একই নামের মেথড ভিন্ন ভিন্ন ক্লাসে ভিন্ন ভিন্ন কাজ করা। 

```python
class Dog:
    def speak(self):
        return "ঘেউ ঘেউ!"

class Cat:
    def speak(self):
        return "মিয়াউ!"

# একটি সাধারণ ফাংশন যা যেকোনো এনিম্যাল নিতে পারে
def animal_sound(animal):
    print(animal.speak())

dog1 = Dog()
cat1 = Cat()

# একই ফাংশন, কিন্তু অবজেক্টের ওপর ভিত্তি করে আলাদা রেজাল্ট দিবে!
animal_sound(dog1)
animal_sound(cat1)
```

## ৬. ম্যাজিক মেথড (Magic Methods / Dunder Methods)
পাইথনে কিছু স্পেশাল মেথড আছে যেগুলোর আগে ও পরে ডাবল আন্ডারস্কোর থাকে (যেমন `__str__`, `__len__`, `__add__`)। এগুলোকে Dunder (Double Under) মেথড বলে।

এগুলো দিয়ে আমরা পাইথনের বিল্ট-ইন ফাংশনগুলোকে নিজেদের ক্লাসের জন্য কাস্টমাইজ করতে পারি।

```python
class Book:
    def __init__(self, title, pages):
        self.title = title
        self.pages = pages

    # অবজেক্টটিকে সরাসরি প্রিন্ট করলে কী দেখাবে, তা কন্ট্রোল করা
    def __str__(self):
        return f"বইয়ের নাম: '{self.title}'"

    # len() ফাংশন কল করলে কী রিটার্ন করবে, তা সেট করা
    def __len__(self):
        return self.pages

my_book = Book("Python Zero to Hero", 350)

# ম্যাজিক! সরাসরি অবজেক্ট প্রিন্ট করছি!
print(my_book) 

# ম্যাজিক! অবজেক্টের লেন্থ (Length) দেখছি!
print("মোট পৃষ্ঠা:", len(my_book))
```

### সারসংক্ষেপ (Conclusion)
জ্যাঙ্গো (Django) তে মডেল বানানো, ফাস্টএপিআই (FastAPI) তে রাউটার বানানো বা গেম ডেভেলপমেন্টে ক্যারেক্টার বানানো—সব জায়গাতেই এই **OOP** ব্যবহৃত হয়। বিশেষ করে `__init__`, `self` এবং `Inheritance` এর কনসেপ্ট ক্লিয়ার না থাকলে পাইথনের বড় ফ্রেমওয়ার্কগুলো বোঝা প্রায় অসম্ভব!
