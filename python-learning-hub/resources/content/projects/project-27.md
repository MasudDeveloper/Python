## ২৬. লাইভ প্রজেক্ট: প্লাজারিজম চেকার (Plagiarism Checker)

স্কুল, কলেজ বা ইউনিভার্সিটিতে যখন স্টুডেন্টরা অ্যাসাইনমেন্ট জমা দেয়, তখন শিক্ষকরা চেক করেন কেউ কারও খাতা থেকে কপি বা নকল করেছে কি না। একেই বলে প্লাজারিজম (Plagiarism)। এই প্রজেক্টে আমরা মেশিন লার্নিংয়ের একটি বেসিক কনসেপ্ট ব্যবহার করে এমন একটি প্রোগ্রাম বানাবো, যাকে দুটি আলাদা টেক্সট দিলে সে বলে দিবে লেখাগুলো একে অপরের সাথে কত শতাংশ মিলে যায়!

### কীভাবে কাজ করে? (How it works):
মেশিন লার্নিংয়ের ভাষায় এটিকে বলা হয় `Cosine Similarity`। আমরা প্রথমে আমাদের টেক্সটগুলোকে (শব্দগুলোকে) গণিতের ভেক্টরে (Numbers) রূপান্তর করবো। একে বলা হয় `TF-IDF (Term Frequency-Inverse Document Frequency)`। এরপর আমরা দুটি ভেক্টরের মাঝের কোণ (Angle) বা দূরত্ব মাপবো। যদি দুটি লেখা একদম হুবহু মিলে যায়, তবে তাদের Similarity হবে ১০০%, আর একদমই না মিললে হবে ০%। 

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের মেশিন লার্নিংয়ের সবচেয়ে জনপ্রিয় লাইব্রেরি `scikit-learn` লাগবে:
1. **scikit-learn:** মেশিন লার্নিং অ্যালগরিদম এবং ম্যাথামেটিক্যাল ক্যালকুলেশন করার জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিটি ইনস্টল করে নিন:
```bash
pip install scikit-learn
```

### প্রজেক্টের কোড:

নিচের কোডটি কপি করে সেভ করুন। এখানে আমরা উদাহরণস্বরূপ দুটি ছোট টেক্সট ব্যবহার করেছি, আপনি চাইলে `open()` ফাংশন দিয়ে দুটি বড় টেক্সট ফাইলও রিড করে চেক করতে পারেন।

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def check_plagiarism(text1, text2):
    """দুটি টেক্সটের মধ্যে কতটুকু মিল আছে তা চেক করার ফাংশন"""
    
    # দুটি টেক্সটকে একটি লিস্টের ভেতরে রাখা হচ্ছে
    documents = [text1, text2]
    
    # TF-IDF Vectorizer তৈরি করা (এটি শব্দগুলোকে নাম্বারে/ভেক্টরে রূপান্তর করবে)
    # stop_words='english' এর মানে হলো in, the, is, at এর মতো কমন শব্দগুলোকে সে হিসেবে ধরবে না
    vectorizer = TfidfVectorizer(stop_words='english')
    
    # টেক্সটগুলোকে ভেক্টরে রূপান্তর করা হলো
    tfidf_matrix = vectorizer.fit_transform(documents)
    
    # Cosine Similarity ক্যালকুলেট করা হচ্ছে
    # এটি মূলত দুটি ভেক্টরের মাঝের কোণ বা মিল খুঁজে বের করে
    similarity_matrix = cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:2])
    
    # রেজাল্টটি 0 থেকে 1 এর মধ্যে আসে, তাই 100 দিয়ে গুণ করে পার্সেন্টেজে নেওয়া হলো
    match_percentage = similarity_matrix[0][0] * 100
    
    return match_percentage

if __name__ == "__main__":
    print("=== Plagiarism Checker ===\n")
    
    # প্রথম টেক্সট (ধরে নিই এটি অরিজিনাল অ্যাসাইনমেন্ট)
    student_1_text = """
    Python is a high-level, general-purpose programming language. 
    Its design philosophy emphasizes code readability with the use of significant indentation. 
    Python is dynamically-typed and garbage-collected.
    """
    
    # দ্বিতীয় টেক্সট (ধরে নিই এটি অন্য স্টুডেন্টের অ্যাসাইনমেন্ট)
    # দেখুন এখানে সে কিছু শব্দ পরিবর্তন করেছে কিন্তু মূল কথা একই
    student_2_text = """
    Python is a popular high-level, general-purpose programming language. 
    The design philosophy of Python focuses on code readability using significant indentation. 
    It is dynamically-typed and supports garbage collection.
    """
    
    # তৃতীয় টেক্সট (সম্পূর্ণ আলাদা একটি টেক্সট)
    student_3_text = """
    Machine learning is a field of study in artificial intelligence concerned with the 
    development and study of statistical algorithms that can learn from data and 
    generalize to unseen data, and thus perform tasks without explicit instructions.
    """
    
    print("Checking Student 1 vs Student 2...")
    match_1_2 = check_plagiarism(student_1_text, student_2_text)
    print(f"Similarity: {match_1_2:.2f}%\n")
    
    if match_1_2 > 60:
        print("Warning: High chance of plagiarism detected between Student 1 and 2!\n")
        
    print("-" * 40)
        
    print("\nChecking Student 1 vs Student 3...")
    match_1_3 = check_plagiarism(student_1_text, student_3_text)
    print(f"Similarity: {match_1_3:.2f}%\n")
```

> [!TIP]
> **টিপস:** কোডটি রান করলে আপনি দেখতে পাবেন প্রথম এবং দ্বিতীয় স্টুডেন্টের লেখার মাঝে প্রায় **৬৭%** মিল পাওয়া গেছে (কারণ তারা একই বিষয় নিয়ে লিখেছে), কিন্তু প্রথম এবং তৃতীয় স্টুডেন্টের লেখার মাঝে **০%** মিল পাওয়া গেছে!

### কোডটি কীভাবে শিখবেন?
1. **TF-IDF Vectorization:** কম্পিউটার তো আর আমাদের মতো ভাষা বুঝে না, সে বুঝে নাম্বার (০ এবং ১)। `TfidfVectorizer` কীভাবে আমাদের ইংরেজি শব্দগুলোকে ম্যাথামেটিক্যাল নাম্বারে (Vectors) কনভার্ট করে, তা আপনি এই প্রজেক্ট থেকে বুঝতে পারবেন।
2. **Stop Words Removal:** `stop_words='english'` ব্যবহার করার ফলে 'is', 'the', 'of' এর মতো শব্দগুলো বাদ দেওয়া হয়েছে। কারণ এগুলো সব লেখাতেই থাকে, এগুলো দিয়ে প্লাজারিজম মাপা যায় না। এটি ন্যাচারাল ল্যাঙ্গুয়েজ প্রসেসিংয়ের (NLP) খুবই গুরুত্বপূর্ণ একটি ধাপ।
3. **Cosine Similarity:** দুটি লেখার মধ্যে গাণিতিকভাবে কীভাবে মিল (Similarity) খুঁজে বের করতে হয়, সেটি `cosine_similarity` ফাংশনের মাধ্যমে আপনি প্র্যাকটিক্যালি শিখে ফেলবেন।

---