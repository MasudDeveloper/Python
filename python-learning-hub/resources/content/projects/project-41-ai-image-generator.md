# ৪১. এআই ইমেজ জেনারেটর (AI Image Generator)

টেক্সট থেকে সরাসরি ছবি তৈরি করা (Text-to-Image) বর্তমান আর্টিফিশিয়াল ইন্টেলিজেন্সের অন্যতম ম্যাজিকাল একটি বিষয়। Midjourney বা DALL-E এর নাম হয়তো শুনে থাকবেন, যেগুলো ইউজারের দেওয়া কমান্ড বা প্রম্পট (Prompt) পড়ে ঠিক সেরকমই একটি ছবি এঁকে দেয়। 

এই প্রজেক্টে আমরা **OpenAI API (DALL-E 3)** ব্যবহার করে পাইথনে নিজস্ব একটি এআই ইমেজ জেনারেটর তৈরি করবো। আপনি পাইথনে প্রম্পট লিখে দিবেন, আর এআই ছবি বানিয়ে আপনার কম্পিউটারে সেভ করে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **API Connection:** প্রথমে আমরা `openai` লাইব্রেরি ব্যবহার করে OpenAI সার্ভারের সাথে কানেক্ট করবো।
2. **Prompt Submission:** আমরা এআইকে একটি ডেসক্রিপটিভ প্রম্পট দিবো (যেমন: "A futuristic city in the style of cyberpunk").
3. **Downloading the Image:** এআই ছবিটি তৈরি করে আমাদের একটি সাময়িক লিংক (URL) দিবে। আমরা পাইথনের `requests` লাইব্রেরি ব্যবহার করে ওই লিংক থেকে ছবিটি ডাউনলোড করে আমাদের প্রজেক্ট ফোল্ডারে `.png` হিসেবে সেভ করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:

```bash
pip install openai requests
```

*(বিঃদ্রঃ এই প্রজেক্টটি রান করার জন্য আপনার একটি OpenAI API Key লাগবে এবং আপনার অ্যাকাউন্টে পর্যাপ্ত ক্রেডিট থাকতে হবে।)*

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে রান করুন। এটি আপনার দেওয়া প্রম্পট অনুযায়ী একটি হাই-রেজ্যুলেশন (High-Resolution) ছবি তৈরি করবে।

```python
import os
import requests
from openai import OpenAI

def generate_ai_image():
    print("=== AI Image Generator (DALL-E 3) ===")
    
    # আপনার OpenAI API Key এখানে দিন
    os.environ["OPENAI_API_KEY"] = "sk-your-openai-api-key-here"
    client = OpenAI()
    
    # ইউজারের কাছ থেকে প্রম্পট বা কমান্ড নেওয়া
    prompt_text = input("\nDescribe the image you want to generate: ")
    
    print("\n[1/2] AI is drawing your image... Please wait.")
    try:
        # OpenAI সার্ভারে রিকোয়েস্ট পাঠানো
        response = client.images.generate(
            model="dall-e-3",
            prompt=prompt_text,
            size="1024x1024",
            quality="standard",
            n=1,
        )
        
        # এআই এর তৈরি করা ছবির লিংক পাওয়া
        image_url = response.data[0].url
        print(f"✅ Image Generated! Temporary Link: {image_url}")
        
        print("\n[2/2] Downloading the image to your computer...")
        
        # লিংক থেকে ছবিটি ডাউনলোড করা
        img_response = requests.get(image_url)
        
        # ছবিটি সেভ করা
        filename = "generated_ai_image.png"
        with open(filename, 'wb') as file:
            file.write(img_response.content)
            
        print(f"🎉 Success! The image has been saved as '{filename}' in your folder.")
        
    except Exception as e:
        print(f"\n❌ Error occurred: {e}")

if __name__ == "__main__":
    generate_ai_image()
```

### কোডটি কীভাবে শিখবেন?
1. **client.images.generate:** এটি হলো OpenAI লাইব্রেরির মূল ইমেজ জেনারেটিং ফাংশন। এখানে `model="dall-e-3"` দিয়ে আমরা সবচেয়ে অত্যাধুনিক ইমেজ মডেল সিলেক্ট করেছি এবং `size="1024x1024"` দিয়ে ছবির রেজ্যুলেশন ঠিক করে দিয়েছি।
2. **response.data[0].url:** এআই ছবিটি তৈরি করার পর সেটি একটি ক্লাউড সার্ভারে রাখে এবং আমাদের একটি লিংক দেয়। এই লাইনটির মাধ্যমে আমরা JSON রেসপন্স থেকে ওই লিংকটি এক্সট্র্যাক্ট (Extract) করি।
3. **wb (Write Binary):** ছবি কোনো সাধারণ টেক্সট ফাইল নয়, এটি একটি বাইনারি ফাইল (Binary Data)। তাই ছবিটি সেভ করার সময় `open(filename, 'wb')` ব্যবহার করা হয়েছে, যার মানে হলো Write Binary। `requests.get()` থেকে পাওয়া বাইনারি ডেটা (`img_response.content`) আমরা সরাসরি ফাইলের ভেতর রাইট করে দিয়েছি।

> [!TIP]
> **ফ্রি অল্টারনেটিভ:** আপনার যদি OpenAI API Key না থাকে, তবে আপনি **HuggingFace API** এবং `Stable Diffusion` মডেল ব্যবহার করতে পারেন। সেটির কোডও প্রায় একই রকম এবং HuggingFace থেকে ফ্রিতে API Key পাওয়া যায়!
