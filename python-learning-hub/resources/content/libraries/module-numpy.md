# NumPy (Zero to Hero) কমপ্লিট গাইড

ডেটা সায়েন্স, মেশিন লার্নিং (Machine Learning) এবং সাইন্টিফিক কম্পিউটিংয়ের দুনিয়ায় **NumPy** (Numerical Python) হলো সবচেয়ে মৌলিক এবং পাওয়ারফুল লাইব্রেরি। 

আপনি যদি পাইথনে সাধারণ `list` দিয়ে ১০ লক্ষ ডেটা নিয়ে কাজ করতে যান, তবে আপনার কম্পিউটার স্লো হয়ে যাবে বা হ্যাং করবে। কিন্তু NumPy ব্যবহার করলে সেই একই কাজ আপনি **৫০ গুণ দ্রুত** করতে পারবেন! 

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের লিনিয়ার অ্যালজেব্রা এবং ভেক্টরাইজেশন পর্যন্ত সবকিছু বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং ইমপোর্ট
প্রথমে টার্মিনালে NumPy ইনস্টল করে নিন:
```bash
pip install numpy
```
কোডে ইমপোর্ট করার সময় আমরা সাধারণত একে `np` হিসেবে ইমপোর্ট করি, যা বিশ্বজুড়ে স্ট্যান্ডার্ড প্র্যাকটিস।
```python
import numpy as np
```

### ২. সাধারণ লিস্ট বনাম NumPy Array
NumPy এর মূল শক্তি হলো এর `ndarray` (N-dimensional Array)। 
```python
import numpy as np

py_list = [1, 2, 3, 4, 5]
np_array = np.array([1, 2, 3, 4, 5])

print(type(py_list))  # <class 'list'>
print(type(np_array)) # <class 'numpy.ndarray'>
```

**কেন NumPy এত ফাস্ট?** 
সাধারণ পাইথন লিস্ট মেমোরির বিভিন্ন জায়গায় ছড়িয়ে ছিটিয়ে ডেটা সেভ করে। কিন্তু NumPy C-ল্যাঙ্গুয়েজের মতো মেমোরিতে ডেটাগুলোকে এক জায়গায় সিরিয়ালি (Contiguous Memory) সাজিয়ে রাখে, তাই প্রসেসর খুব দ্রুত সেগুলো পড়তে পারে।

### ৩. বিভিন্ন ডাইমেনশনের (Dimension) অ্যারে তৈরি করা
ইমেজ প্রসেসিং বা ডিপ লার্নিংয়ে ডাইমেনশন বোঝাটা সবচেয়ে জরুরি।
```python
# 0-D Array (শুধুমাত্র একটি নাম্বার বা Scalar)
arr_0d = np.array(42)

# 1-D Array (সাধারণ লিস্ট বা Vector)
arr_1d = np.array([1, 2, 3])

# 2-D Array (ম্যাট্রিক্স - শাড়ি এবং কলাম থাকবে)
arr_2d = np.array([[1, 2, 3], [4, 5, 6]])

# 3-D Array (একাধিক 2D ম্যাট্রিক্সের সমষ্টি - যেমন কালার ছবি)
arr_3d = np.array([[[1, 2], [3, 4]], [[5, 6], [7, 8]]])

# ডাইমেনশন চেক করার নিয়ম (.ndim)
print("1D Array Dimension:", arr_1d.ndim)
print("3D Array Dimension:", arr_3d.ndim)
```

