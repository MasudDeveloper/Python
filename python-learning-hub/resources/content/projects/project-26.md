## ২৫. লাইভ প্রজেক্ট: অটোমেটেড রেজ্যুমে/সিভি পার্সার (Automated Resume Parser)

বড় বড় কোম্পানিতে যখন কোনো চাকরির সার্কুলার দেওয়া হয়, তখন হাজার হাজার সিভি বা রেজ্যুমে জমা পড়ে। এইচআর (HR) ম্যানেজারদের পক্ষে একটি একটি করে সিভি পড়া অসম্ভব। তাই তারা 'রেজ্যুমে পার্সার' বা 'ATS (Applicant Tracking System)' ব্যবহার করে। এই প্রজেক্টে আমরা এমন একটি প্রোগ্রাম বানাবো যা একটি ফোল্ডারে থাকা সমস্ত সিভির পিডিএফ (PDF) নিজে নিজে পড়বে এবং সেখান থেকে মানুষের নাম, ইমেইল, ফোন নাম্বার ও স্কিলগুলো (Skills) আলাদা করে একটি এক্সেলে বা CSV ফাইলে সেভ করে দিবে!

### কীভাবে কাজ করে? (How it works):
আমরা প্রথমে `PyPDF2` দিয়ে পিডিএফ ফাইল থেকে সমস্ত টেক্সট (Text) বা লেখাগুলো এক্সট্রাক্ট (Extract) করবো। এরপর `Regular Expression (Regex)` ব্যবহার করে টেক্সটের ভেতর থেকে ইমেইল ও ফোন নাম্বারের নির্দিষ্ট প্যাটার্ন খুঁজবো। সবশেষে `pandas` লাইব্রেরি ব্যবহার করে সেই ডেটাগুলোকে একটি এক্সেলে সেভ করবো।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের পিডিএফ রিডিং এবং ডেটা প্রসেসিংয়ের লাইব্রেরি লাগবে:
1. **PyPDF2:** পিডিএফ ফাইল থেকে টেক্সট পড়ার জন্য।
2. **pandas:** ডেটাগুলোকে টেবিল আকারে সাজিয়ে এক্সেলে বা CSV-তে সেভ করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install PyPDF2 pandas
```

### প্রজেক্টের কোড:

নিচের কোডটি রান করার আগে আপনার প্রজেক্ট ফোল্ডারে `resumes` নামে একটি ফোল্ডার তৈরি করুন এবং সেখানে কয়েকটি পিডিএফ ফরম্যাটের সিভি রাখুন।

```python
import os
import re
import pandas as pd
from PyPDF2 import PdfReader

# আমাদের পিডিএফ সিভিগুলো যে ফোল্ডারে আছে তার নাম
RESUME_FOLDER = "resumes"

def extract_text_from_pdf(pdf_path):
    """পিডিএফ ফাইল থেকে সব টেক্সট পড়ে রিটার্ন করার ফাংশন"""
    text = ""
    try:
        reader = PdfReader(pdf_path)
        for page in reader.pages:
            text += page.extract_text()
    except Exception as e:
        print(f"Error reading {pdf_path}: {e}")
    return text

def extract_email(text):
    """রেগুলার এক্সপ্রেশন (Regex) দিয়ে টেক্সট থেকে ইমেইল বের করার ফাংশন"""
    # ইমেইলের সাধারণ প্যাটার্ন
    email_pattern = r'[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}'
    emails = re.findall(email_pattern, text)
    if emails:
        return emails[0] # প্রথম ইমেইলটি রিটার্ন করবে
    return "Not Found"

def extract_phone(text):
    """Regex দিয়ে টেক্সট থেকে ফোন নাম্বার বের করার ফাংশন"""
    # ফোন নাম্বারের সাধারণ প্যাটার্ন (যেমন: 017..., +88017..., 123-456-7890)
    phone_pattern = r'\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}'
    phones = re.findall(phone_pattern, text)
    if phones:
        return phones[0]
    return "Not Found"

