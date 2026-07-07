## ৭. অবজেক্ট ওরিয়েন্টেড প্রোগ্রামিং (OOP)

OOP হলো ক্লাস এবং অবজেক্ট ব্যবহার করে কোড লেখার একটি কাঠামো।

* **Class (ক্লাস):** এটি হলো একটি নকশা বা ব্লু-প্রিন্ট।
* **Object (অবজেক্ট):** ক্লাসের নকশা অনুযায়ী তৈরি বাস্তব জিনিস।

```python
class Car:
    # Constructor (__init__) - অবজেক্ট তৈরির সময় কল হয়
    def __init__(self, brand, color):
        self.brand = brand  # Attribute বা বৈশিষ্ট্য
        self.color = color
    
    # Method বা কাজ
    def start_engine(self):
        print(f"The {self.color} {self.brand}'s engine is starting...")

# অবজেক্ট (Object) তৈরি করা
my_car = Car("Toyota", "Red")
my_car.start_engine() # The Red Toyota's engine is starting...
```

**ইনহেরিটেন্স (Inheritance):** এক ক্লাসের বৈশিষ্ট্য অন্য ক্লাসে ব্যবহার করা।
```python
class ElectricCar(Car): # Car ক্লাসকে ইনহেরিট করছে
    def __init__(self, brand, color, battery_capacity):
        super().__init__(brand, color) # প্যারেন্ট ক্লাসের __init__ কল করা
        self.battery_capacity = battery_capacity
        
    def charge(self):
        print("Charging the battery...")
```

---