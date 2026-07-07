# ৫০. নেটফ্লিক্সের মতো রেকমেন্ডেশন সিস্টেম (Recommendation Engine)

আপনি যখন নেটফ্লিক্স (Netflix) বা ইউটিউবে কোনো মুভি দেখেন, ঠিক তার পরপরই আপনার পছন্দের মতো আরও বেশ কিছু মুভি সাজেস্ট (Suggest) করা হয়। ই-কমার্স সাইটে (যেমন অ্যামাজন) কোনো প্রোডাক্ট কিনলে লেখা আসে "Customers who bought this also bought..."। 

এই অসাধারণ কাজটি করা হয় মেশিন লার্নিংয়ের **Recommendation Engine** বা রেকমেন্ডেশন সিস্টেম ব্যবহার করে। এই প্রজেক্টে আমরা **Collaborative Filtering** অ্যালগরিদম ব্যবহার করে একটি মুভি রেকমেন্ডেশন সিস্টেম বানাবো!

### কীভাবে কাজ করে? (How it works):
1. **User Ratings:** প্রথমে আমরা একটি ডেটাসেট নিবো যেখানে বিভিন্ন ইউজার বিভিন্ন মুভিকে কত রেটিং দিয়েছে তা লেখা থাকবে।
2. **Collaborative Filtering:** অ্যালগরিদম চেক করবে যে আপনার মুভি দেখার টেস্ট (Taste) বা পছন্দের সাথে অন্য কোন ইউজারের পছন্দ মিলে যায়।
3. **Cosine Similarity:** গাণিতিক এই সূত্র ব্যবহার করে দেখা হবে দুটি মুভি একে অপরের কতটা কাছাকাছি। যে মুভিগুলো সবচেয়ে কাছাকাছি (Similar), সেগুলোই আপনাকে সাজেস্ট করা হবে!

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install pandas scikit-learn
```

### প্রজেক্টের কোড:
নিচের কোডটি হলো একটি কনটেন্ট/কলাবোরেটিভ-ভিত্তিক রেকমেন্ডেশন ইঞ্জিনের বেসিক আর্কিটেকচার।

```python
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def build_recommendation_engine():
    print("=== AI Movie Recommendation Engine ===\n")
    
    # ১. একটি ডেমো ডেটাসেট তৈরি করা (বাস্তবে এটি বিশাল CSV ফাইল হয়)
    movies_data = {
        'Movie_ID': [1, 2, 3, 4, 5],
        'Title': [
            'Interstellar', 
            'The Dark Knight', 
            'Inception', 
            'Avengers: Endgame', 
            'The Notebook'
        ],
        'Genres': [
            'Sci-Fi Space Action',
            'Action Superhero Crime',
            'Sci-Fi Action Thriller',
            'Action Superhero Sci-Fi',
            'Romance Drama'
        ]
    }
    
    df = pd.DataFrame(movies_data)
    print("Available Movies in Database:")
    for title in df['Title']:
        print(f"- {title}")
        
    print("\n[1] Processing Movie Genres using AI (TF-IDF)...")
    
    # ২. জঁরা (Genres) টেক্সটগুলোকে গাণিতিক ম্যাট্রিক্সে রূপান্তর করা
    vectorizer = TfidfVectorizer()
    feature_vectors = vectorizer.fit_transform(df['Genres'])
    
    print("[2] Calculating Cosine Similarity (Finding patterns)...")
    
    # ৩. একটি মুভির সাথে বাকি মুভিগুলোর মিল বা সিমিলারিটি (Similarity) বের করা
    similarity = cosine_similarity(feature_vectors)
    
    # ৪. ইউজারকে মুভি সাজেস্ট করা
    user_liked_movie = "Inception"
    print(f"\nUser watched: '{user_liked_movie}'")
    print(f"Generating recommendations based on '{user_liked_movie}'...\n")
    
    # ইউজারের পছন্দের মুভির ইনডেক্স (Index) বের করা
    movie_index = df[df.Title == user_liked_movie].index[0]
    
    # সিমিলারিটি লিস্ট থেকে ওই মুভির সাথে মিল থাকা স্কোরগুলো নেওয়া
    similarity_score = list(enumerate(similarity[movie_index]))
    
    # স্কোর অনুযায়ী মুভিগুলোকে বড় থেকে ছোট ক্রমানুসারে সাজানো
    sorted_similar_movies = sorted(similarity_score, key=lambda x: x[1], reverse=True)
    
    print("🎥 TOP RECOMMENDATIONS FOR YOU:")
    # প্রথমটি বাদ দিয়ে (কারণ প্রথমটি ইনসেপশন নিজেই) পরের ৩টি মুভি সাজেস্ট করা
    count = 1
    for i in sorted_similar_movies[1:]:
        index = i[0]
        score = i[1] * 100 # শতকরা (Percentage) হিসেবে স্কোর
        if count <= 3:
            print(f"{count}. {df.loc[index, 'Title']} (Match Score: {score:.1f}%)")
            count += 1

if __name__ == "__main__":
    build_recommendation_engine()
```

### কোডটি কীভাবে শিখবেন?
1. **TF-IDF Vectorizer:** এটি একটি অসাধারণ NLP টুল। এটি মুভির জঁরা (যেমন: Sci-Fi, Action) পড়ে বুঝতে পারে কোন শব্দটি বেশি গুরুত্বপূর্ণ এবং সেগুলোকে নাম্বারে রূপান্তর করে।
2. **Cosine Similarity:** এটি মূলত দুটি ভেক্টরের (Vectors) মধ্যবর্তী কোণ (Angle) মাপে। যদি দুটি মুভির জঁরা একদম সেম হয়, তবে কোণ হবে ০ ডিগ্রি এবং কস (Cos 0) এর মান হবে ১ (অর্থাৎ ১০০% ম্যাচ)। আর যদি একদম আলাদা হয় (যেমন: Sci-Fi এবং Romance), তবে স্কোর হবে ০%। 
3. **Sorting Recommendations:** `sorted(..., reverse=True)` ব্যবহার করে আমরা সবচেয়ে বেশি স্কোর পাওয়া মুভিগুলোকে (Highest Match) লিস্টের একেবারে উপরের দিকে নিয়ে এসেছি, ঠিক যেভাবে ইউটিউব বা নেটফ্লিক্স কাজ করে!
