# Pillow (Zero to Hero) কমপ্লিট গাইড

আপনি যদি পাইথন দিয়ে সাধারণ কোনো ইমেজ প্রসেসিংয়ের কাজ করতে চান—যেমন: একসাথে ফোল্ডারের ১০০টি ছবির সাইজ ছোট করা, ছবিতে ব্লার ফিল্টার (Blur Filter) দেওয়া, বা ছবির ওপর কোনো টেক্সট/লোগো (Watermark) বসানো—তবে `OpenCV` এর মতো বিশাল সাইজের লাইব্রেরি ব্যবহার করাটা বোকামি।

এই ছোটখাটো কিন্তু দারুণ কাজগুলো করার জন্য পাইথনের সবচেয়ে জনপ্রিয় এবং লাইটওয়েট লাইব্রেরির নাম হলো **Pillow (PIL)**। 

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের ছবি ওপেন করা থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের ফিল্টার দেওয়া এবং ছবিতে টেক্সট বা লোগো (Watermark) বসানো পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং ছবি ওপেন/সেভ করা
প্রথমে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install Pillow
```
*(বিঃদ্রঃ প্যাকেজটির নাম Pillow হলেও, কোডে ইমপোর্ট করার সময় একে `PIL` লিখতে হয়!)*

```python
from PIL import Image

# ১. ফোল্ডার থেকে একটি ছবি ওপেন করা
img = Image.open('nature.jpg')

# ২. ছবির বিস্তারিত তথ্য দেখা
print("Image Format:", img.format) # JPEG, PNG
print("Image Size:", img.size)     # (Width, Height)
print("Color Mode:", img.mode)     # RGB, L (Grayscale)

# ৩. ছবিটি স্ক্রিনে দেখানো (কম্পিউটারের ডিফল্ট ফটো ভিউয়ার ওপেন হবে)
img.show()

# ৪. ছবিটিকে অন্য ফরমেটে (যেমন: PNG) সেভ করা
img.save('nature_converted.png')
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ২. রিসাইজ (Resize) এবং ক্রপ (Crop) করা
ওয়েবসাইটে আপলোড করার জন্য আমরা প্রায়ই ছবির রেজোলিউশন বা সাইজ ছোট করি।

```python
from PIL import Image

img = Image.open('nature.jpg')
width, height = img.size

# ১. রিসাইজ করা (যেমন: Width 800 এবং Height 600)
resized_img = img.resize((800, 600))
resized_img.save('resized.jpg')

# ২. থাম্বনেইল (Thumbnail) তৈরি করা (এটির কোয়ালিটি রিসাইজের চেয়ে ভালো হয়)
img.thumbnail((400, 400))
img.save('thumbnail.jpg')

# ৩. ছবি ক্রপ (Crop) করা (নির্দিষ্ট অংশ কেটে বের করা)
# কোঅর্ডিনেট দিতে হয়: (Left, Top, Right, Bottom)
crop_area = (100, 100, 400, 400)
cropped_img = img.crop(crop_area)
cropped_img.save('cropped.jpg')
```

### ৩. ব্ল্যাক এন্ড হোয়াইট করা এবং ছবি ঘোরানো (Rotate)
```python
from PIL import Image

img = Image.open('nature.jpg')

# ১. ছবিকে ব্ল্যাক এন্ড হোয়াইট (Grayscale) এ কনভার্ট করা ('L' মানে Luminance)
bw_img = img.convert('L')
bw_img.save('black_white.jpg')

# ২. ছবিকে ঘোরানো বা Rotate করা (যেমন: 90 ডিগ্রি)
# expand=True দিলে ছবিটি না কেটে পুরো ক্যানভাসটা বড় হয়ে যাবে
rotated_img = img.rotate(90, expand=True)
rotated_img.save('rotated.jpg')

# ৩. ছবি উলটে দেওয়া (Flip / Mirror)
flipped_img = img.transpose(Image.FLIP_LEFT_RIGHT)
flipped_img.save('flipped.jpg')
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৪. ইমেজ ফিল্টার অ্যাপ্লাই করা (Image Filters)
Pillow লাইব্রেরির `ImageFilter` মডিউলে আগে থেকেই বেশ কিছু চমৎকার ফিল্টার দেওয়া আছে।

```python
from PIL import Image, ImageFilter

img = Image.open('nature.jpg')

# ১. ব্লার (Blur) বা ঘোলা করা
blurred_img = img.filter(ImageFilter.BLUR)
blurred_img.save('blurred.jpg')

# খুব বেশি ব্লার করতে চাইলে Gaussian Blur
super_blur = img.filter(ImageFilter.GaussianBlur(radius=5))
super_blur.save('super_blur.jpg')

# ২. ছবির এজ (Edge) বা আউটলাইন বের করা (পেন্সিল স্কেচের মতো দেখায়!)
edges = img.filter(ImageFilter.FIND_EDGES)
edges.save('edges.jpg')

# ৩. ছবির ডিটেইলস (Detail) এবং শার্পনেস (Sharpen) বাড়ানো
sharp_img = img.filter(ImageFilter.SHARPEN)
sharp_img.save('sharpened.jpg')
```

### ৫. ছবির ওপর টেক্সট লেখা বা লোগো বসানো (Watermark / Draw)
ধরুন আপনার একটি ফেসবুক পেজ আছে এবং আপনি চান আপনার সব ছবির ওপরে অটোমেটিকভাবে আপনার পেজের নাম (Watermark) লেখা হয়ে যাক। এই কাজটি করার জন্য `ImageDraw` এবং `ImageFont` ব্যবহার করতে হয়।

```python
from PIL import Image, ImageDraw, ImageFont

img = Image.open('nature.jpg')

# ১. ছবির ওপর আঁকাআঁকি করার জন্য Draw অবজেক্ট তৈরি
draw = ImageDraw.Draw(img)

# ২. ফন্ট এবং ফন্ট সাইজ লোড করা
# (আপনার কম্পিউটারে arial.ttf ফাইলটি না থাকলে ডিফল্ট ফন্ট লোড হবে)
try:
    font = ImageFont.truetype("arial.ttf", 50)
except IOError:
    font = ImageFont.load_default()

# ৩. টেক্সট বসানো
# প্যারামিটার: (X, Y) পজিশন, টেক্সট, কালার (R, G, B), ফন্ট
draw.text((50, 50), "Python Learning Hub!", fill=(255, 255, 255), font=font)

# ৪. (বোনাস) আপনি চাইলে ছবির ওপর বক্স বা বৃত্তও আঁকতে পারেন!
# outline হলো বর্ডারের কালার, width হলো বর্ডারের সাইজ
draw.rectangle([(30, 30), (600, 120)], outline="red", width=5)

img.show()
img.save('watermarked.jpg')
```

### সারসংক্ষেপ (Conclusion)
ই-কমার্স সাইটে অটোমেটিক থাম্বনেইল (Thumbnail) তৈরি করা, মিম (Meme) জেনারেটর বানানো বা ফোল্ডারের হাজার হাজার ছবির ওপর লোগো বসানোর জন্য **`Pillow`** লাইব্রেরির কোনো বিকল্প নেই। এটি `OpenCV` এর চেয়ে অনেক বেশি সহজ এবং মডার্ন ওয়েব ফ্রেমওয়ার্কগুলোর (যেমন Django) সাথে দারুণভাবে কাজ করে!