### ৪. রেডিমেড অ্যারে তৈরি করার জাদুকরী ফাংশনসমূহ
সবসময় হাতে টাইপ করে অ্যারে তৈরি করতে হয় না, NumPy এর অনেক জাদুকরী ফাংশন আছে।
```python
# ১. সব ভ্যালু ০ (Zero) হবে (৩ সারি, ৪ কলাম)
zeros = np.zeros((3, 4))

# ২. সব ভ্যালু ১ (One) হবে (২ সারি, ২ কলাম)
ones = np.ones((2, 2))

# ৩. যেকোনো একটি ফিক্সড ভ্যালু দিয়ে পূরণ করা (যেমন 7)
sevens = np.full((3, 3), 7)

# ৪. একটি নির্দিষ্ট রেঞ্জের সংখ্যা (start, end, step)
range_arr = np.arange(10, 50, 5) # 10 থেকে 50 এর মধ্যে 5 করে বাড়বে

# ৫. দুটি সংখ্যার মাঝখানে সমান দূরত্বের (Equally spaced) নির্দিষ্ট পরিমাণ সংখ্যা
linear_arr = np.linspace(0, 100, 5) # 0 থেকে 100 এর মধ্যে 5টি সংখ্যা

# ৬. আইডেন্টিটি ম্যাট্রিক্স (Identity Matrix - কর্ণ বরাবর 1 থাকবে)
eye_matrix = np.eye(3)
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৫. অ্যারের প্রপার্টি বা বৈশিষ্ট্য (Attributes)
একটি অ্যারের সাইজ বা গঠন জানার জন্য এর কিছু অ্যাট্রিবিউট ব্যবহার করা হয়।
```python
arr = np.array([[10, 20, 30], [40, 50, 60]])

print("Shape (Rows, Cols):", arr.shape) # (2, 3)
print("Total Elements (Size):", arr.size) # 6
print("Data Type (dtype):", arr.dtype) # int32 বা int64
print("Item Size (in bytes):", arr.itemsize) # 4 বা 8
```

### ৬. ডেটা টাইপ পরিবর্তন (Casting / astype)
মেশিন লার্নিংয়ে অনেক সময় ফ্লোট (Float) ডেটাকে ইন্টিজারে (Integer) বা স্ট্রিংকে নাম্বারে কনভার্ট করতে হয়।
```python
# ফ্লোট অ্যারে তৈরি
arr_float = np.array([1.1, 2.5, 3.9])

# ফ্লোট থেকে ইন্টিজারে কনভার্ট করা (এটি ভ্যালু রাউন্ড করবে না, শুধু দশমিক কেটে ফেলবে)
arr_int = arr_float.astype('int32')
print(arr_int) # [1, 2, 3]

# বুলিয়ান (Boolean) এ কনভার্ট করা (0 মানে False, বাকি সব True)
arr_bool = np.array([0, 1, 5]).astype('bool')
print(arr_bool) # [False, True, True]
```

### ৭. ইনডেক্সিং এবং স্লাইসিং (Indexing & Slicing)
২ডি বা ৩ডি ম্যাট্রিক্স থেকে নির্দিষ্ট ডেটা কেটে বের করা খুবই গুরুত্বপূর্ণ স্কিল।
```python
matrix = np.array([[10, 20, 30, 40],
                   [50, 60, 70, 80],
                   [90, 100, 110, 120]])

# ১. সাধারণ ইনডেক্সিং [Row, Column]
print(matrix[1, 2]) # আউটপুট: 70 (২য় সারির ৩য় কলাম)

# ২. স্লাইসিং [Start_Row:End_Row, Start_Col:End_Col]
# প্রথম ২টি সারি এবং শেষের ২টি কলাম বের করা
print(matrix[0:2, 2:4])
# আউটপুট: 
# [[30 40]
#  [70 80]]

# ৩. শুধু একটি নির্দিষ্ট কলাম পুরোটা নেওয়া
second_column = matrix[:, 1]
print(second_column) # [20, 60, 100]
```

### ৮. শেপ পরিবর্তন বা রিশেপ (Reshaping & Flattening)
ইমেজ প্রসেসিংয়ে 2D ছবিকে 1D লম্বা লাইনে রূপান্তর (Flatten) করতে হয়।
```python
arr = np.array([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])

# ১২টি আইটেমকে ৩টি সারি ও ৪টি কলামের 2D ম্যাট্রিক্সে রূপান্তর
reshaped = arr.reshape(3, 4)
print(reshaped)

