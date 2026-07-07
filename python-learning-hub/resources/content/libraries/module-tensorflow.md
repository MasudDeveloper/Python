# TensorFlow / Keras (Zero to Hero) কমপ্লিট গাইড

আর্টিফিশিয়াল ইন্টেলিজেন্স (AI), চ্যাটজিপিটি (ChatGPT), ফেস রিকগনিশন বা সেলফ-ড্রাইভিং গাড়ির মতো জাদুকরী জিনিসগুলোর মূলে রয়েছে **Deep Learning (ডিপ লার্নিং)** বা **Neural Networks (নিউরন নেটওয়ার্ক)**। 

আর এই ডিপ লার্নিং মডেল বানানোর জন্য গুগলের তৈরি পৃথিবীর সবচেয়ে বিখ্যাত এবং পাওয়ারফুল লাইব্রেরি হলো **`TensorFlow`**। এর ভেতরে **`Keras`** নামে একটি সাব-লাইব্রেরি আছে, যা দিয়ে মানুষের মতো চিন্তা করতে পারা নিউরন নেটওয়ার্ক মাত্র কয়েক লাইনের কোডেই তৈরি করা যায়!

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেল থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের ইমেজ ক্লাসিফিকেশন (CNN) মডেল বানানো শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং বেসিক টেন্সর (Tensor)
প্রথমে লাইব্রেরিটি ইনস্টল করে নিন (এটি সাইজে বেশ বড়, তাই কিছুটা সময় লাগতে পারে):
```bash
pip install tensorflow
```

মেশিন লার্নিংয়ে ডেটাকে বলা হয় ম্যাট্রিক্স বা অ্যারে। আর ডিপ লার্নিংয়ের ভাষায় যেকোনো ডেটাকে (ছবি, অডিও, টেক্সট) বলা হয় **Tensor (টেন্সর)**। 
```python
import tensorflow as tf

# ১. একটি সাধারণ টেন্সর বা ভেক্টর তৈরি করা
tensor_1d = tf.constant([1.0, 2.0, 3.0])
print("1D Tensor:", tensor_1d)

# ২. টেন্সরের মধ্যে গাণিতিক কাজ (সরাসরি GPU তে রান হয়!)
tensor_2d = tf.constant([[1, 2], [3, 4]])
multiplied = tensor_2d * 10

print("Multiplied Tensor:\n", multiplied.numpy()) # numpy() দিলে সাধারণ পাইথন লিস্টের মতো দেখাবে
```

### ২. Keras দিয়ে বেসিক নিউরাল নেটওয়ার্ক তৈরি (Sequential Model)
চলুন আমরা মানুষের ব্রেইনের মতো একটি নিউরাল নেটওয়ার্ক তৈরি করি, যার কাজ হবে একটি সাধারণ সূত্র (y = 2x - 1) বের করা।

```python
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
import numpy as np

# ১. আমাদের ডেটাসেট (X এবং Y)
X = np.array([-1.0, 0.0, 1.0, 2.0, 3.0, 4.0])
Y = np.array([-3.0, -1.0, 1.0, 3.0, 5.0, 7.0]) # সূত্র: Y = 2X - 1

# ২. মডেল তৈরি (Sequential মানে লেয়ারের পর লেয়ার সাজানো)
model = Sequential([
    # Dense মানে হলো সব নিউরন একে অপরের সাথে কানেক্টেড। এখানে মাত্র ১টি নিউরন ব্যবহার করছি।
    Dense(units=1, input_shape=[1]) 
])

# ৩. মডেলকে কম্পাইল করা (optimizer এবং loss function সেট করা)
# 'sgd' (Stochastic Gradient Descent) হলো মডেল শেখার পদ্ধতি
# 'mean_squared_error' হলো মডেলের ভুল মাপার পদ্ধতি
model.compile(optimizer='sgd', loss='mean_squared_error')

# ৪. মডেলকে ট্রেনিং দেওয়া (epochs মানে মডেল ডেটাগুলো কতবার দেখবে/পড়বে)
print("Training started...")
model.fit(X, Y, epochs=500, verbose=0) # verbose=0 দিলে ট্রেনিংয়ের আউটপুট হিজিবিজি দেখাবে না
print("Training finished!")

# ৫. প্রেডিকশন বা পরীক্ষা করা (X = 10 হলে Y কত হবে? সূত্র অনুযায়ী 19 হওয়া উচিত)
prediction = model.predict([10.0])
print("Prediction for 10:", prediction[0][0]) # রেজাল্ট 18.99 এর কাছাকাছি আসবে!
```

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. মাল্টিপল লেয়ার (Deep Neural Network)
বাস্তব জীবনে সমস্যাগুলো এতো সহজ হয় না। তাই আমাদেরকে একাধিক লেয়ার (Hidden Layers) এবং এক্টিভেশন ফাংশন (Activation Functions যেমন 'relu') ব্যবহার করতে হয়।

