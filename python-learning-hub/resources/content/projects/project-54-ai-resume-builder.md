# ৫৪. এআই রেজ্যুমে/সিভি বিল্ডার (AI Resume Builder)

আপনার রেজ্যুমে বা সিভি (CV) যদি আকর্ষণীয় এবং প্রফেশনাল ভাষায় লেখা না থাকে, তবে ইন্টারভিউয়ের কল পাওয়া অনেক কঠিন হয়ে যায়। কিন্তু সবার পক্ষে তো আর প্রফেশনাল ইংলিশে সিভি লেখা সম্ভব নয়! 

এই প্রজেক্টে আমরা **Generative AI (OpenAI API)** ব্যবহার করে একটি এআই রেজ্যুমে বিল্ডার বানাবো। ইউজার শুধু তার নাম, স্কিলস (Skills) এবং আগের কাজের একটু ধারণা (Basic details) দিবে। বাকিটা এআই নিজে থেকেই প্রফেশনাল ভাষায় বিস্তারিত লিখে সুন্দর একটি সিভি বানিয়ে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **User Input:** পাইথনের মাধ্যমে ইউজারের কাছ থেকে তার ইনফরমেশনগুলো টেক্সট আকারে ইনপুট নেওয়া হবে।
2. **AI Prompt Engineering:** ইউজারের দেওয়া ইনফরমেশনগুলোকে একটি প্রম্পট (Prompt) আকারে সাজিয়ে `OpenAI API` এর কাছে পাঠানো হবে এবং কমান্ড দেওয়া হবে এটিকে প্রফেশনাল রেজ্যুমের ভাষায় রূপান্তর করতে।
3. **Resume Generation:** এআই যে সুন্দর টেক্সটটি বানিয়ে দিবে, তা আমরা চাইলে টার্মিনালে দেখতে পারবো অথবা পিডিএফ হিসেবে সেভ করতে পারবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরি ইনস্টল করে নিন:
```bash
pip install openai
```
*(বিঃদ্রঃ এই প্রজেক্টটি রান করার জন্য আপনার একটি OpenAI API Key লাগবে।)*

### প্রজেক্টের কোড:
নিচের কোডটি হলো এআই রেজ্যুমে জেনারেটরের মূল লজিক:

```python
import os
from openai import OpenAI

def generate_ai_resume():
    print("=== AI Resume Builder ===")
    print("Please provide some basic details about yourself.\n")
    
    # ইউজারের কাছ থেকে বেসিক ইনফো নেওয়া
    name = input("Your Full Name: ")
    job_title = input("Target Job Title (e.g., Software Engineer): ")
    skills = input("Key Skills (comma separated): ")
    experience = input("Briefly describe your past experience: ")
    
    print("\n[!] Generating a professional resume for you using AI... Please wait.")
    
    # OpenAI API Key সেটআপ
    os.environ["OPENAI_API_KEY"] = "sk-your-openai-api-key-here"
    client = OpenAI()
    
    # এআইকে বোঝানোর জন্য প্রম্পট ইঞ্জিনিয়ারিং
    prompt_text = f"""
    You are an expert Resume Writer. Please write a highly professional, ATS-friendly resume summary and experience section based on the following details. Make the tone confident and action-oriented.
    
    Name: {name}
    Target Role: {job_title}
    Skills: {skills}
    Past Experience: {experience}
    
    Format the output cleanly with headings: [Professional Summary], [Core Competencies], and [Professional Experience].
    """
    
    try:
        # OpenAI সার্ভারে রিকোয়েস্ট পাঠানো (GPT-3.5 বা GPT-4 মডেল ব্যবহার করে)
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "You are a professional resume writer."},
                {"role": "user", "content": prompt_text}
            ],
            max_tokens=800,
            temperature=0.7 # 0.7 মানে এআই কিছুটা ক্রিয়েটিভ হবে
        )
        
        # এআই এর তৈরি করা রেজ্যুমে টেক্সট
        generated_resume = response.choices[0].message.content
        
        print("\n" + "="*50)
        print("✅ YOUR PROFESSIONAL RESUME:")
        print("="*50 + "\n")
        
        print(generated_resume)
        print("\n" + "="*50)
        
        # চাইলে এটিকে একটি .txt ফাইলে সেভ করে রাখা যায়
        with open(f"Resume_{name.replace(' ', '_')}.txt", "w") as file:
            file.write(generated_resume)
        print("Resume saved as a text file successfully!")
        
    except Exception as e:
        print(f"\n❌ Error occurred: {e}")

if __name__ == "__main__":
    generate_ai_resume()
```

### কোডটি কীভাবে শিখবেন?
1. **Prompt Engineering:** এই প্রজেক্টের সবচেয়ে গুরুত্বপূর্ণ অংশ হলো প্রম্পট (Prompt)। ইউজার শুধু বলেছে "worked in a bank", কিন্তু প্রম্পটে আমরা এআইকে কমান্ড দিয়েছি `Make the tone confident and action-oriented`। ফলে এআই লিখবে "Spearheaded financial operations..."। এভাবেই সাধারণ টেক্সট প্রফেশনাল সিভিতে পরিণত হয়!
2. **Chat Completions API:** `client.chat.completions.create(...)` হলো চ্যাটজিপিটি (ChatGPT) এর কোর API। এখানে `system` রোলে আমরা এআইকে তার দায়িত্ব (Resume Writer) বুঝিয়ে দিয়েছি এবং `user` রোলে প্রম্পটটি দিয়েছি।
3. **Temperature Parameter:** `temperature=0.7` এর মানে হলো ক্রিয়েটিভিটি লেভেল। এটি ০ থেকে ১ এর মধ্যে হয়। ০ দিলে এআই রোবটের মতো কাঠখোট্টা উত্তর দিবে, আর ১ দিলে সে অনেক বেশি ক্রিয়েটিভ (কিন্তু মাঝে মাঝে ভুল) শব্দ ব্যবহার করবে। ০.৭ হলো প্রফেশনাল রাইটিংয়ের জন্য পারফেক্ট ব্যালেন্স!