# ম্যাজিক: কলামের জায়গায় -1 দিলে NumPy নিজেই হিসাব করে নিবে কয়টি কলাম লাগবে!
auto_reshape = arr.reshape(2, -1) 
print(auto_reshape.shape) # (2, 6)

# 2D বা 3D অ্যারেকে ভেঙে সোজা 1D অ্যারে বানানো (Flattening)
flat_arr = reshaped.flatten() 
print(flat_arr) # [1 2 3 ... 12]
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৯. বুলিয়ান ইনডেক্সিং (Boolean Masking / Filtering)
ধরুন আপনার কাছে লক্ষ ডেটা আছে, সেখান থেকে কন্ডিশন দিয়ে ডেটা ফিল্টার করতে হবে (Pandas এর মতো)।
```python
ages = np.array([15, 22, 18, 30, 12, 45, 50, 17])

# কোন কোন বয়স ১৮ এর বেশি? (এটি একটি True/False এর মাস্ক দিবে)
mask = ages > 18
print("Mask:", mask) 

# এবার এই মাস্কটি অ্যারের ভেতর দিলে শুধু True ভ্যালুগুলো রিটার্ন হবে!
adults = ages[ages > 18]
print("Adults only:", adults) # [22 30 45 50]

# একাধিক কন্ডিশন (বয়স ২০ এর বেশি এবং ৪০ এর কম)
target_group = ages[(ages > 20) & (ages < 40)]
```

### ১০. ব্রডকাস্টিং (Broadcasting) - দ্য সুপারপাওয়ার!
NumPy এর সবচেয়ে জাদুকরী ফিচারের নাম Broadcasting। দুটি ভিন্ন সাইজের অ্যারের মধ্যে যোগ-বিয়োগ করার সময় NumPy ছোট অ্যারেকে অটোমেটিক স্ট্রেচ (Stretch) বা প্রসারিত করে বড় অ্যারের সাইজে নিয়ে যায়!

```python
matrix = np.array([[1, 2, 3],
                   [4, 5, 6],
                   [7, 8, 9]])

# একটি 2D ম্যাট্রিক্সের সাথে একটি সাধারণ স্কেলার নাম্বার যোগ করা
# NumPy অটোমেটিক্যালি এই 10 কে ম্যাট্রিক্সের প্রতিটি উপাদানের সাথে যোগ করে দিবে!
print(matrix + 10)

# একটি 2D (3x3) ম্যাট্রিক্সের সাথে 1D (3,) অ্যারে যোগ করা
vector = np.array([100, 200, 300])
# ভেক্টরটি ম্যাট্রিক্সের প্রতিটি সারির সাথে আলাদাভাবে যোগ হবে!
print(matrix + vector)
```

### ১১. স্ট্যাটিস্টিকস এবং ম্যাথ (Axis Operations)
অ্যাডভান্সড ক্যালকুলেশনের সময় `axis` এর ধারণা থাকা মাস্ট। 2D ম্যাট্রিক্সে `axis=0` মানে কলাম বরাবর (উপর-নিচ) কাজ করা, আর `axis=1` মানে সারি বরাবর (ডান-বাম) কাজ করা।

```python
data = np.array([[10, 20, 30],
                 [40, 50, 60]])

# পুরো ম্যাট্রিক্সের গড় (Mean)
print("Total Mean:", np.mean(data))

# প্রতিটি কলামের আলাদা গড় (axis=0)
print("Column-wise Mean:", np.mean(data, axis=0)) # [25. 35. 45.]

# প্রতিটি সারির আলাদা যোগফল (axis=1)
print("Row-wise Sum:", np.sum(data, axis=1)) # [60, 150]

# সর্বোচ্চ ভ্যালুর ইনডেক্স পজিশন বের করা (argmax)
print("Index of max value:", np.argmax(data)) # 5 (অর্থাৎ 60 আছে ৫ নাম্বার ইনডেক্সে)
```