```python
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout

# ডিপ লার্নিং মডেল তৈরি
model = Sequential([
    # ইনপুট লেয়ার (৬৪টি নিউরন)
    Dense(64, activation='relu', input_shape=(10,)), 
    
    # Dropout লেয়ার (মডেল যেন ডেটা মুখস্থ বা Overfitting করতে না পারে, তাই ২০% নিউরনকে অফ করে দেওয়া)
    Dropout(0.2), 
    
    # হিডেন লেয়ার
    Dense(32, activation='relu'),
    
    # আউটপুট লেয়ার (১টি নিউরন - যেমন দাম প্রেডিক্ট করা বা হ্যাঁ/না উত্তর দেওয়া)
    Dense(1, activation='sigmoid') # 'sigmoid' দিলে আউটপুট ০ থেকে ১ এর মধ্যে আসে (Probability)
])

# সবচেয়ে পপুলার এবং পাওয়ারফুল অপটিমাইজার হলো 'adam'
model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])

model.summary() # মডেলের আর্কিটেকচার বা গঠন সুন্দর করে দেখাবে
```

### ৪. মডেল সেভ (Save) এবং লোড (Load) করা
মডেল ট্রেনিং করতে অনেক সময় (কয়েক ঘণ্টা থেকে কয়েক দিন) লাগে। তাই ট্রেনিং শেষে মডেলটি সেভ করে রাখতে হয়, যেন পরে সরাসরি ইউজ করা যায়।

```python
import tensorflow as tf

# ধরি আমাদের 'model' নামে একটি ট্রেইন করা মডেল আছে
# ১. মডেলটি .h5 বা .keras ফরমেটে সেভ করা
model.save('my_brain_model.keras')
print("Model saved to disk!")

# ২. নতুন কোনো ফাইলে মডেলটি রিড বা লোড করা
loaded_model = tf.keras.models.load_model('my_brain_model.keras')
print("Model loaded successfully!")

# ৩. লোড করা মডেল দিয়ে প্রেডিক্ট করা
# loaded_model.predict(new_data)
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৫. ইমেজ ক্লাসিফিকেশন (Convolutional Neural Networks - CNN)
ছবি দেখে কুকুর নাকি বিড়াল, তা বোঝার জন্য সাধারণ `Dense` লেয়ার কাজ করে না। ছবির ভেতর থেকে চোখ, নাক বা কান (Features) খুঁজে বের করার জন্য **CNN (Conv2D)** ব্যবহার করা হয়।

এটি ডিপ লার্নিংয়ের সবচেয়ে যুগান্তকারী আবিষ্কার!

```python
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense

# একটি ইমেজ ক্লাসিফিকেশন মডেল তৈরি
cnn_model = Sequential([
    # ১. কনভোলিউশনাল লেয়ার (এটি ইমেজের ওপর ফিল্টার চালিয়ে ফিচার বের করবে)
    # 32টি ফিল্টার, সাইজ (3x3), ইনপুট ছবি (64x64 পিক্সেল কালার ছবি)
    Conv2D(32, (3, 3), activation='relu', input_shape=(64, 64, 3)),
    
    # ২. পুলিং লেয়ার (এটি ইমেজের সাইজ ছোট করে দেয়, যাতে প্রসেসিং ফাস্ট হয়)
    MaxPooling2D(pool_size=(2, 2)),
    
    # ৩. আরেকটি কনভোলিউশনাল লেয়ার (আরও গভীর ফিচার খোঁজার জন্য)
    Conv2D(64, (3, 3), activation='relu'),
    MaxPooling2D((2, 2)),
    
    # ৪. ফ্ল্যাটেন (Flatten) লেয়ার (ম্যাট্রিক্সকে লম্বা এক লাইনের ভেক্টরে পরিণত করা)
    Flatten(),
    
    # ৫. ফুল্লি কানেক্টেড (Dense) লেয়ার
    Dense(128, activation='relu'),
    
    # ৬. আউটপুট লেয়ার (ধরুন ১০ ধরনের অবজেক্ট চিনতে হবে)
    # 'softmax' দিলে আউটপুটটি পার্সেন্টেজ হিসেবে দেখাবে (কোনটার চান্স কত)
    Dense(10, activation='softmax')
])

cnn_model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# মডেলটির চেহারা বা আর্কিটেকচার প্রিন্ট করে দেখা
cnn_model.summary()
```

### সারসংক্ষেপ (Conclusion)
আর্টিফিশিয়াল ইন্টেলিজেন্স বা এআই (AI) এর দুনিয়ায় পা রাখার জন্য **`TensorFlow`** এবং **`Keras`** শেখা বাধ্যতামূলক। আপনি যদি এই `Sequential` মডেল, `Dense` লেয়ার এবং অপটিমাইজারগুলোর কাজ বুঝতে পারেন, তবে আপনি নিজেই চ্যাটজিপিটির মতো টেক্সট মডেল বা ফেস-অ্যানলক করার মতো ফেস রিকগনিশন মডেল তৈরি করতে পারবেন!
