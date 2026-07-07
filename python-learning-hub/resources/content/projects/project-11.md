## ১০. মডিউল এবং প্যাকেজ (Modules and Packages)

অন্য ফাইলে লেখা কোড বা পাইথনের বিল্ট-ইন ফাংশনালিটি ব্যবহার করার পদ্ধতি।

### বিল্ট-ইন মডিউল ব্যবহার:
```python
import math
import random
import datetime

print(math.factorial(5))         # 120
print(random.randint(1, 10))     # ১ থেকে ১০ এর মধ্যে র‍্যান্ডম সংখ্যা
print(datetime.datetime.now())   # বর্তমান সময়
```

### নির্দিষ্ট ফাংশন ইম্পোর্ট করা:
```python
from math import pi, sqrt
print(sqrt(25))  # 5.0
print(pi)        # 3.14159...
```

### থার্ড-পার্টি প্যাকেজ (pip):
অনলাইনে থাকা লক্ষ লক্ষ পাইথন প্যাকেজ ব্যবহার করতে টার্মিনালে `pip` কমান্ড ব্যবহার করতে হয়।
```bash
pip install requests
```

---