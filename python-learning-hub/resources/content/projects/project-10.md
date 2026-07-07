## ৯. এক্সেপশন হ্যান্ডলিং (Error Handling)

রানটাইম এরর বা এক্সেপশন (যেমন শূন্য দিয়ে ভাগ করা বা ভুল ইনপুট দেওয়া) যেন প্রোগ্রামকে ক্র্যাশ না করে, সেজন্য `try...except` ব্লক ব্যবহার করা হয়।

```python
try:
    num1 = int(input("Enter numerator: "))
    num2 = int(input("Enter denominator: "))
    result = num1 / num2
    
except ZeroDivisionError:
    print("Error: You cannot divide by zero!")
    
except ValueError:
    print("Error: Please enter valid integer numbers!")
    
else:
    # যদি কোনো error না হয়, তবে else ব্লক চলবে
    print(f"Result is: {result}")
    
finally:
    # error হোক বা না হোক, finally ব্লক সবসময় চলবে
    print("Operation executed.")
```

---