### ১২. লিনিয়ার অ্যালজেব্রা (Linear Algebra)
মেশিন লার্নিং বা ডিপ লার্নিংয়ের নিউরাল নেটওয়ার্ক পুরোটাই লিনিয়ার অ্যালজেব্রার ওপর দাঁড়িয়ে আছে।

```python
A = np.array([[1, 2], [3, 4]])
B = np.array([[5, 6], [7, 8]])

# ১. ডট গুণন (Dot Product / Matrix Multiplication)
# এটি সাধারণ গুণের (A * B) মতো নয়। এটি সারি ও কলামের গুণন।
dot_product = np.dot(A, B)
# মডার্ন পাইথনে: dot_product = A @ B
print("Dot Product:\n", dot_product)

# ২. ম্যাট্রিক্স ট্রান্সপোজ (Transpose) - সারিকে কলাম, কলামকে সারি করা
print("Transpose of A:\n", A.T)

# ৩. ডিটারমিন্যান্ট বা নির্ণায়ক (Determinant)
det_A = np.linalg.det(A)
print("Determinant of A:", det_A)

# ৪. ইনভার্স ম্যাট্রিক্স (Inverse Matrix)
inv_A = np.linalg.inv(A)
```

### ১৩. রেন্ডম নাম্বার জেনারেটর (Random Module)
```python
# ১. ০ থেকে ১ এর মধ্যে ৩x৩ রেন্ডম ম্যাট্রিক্স
rand_matrix = np.random.rand(3, 3)

# ২. নরমাল ডিস্ট্রিবিউশন (Standard Normal Distribution) থেকে ডেটা নেওয়া
randn_matrix = np.random.randn(3, 3)

# ৩. নির্দিষ্ট রেঞ্জের পূর্ণসংখ্যা (যেমন: ১০ থেকে ৫০ এর মধ্যে ৫টি সংখ্যা)
rand_ints = np.random.randint(10, 50, 5)

# ৪. অ্যারে শাফল (Shuffle) বা এলোমেলো করা
arr = np.array([1, 2, 3, 4, 5])
np.random.shuffle(arr) # অরিজিনাল অ্যারেই চেঞ্জ হয়ে যাবে
```

### ১৪. পারফরম্যান্স এবং ভেক্টরাইজেশন (Vectorization vs Loops)
NumPy শেখার মূল উদ্দেশ্যই হলো স্পিড! চলুন লুপের সাথে NumPy এর স্পিড টেস্ট করি।

```python
import numpy as np
import time

# ১ কোটি ডেটা তৈরি
size = 10000000
list1 = list(range(size))
list2 = list(range(size))

arr1 = np.arange(size)
arr2 = np.arange(size)

# === Python Loop Test ===
start_time = time.time()
# দুটি লিস্টের প্রতিটি উপাদান যোগ করা
result_list = [list1[i] + list2[i] for i in range(size)]
print(f"Python Loop Time: {time.time() - start_time:.4f} seconds")

# === NumPy Vectorization Test ===
start_time = time.time()
# এক লাইনেই ১ কোটি ডেটা যোগ! (Vectorization)
result_arr = arr1 + arr2
print(f"NumPy Vectorization Time: {time.time() - start_time:.4f} seconds")
```
*আপনার কম্পিউটারে রান করে দেখুন, NumPy লুপের চেয়ে প্রায় **৫০ থেকে ১০০ গুণ** কম সময় নিবে!*

---

### উপসংহার (Conclusion)
NumPy হলো পুরো পাইথন ডেটা ইকোসিস্টেমের ফাউন্ডেশন। আপনি যদি Pandas, OpenCV, Scikit-Learn বা TensorFlow ব্যবহার করেন—সবার ব্যাকএন্ডেই ডেটা মূলত NumPy Array হিসেবেই কাজ করে! তাই NumPy এর শেপ (Shape), স্লাইসিং (Slicing) এবং ব্রডকাস্টিং (Broadcasting) খুব ভালোভাবে বোঝা একজন এক্সপার্ট পাইথন ডেভেলপারের জন্য অত্যন্ত জরুরি!
