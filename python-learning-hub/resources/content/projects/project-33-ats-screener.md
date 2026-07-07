# ৩৩. রেজ্যুমে/সিভি স্ক্রিনিং সফটওয়্যার (ATS Resume Screener)

বড় বড় মাল্টিন্যাশনাল কোম্পানিগুলোতে যখন কোনো জবের জন্য সার্কুলার দেওয়া হয়, তখন হাজার হাজার সিভি (CV) জমা পড়ে। মানুষের পক্ষে প্রতিটি সিভি পড়ে দেখা অসম্ভব। তাই তারা **ATS (Applicant Tracking System)** নামের একটি সফটওয়্যার ব্যবহার করে, যা স্বয়ংক্রিয়ভাবে সিভিগুলো পড়ে এবং জবের রিকোয়ারমেন্টের সাথে মিলিয়ে ক্যান্ডিডেটদের একটি স্কোর (Score) দেয়। 

এই প্রজেক্টে আমরা ন্যাচারাল ল্যাঙ্গুয়েজ প্রসেসিং বা NLP (Natural Language Processing) ব্যবহার করে ঠিক সেরকমই একটি মিনি ATS সফটওয়্যার বানাবো!

### কীভাবে কাজ করে? (How it works):
1. **PDF Text Extraction:** প্রথমে পাইথন ব্যবহার করে ইউজারের দেওয়া সিভির পিডিএফ (PDF) থেকে সমস্ত টেক্সট বা লেখা পড়ে নেওয়া হবে।
2. **Text Processing (NLP):** `Spacy` লাইব্রেরি ব্যবহার করে সিভির টেক্সট থেকে অপ্রয়োজনীয় শব্দ বাদ দেওয়া হবে এবং মূল স্কিলগুলো (Skills) আলাদা করা হবে।
3. **Keyword Matching:** আপনি জবের জন্য যে স্কিলগুলো (যেমন: Python, Django, SQL) খুঁজছেন, তার সাথে সিভির স্কিলগুলো মেলানো হবে।
4. **Scoring:** কতগুলো কি-ওয়ার্ড মিলেছে তার ওপর ভিত্তি করে সফটওয়্যারটি ১০০ এর মধ্যে একটি স্কোর জেনারেট করবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডগুলো লিখে লাইব্রেরিগুলো ইনস্টল করে নিন। `spacy` এর সাথে আমাদের ইংরেজি ভাষার একটি প্রি-ট্রেইনড মডেলও ডাউনলোড করতে হবে:

```bash
pip install spacy PyPDF2
python -m spacy download en_core_web_sm
```

### প্রজেক্টের কোড:

ধরে নিচ্ছি আপনার ফোল্ডারে `resume.pdf` নামে একটি সিভির পিডিএফ ফাইল আছে। নিচের কোডটি কপি করে রান করুন।

```python
import spacy
import PyPDF2
import re

# স্পেসির ইংরেজি মডেল লোড করা
nlp = spacy.load("en_core_web_sm")

def extract_text_from_pdf(pdf_path):
    """পিডিএফ ফাইল থেকে টেক্সট এক্সট্র্যাক্ট করার ফাংশন"""
    text = ""
    try:
        with open(pdf_path, 'rb') as file:
            reader = PyPDF2.PdfReader(file)
            for page in range(len(reader.pages)):
                text += reader.pages[page].extract_text()
    except Exception as e:
        print(f"Error reading PDF: {e}")
    return text

def clean_and_extract_keywords(text):
    """টেক্সট থেকে অপ্রয়োজনীয় শব্দ বাদ দিয়ে শুধু মূল শব্দ বের করা"""
    # টেক্সট ছোট হাতের অক্ষরে রূপান্তর এবং স্পেশাল ক্যারেক্টার বাদ দেওয়া
    text = re.sub(r'[^a-zA-Z\s]', '', text.lower())
    
    # NLP মডেল দিয়ে প্রসেস করা
    doc = nlp(text)
    
    # Stop words (am, is, the) বাদ দিয়ে শুধু প্রয়োজনীয় শব্দ নেওয়া
    keywords = set([token.text for token in doc if not token.is_stop and token.text.strip() != ""])
    return keywords

def ats_scorer(resume_path, job_description_keywords):
    print("--- ATS Resume Screener ---")
    print("Reading Resume PDF...\n")
    
    resume_text = extract_text_from_pdf(resume_path)
    
    if not resume_text:
        return
        
    resume_keywords = clean_and_extract_keywords(resume_text)
    
    # জব রিকোয়ারমেন্টের কি-ওয়ার্ডগুলোকে ছোট হাতের অক্ষরে রূপান্তর
    required_keywords = set([kw.lower() for kw in job_description_keywords])
    
    # সিভির সাথে জব রিকোয়ারমেন্ট মেলানো (Intersection)
    matched_keywords = required_keywords.intersection(resume_keywords)
    
    # স্কোর ক্যালকুলেট করা (Match % = Matched / Required * 100)
    match_percentage = (len(matched_keywords) / len(required_keywords)) * 100
    
    print("=== Screening Results ===")
    print(f"Required Skills: {', '.join(required_keywords)}")
    print(f"Matched Skills in Resume: {', '.join(matched_keywords)}")
    print(f"Match Score: {match_percentage:.2f}%")
    
    if match_percentage >= 70:
        print("\n✅ Result: SHORTLISTED! (Good Match)")
    else:
        print("\n❌ Result: REJECTED! (Does not match job requirements)")

if __name__ == "__main__":
    # জবের জন্য কী কী স্কিল দরকার তার একটি লিস্ট
    job_requirements = ["Python", "Django", "SQL", "API", "Git", "Linux"]
    
    # সিভির পিডিএফ ফাইলের নাম (আপনার ফোল্ডারে থাকা ফাইলের নাম দিন)
    resume_file = "resume.pdf"
    
    ats_scorer(resume_file, job_requirements)
```

### কোডটি কীভাবে শিখবেন?
1. **PyPDF2 Library:** `PyPDF2.PdfReader()` ব্যবহার করে আমরা খুব সহজেই যেকোনো পিডিএফ ফাইল থেকে পেইজ বাই পেইজ লেখা পড়ে নিতে পারি। 
2. **Regex (Regular Expression):** `re.sub(r'[^a-zA-Z\s]', '', text)` এই লাইনটির কাজ হলো ইংরেজি অক্ষর ছাড়া বাকি সব কিছু (যেমন: কমা, দাড়ি, ইমোজি) মুছে ফেলা। এটি ডেটা ক্লিনিংয়ের জন্য খুবই গুরুত্বপূর্ণ।
3. **NLP Stop Words:** `token.is_stop` ব্যবহার করে আমরা 'and', 'or', 'the', 'is' এর মতো শব্দগুলো ফিল্টার করে ফেলেছি, কারণ এগুলো সিভির স্কিল যাচাই করার জন্য কোনো কাজে আসে না।
4. **Python Sets:** `intersection()` মেথডটি পাইথনের `Set` ডেটাস্ট্রাকচারের একটি চমৎকার ফিচার। এটি দুটি লিস্টের মধ্যে কমন বা সাধারণ জিনিসগুলো খুব দ্রুত খুঁজে বের করতে পারে!
