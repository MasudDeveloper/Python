# ৩৭. ভয়েস ক্লোনিং অ্যাপ্লিকেশন (Voice Cloning AI)

আর্টিফিশিয়াল ইন্টেলিজেন্স বা এআই (AI) এর আরেকটি চমৎকার ও বিস্ময়কর শাখা হলো অডিও বা ভয়েস প্রসেসিং। এই প্রজেক্টে আমরা এআই ব্যবহার করে এমন একটি অ্যাপ্লিকেশন বানাবো, যেখানে আপনি আপনার বা অন্য যেকোনো মানুষের মাত্র কয়েক সেকেন্ডের অডিও রেকর্ড (Sample) ইনপুট দিলে, এআই সেই কণ্ঠ হুবহু নকল (Clone) করে ফেলবে! এরপর আপনি এআইকে যে টেক্সটই লিখে দিবেন, সে ওই মানুষের কণ্ঠেই সেটি পড়ে শোনাবে!

বর্তমানে **ElevenLabs** হলো ভয়েস ক্লোনিং এবং টেক্সট-টু-স্পিচ (TTS) এর ক্ষেত্রে পৃথিবীর সবচেয়ে জনপ্রিয় এবং রিয়েলিস্টিক এআই প্ল্যাটফর্ম। আমরা পাইথনে তাদের API ব্যবহার করে এই প্রজেক্টটি তৈরি করবো।

### কীভাবে কাজ করে? (How it works):
1. **Voice Sampling:** প্রথমে যার কণ্ঠ নকল করতে চান, তার স্পষ্ট কণ্ঠের একটি ছোট অডিও ফাইল (যেমন: `sample_voice.mp3`) তৈরি করতে হবে।
2. **API Connection:** এরপর `ElevenLabs API` এর মাধ্যমে আমরা ওই অডিও ফাইলটি তাদের সার্ভারে পাঠাবো। এআই ওই অডিওর টোন, পিচ (Pitch) এবং স্টাইল অ্যানালাইসিস করবে।
3. **Text-to-Speech:** এরপর আপনি যেকোনো টেক্সট দিলে, এআই ওই মানুষের কণ্ঠ ব্যবহার করে নতুন একটি অডিও ফাইল জেনারেট করে আপনাকে ফেরত দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের `elevenlabs` এর অফিশিয়াল পাইথন লাইব্রেরি লাগবে। 

টার্মিনালে নিচের কমান্ডটি লিখে ইনস্টল করে নিন:
```bash
pip install elevenlabs
```
*(বিঃদ্রঃ এই প্রজেক্টটি রান করার জন্য আপনার একটি ElevenLabs API Key লাগবে, যা আপনি elevenlabs.io থেকে ফ্রিতে অ্যাকাউন্ট খুলে সংগ্রহ করতে পারবেন)*

### প্রজেক্টের কোড:

ধরে নিচ্ছি আপনার প্রজেক্ট ফোল্ডারে `my_voice.mp3` নামে আপনার একটি ছোট ভয়েস রেকর্ডিং আছে। নিচের কোডটি কপি করে রান করুন।

```python
import os
from elevenlabs.client import ElevenLabs
from elevenlabs import play, save

def voice_cloning_app():
    print("=== AI Voice Cloning App (ElevenLabs) ===")
    
    # আপনার ElevenLabs API Key এখানে দিন
    # এটি elevenlabs.io -> Profile -> Profile Settings থেকে পাবেন
    API_KEY = "your-elevenlabs-api-key-here"
    
    client = ElevenLabs(
        api_key=API_KEY
    )
    
    print("\n[1/3] Cloning the voice...")
    try:
        # ১. ভয়েস ক্লোন করা
        # আপনার ফোল্ডারে থাকা অডিও ফাইলের নাম দিন (যেমন my_voice.mp3)
        cloned_voice = client.clone(
            name="My Cloned Voice",
            description="This is a custom cloned voice for testing.",
            files=["my_voice.mp3"] 
        )
        print("✅ Voice successfully cloned!")
        
        # ২. নতুন টেক্সট থেকে অডিও তৈরি করা
        print("\n[2/3] Generating speech with the cloned voice...")
        
        text_to_speak = "হ্যালো! এটি একটি আর্টিফিশিয়াল ইন্টেলিজেন্স বা এআই জেনারেটেড ভয়েস। আমার কণ্ঠটি হুবহু নকল করা হয়েছে!"
        
        audio = client.generate(
            text=text_to_speak,
            voice=cloned_voice, # সদ্য ক্লোন করা ভয়েসটি ব্যবহার করছি
            model="eleven_multilingual_v2" # এই মডেলটি বাংলাসহ பல ভাষা সাপোর্ট করে
        )
        print("✅ Speech generated successfully!")
        
        # ৩. অডিও সেভ করা এবং শোনানো
        print("\n[3/3] Saving and playing the audio...")
        
        save(audio, "cloned_output.mp3")
        print("Audio saved as 'cloned_output.mp3'")
        
        # অডিওটি প্লে করা
        play(audio)
        
    except Exception as e:
        print(f"\n❌ Error occurred: {e}")
        print("Make sure your API key is correct and 'my_voice.mp3' exists in the folder.")

if __name__ == "__main__":
    voice_cloning_app()
```

> [!WARNING]
> **সতর্কতা ও এথিক্স (Ethics):** ভয়েস ক্লোনিং একটি অত্যন্ত সেনসিটিভ টেকনোলজি। এটি দিয়ে স্ক্যাম বা সাইবার ক্রাইম করা সম্ভব। তাই **কখনোই কারও অনুমতি ছাড়া তার কণ্ঠ ক্লোন করবেন না!** এই প্রজেক্টটি শুধুমাত্র এআই কীভাবে কাজ করে তা শেখার উদ্দেশ্যে তৈরি করা হয়েছে। 

### কোডটি কীভাবে শিখবেন?
1. **ElevenLabs Client:** `ElevenLabs(api_key=...)` দিয়ে আমরা এআই সার্ভারের সাথে একটি সিকিউর কানেকশন তৈরি করি। 
2. **client.clone():** এই ফাংশনটি অডিও ফাইল আপলোড করে এবং একটি ইউনিক 'Voice Object' ফেরত দেয়, যার ভেতরে অডিওটির সমস্ত গাণিতিক বৈশিষ্ট্য (Acoustic features) সংরক্ষিত থাকে।
3. **Multilingual Model:** আমরা এখানে `eleven_multilingual_v2` মডেল ব্যবহার করেছি। কারণ সাধারণ মডেলগুলো শুধু ইংরেজি বুঝতে পারে, কিন্তু মাল্টিলিঙ্গুয়াল (Multilingual) মডেলটি বাংলা, হিন্দি, স্প্যানিশসহ প্রায় ২৯টি ভাষায় নিখুঁত উচ্চারণ করতে পারে! 
4. **play() এবং save():** `generate()` ফাংশন যে বাইনারি অডিও ডেটা ফেরত দেয়, তা `play()` দিয়ে সরাসরি বাজানো যায় অথবা `save()` দিয়ে এমপিথ্রি (MP3) হিসেবে কম্পিউটারে সেভ করা যায়।
