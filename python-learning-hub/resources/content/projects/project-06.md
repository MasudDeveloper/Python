## ৫. ফাংশন (Functions)

ফাংশন হলো পুনব্যবহারযোগ্য কোডের ব্লক যা একটি নির্দিষ্ট কাজ করে। `def` কিওয়ার্ড দিয়ে ফাংশন তৈরি করতে হয়।

### বেসিক ফাংশন:
```python
def greet(name):
    # এই ফাংশনটি কাউকে অভিবাদন জানায়
    print(f"Hello, {name}! How are you?") # f-string ব্যবহার করে ভেরিয়েবল প্রিন্ট

greet("Rahim")
greet("Karim")
```

### রিটার্ন ভ্যালু (Return Value):
ফাংশন থেকে কোনো ফলাফল ফেরত পেতে `return` ব্যবহার করা হয়।
```python
def multiply(a, b):
    return a * b

result = multiply(4, 5)
print("Result is:", result) # 20
```

### ডিফল্ট আর্গুমেন্ট (Default Argument):
যদি ফাংশন কল করার সময় কোনো ভ্যালু দেওয়া না হয়, তবে এটি ডিফল্ট ভ্যালু ব্যবহার করবে।
```python
def say_hi(name="Guest"):
    print("Hi,", name)

say_hi("Alice")  # Hi, Alice
say_hi()         # Hi, Guest
```

### *args এবং **kwargs (অ্যাডভান্সড):
অনির্দিষ্ট সংখ্যক আর্গুমেন্ট রিসিভ করতে।
```python
# *args - অনেকগুলো পজিশনাল আর্গুমেন্ট লিস্ট (টুপল) আকারে নেয়
def add_all(*numbers):
    total = 0
    for num in numbers:
        total += num
    return total

print(add_all(1, 2, 3, 4, 5)) # 15
```

---