# ৫৯. অটোমেটেড কোড রিভিউয়ার (Automated AI Code Reviewer)

আপনি যখন কোড লেখেন, তখন ভুল থাকা বা কোডটি আন-অপ্টিমাইজড (Unoptimized) হওয়া খুবই স্বাভাবিক। বড় বড় সফটওয়্যার কোম্পানিতে সিনিয়র ডেভেলপাররা জুনিয়রদের কোড ম্যানুয়ালি পড়ে রিভিউ (Code Review) করেন। কিন্তু কেমন হতো যদি একটি আর্টিফিশিয়াল ইন্টেলিজেন্স (AI) আপনার কোড পড়ে বলে দিতো কোথায় ভুল আছে এবং কীভাবে আরও ভালোভাবে লেখা যায়? 

এই প্রজেক্টে আমরা **OpenAI API** ব্যবহার করে এমন একটি অটোমেটেড কোড রিভিউয়ার টুল বানাবো, যা টার্মিনাল থেকেই আপনার লেখা পাইথন ফাইল পড়ে স্ক্যান করবে এবং এআই-এর মাধ্যমে আপনাকে সাজেশন (Suggestion) বা ফিডব্যাক দিবে।

### কীভাবে কাজ করে? (How it works):
1. **File Reading:** প্রথমে পাইথন দিয়ে আমরা যে ফাইলের (যেমন `test_script.py`) রিভিউ করতে চাই, তার সম্পূর্ণ কোড রিড (Read) করবো।
2. **AI Prompting:** ওই কোডটিকে একটি প্রম্পটের (Prompt) সাথে যুক্ত করে `OpenAI API` এর কাছে পাঠানো হবে। এআইকে বলা হবে একজন "সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার" এর মতো আচরণ করে কোডের বাগ (Bugs) এবং সিকিউরিটি ইস্যু বের করতে।
3. **Feedback:** এআই কোডটি অ্যানালাইসিস করে আমাদের বিস্তারিত একটি ফিডব্যাক দিবে এবং প্রয়োজনে ঠিক করা কোড (Corrected Code) লিখে দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরি ইনস্টল করে নিন:
```bash
pip install openai
```
*(বিঃদ্রঃ এই প্রজেক্টটি রান করার জন্য আপনার একটি OpenAI API Key লাগবে।)*

### প্রজেক্টের কোড:
নিচের কোডটি হলো অটোমেটেড এআই কোড রিভিউয়ারের মূল স্ক্রিপ্ট:

```python
import os
from openai import OpenAI
import sys

def review_code(file_path):
    print(f"=== AI Code Reviewer ===")
    print(f"Loading file: {file_path}")
    
    # ১. ইউজার যে ফাইলটি রিভিউ করতে দিয়েছে তা রিড করা
    if not os.path.exists(file_path):
        print(f"Error: File '{file_path}' not found!")
        return
        
    with open(file_path, 'r', encoding='utf-8') as f:
        code_content = f.read()
        
    print("✅ File loaded successfully. Analyzing with AI...\n")
    
    # ২. OpenAI API সেটআপ
    os.environ["OPENAI_API_KEY"] = "sk-your-openai-api-key-here"
    client = OpenAI()
    
    # ৩. এআইকে কমান্ড দেওয়া (Prompt Engineering)
    system_prompt = """
    You are an expert Senior Software Engineer. Review the provided Python code.
    1. Identify any bugs or logical errors.
    2. Suggest improvements for performance, readability, and security.
    3. Provide the corrected/optimized version of the code.
    Output your feedback clearly using Markdown.
    """
    
    try:
        # এআই সার্ভারে রিকোয়েস্ট পাঠানো (GPT-3.5 বা 4 মডেল)
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": f"Here is my code:\n\n{code_content}"}
            ],
            temperature=0.3 # 0.3 মানে লজিক্যাল উত্তর দিবে, বেশি বানাবে না
        )
        
        # এআই এর ফিডব্যাক বা রিভিউ
        ai_feedback = response.choices[0].message.content
        
        print("="*60)
        print("🤖 AI CODE REVIEW & FEEDBACK")
        print("="*60)
        print(ai_feedback)
        print("="*60)
        
    except Exception as e:
        print(f"❌ Error during AI review: {e}")

if __name__ == "__main__":
    # টার্মিনাল থেকে ফাইলের নাম ইনপুট হিসেবে নেওয়া
    # উদাহরণ: python reviewer.py my_code.py
    
    if len(sys.argv) > 1:
        target_file = sys.argv[1]
        review_code(target_file)
    else:
        print("Usage: python reviewer.py <filename.py>")
        print("Example: python reviewer.py bad_code.py")
```

### কীভাবে টেস্ট করবেন?
আপনার ফোল্ডারে ইচ্ছা করে ভুল লেখা একটি পাইথন ফাইল (যেমন `bad_code.py`) সেভ করুন। যেমন:
```python
# bad_code.py
def divide(a, b):
    return a/b # (এখানে b=0 হলে যে ক্র্যাশ করবে তা হ্যান্ডেল করা নেই)
```
এরপর টার্মিনালে `python reviewer.py bad_code.py` রান করুন। এআই সাথে সাথে ধরে ফেলবে যে এখানে `ZeroDivisionError` হতে পারে এবং আপনাকে `try-except` ব্যবহার করে সঠিক কোডটি লিখে দিবে!

### কোডটি কীভাবে শিখবেন?
1. **sys.argv:** কমান্ড লাইনে আমরা প্রোগ্রামের নামের সাথে যে আর্গুমেন্টগুলো দিই (যেমন: `python script.py myfile.txt`), সেগুলো `sys.argv` নামক একটি লিস্টে সেভ হয়। `sys.argv[0]` হলো ফাইলের নাম (script.py) এবং `sys.argv[1]` হলো আমরা যে ফাইলটি রিভিউ করতে চাই (myfile.txt)।
2. **System Prompt (System Role):** এআইকে যখন `system` রোলে ইনস্ট্রাকশন দেওয়া হয় ("You are an expert Senior Software Engineer..."), তখন সে তার উত্তরগুলোকে ওই চরিত্রের (Character) বা পারসোনা (Persona) অনুযায়ী সেট করে নেয়। এটি প্রম্পট ইঞ্জিনিয়ারিংয়ের একটি অ্যাডভান্সড পদ্ধতি।
3. **Temperature (0.3):** কোডিংয়ের ক্ষেত্রে এআইকে খুব বেশি ক্রিয়েটিভ (Temperature 1.0) হতে দিলে সে ভুল বা হাবিজাবি লজিক বানাতে পারে (যাকে Hallucination বলে)। তাই কোড রিভিউ বা ম্যাথ সলভ করার সময় টেম্পারেচার (0.1 - 0.3) এর মতো কম রাখা হয়, যেন উত্তরগুলো ফ্যাকচুয়াল বা লজিক্যাল হয়।