def extract_skills(text):
    """টেক্সট থেকে নির্দিষ্ট কিছু স্কিল খোঁজার ফাংশন"""
    # আমরা যে স্কিলগুলো খুঁজতে চাই তার একটি লিস্ট
    target_skills = ['Python', 'Java', 'C++', 'SQL', 'Machine Learning', 'Django', 'React', 'HTML', 'CSS', 'JavaScript']
    found_skills = []
    
    # টেক্সটগুলো ছোট হাতের অক্ষরে (lowercase) কনভার্ট করে নিচ্ছি মেলানোর সুবিধার জন্য
    text_lower = text.lower()
    
    for skill in target_skills:
        if skill.lower() in text_lower:
            found_skills.append(skill)
            
    # স্কিলগুলোকে কমা (,) দিয়ে যুক্ত করে রিটার্ন করা হচ্ছে
    return ", ".join(found_skills) if found_skills else "No match found"

def process_resumes():
    print("Starting Resume Parser...")
    
    # সবার ডেটা রাখার জন্য একটি এম্পটি (Empty) লিস্ট
    all_candidates_data = []
    
    # ফোল্ডারের সবগুলো ফাইল চেক করা
    for filename in os.listdir(RESUME_FOLDER):
        if filename.endswith(".pdf"):
            file_path = os.path.join(RESUME_FOLDER, filename)
            print(f"Processing: {filename}...")
            
            # 1. টেক্সট এক্সট্রাক্ট করা
            resume_text = extract_text_from_pdf(file_path)
            
            # 2. ডেটা পার্স (Parse) করা
            email = extract_email(resume_text)
            phone = extract_phone(resume_text)
            skills = extract_skills(resume_text)
            
            # 3. ডিকশনারি (Dictionary) আকারে ডেটা সেভ করা
            # এখানে নাম হিসেবে আপাতত ফাইলের নামটাই ব্যবহার করছি
            candidate_data = {
                "Name": filename.replace(".pdf", ""), 
                "Email": email,
                "Phone": phone,
                "Skills": skills
            }
            
            all_candidates_data.append(candidate_data)
            
    # Pandas ব্যবহার করে ডেটাগুলোকে একটি ফ্রেমে (টেবিলে) আনা
    df = pd.DataFrame(all_candidates_data)
    
    # টেবিলটি একটি CSV (Excel) ফাইলে সেভ করা
    output_file = "parsed_resumes_data.csv"
    df.to_csv(output_file, index=False)
    
    print(f"\nSuccessfully processed {len(all_candidates_data)} resumes!")
    print(f"Data saved to {output_file}")

if __name__ == "__main__":
    # যদি resumes ফোল্ডার না থাকে তবে সেটি তৈরি করে নিবে
    if not os.path.exists(RESUME_FOLDER):
        os.makedirs(RESUME_FOLDER)
        print(f"Please put some PDF resumes inside the '{RESUME_FOLDER}' folder and run again.")
    else:
        process_resumes()
```

> [!TIP]
> **টিপস:** কোডটি রান করার পর এটি আপনার ফোল্ডারে থাকা সবগুলো সিভির ভেতর থেকে ইমেইল, নাম্বার আর স্কিল খুঁজে বের করে `parsed_resumes_data.csv` নামে একটি এক্সেল ফাইল তৈরি করবে। আপনি এক ক্লিকেই হাজার হাজার সিভির ডেটা একসাথে পেয়ে যাবেন!

### কোডটি কীভাবে শিখবেন?
1. **Regular Expressions (Regex):** `re.findall()` ফাংশন এবং একটি নির্দিষ্ট প্যাটার্ন (যেমন: `r'[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}'`) ব্যবহার করে কীভাবে হাজার হাজার শব্দের ভেতর থেকে শুধু ইমেইল বা ফোন নাম্বার বের করে আনা যায়, তা শিখতে পারবেন। এটি ডেটা সায়েন্সে খুবই গুরুত্বপূর্ণ।
2. **Text Processing:** একটি পিডিএফ ফাইল রিড করে তার স্ট্রিং (String) বা টেক্সট ডেটার ভেতরে কীভাবে নির্দিষ্ট স্কিল সার্চ করতে হয় (যেমন `skill.lower() in text_lower`), সেটি আয়ত্ত করতে পারবেন।
3. **Data Exporting:** আনস্ট্রাকচারড (Unstructured) ডেটাকে ডিকশনারি এবং লিস্টে সাজিয়ে, পরিশেষে `pandas` এর `to_csv()` ব্যবহার করে কীভাবে একটি প্রফেশনাল রিপোর্ট বা এক্সেলে সেভ করতে হয়, তার প্র্যাকটিক্যাল ব্যবহার শিখতে পারবেন।

---