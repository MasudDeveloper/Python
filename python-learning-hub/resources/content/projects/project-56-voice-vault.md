# ৫৬. ভয়েস-অথেন্টিকেটেড সিক্রেট ভল্ট (Voice Authenticated Vault)

কম্পিউটারের কোনো ফোল্ডার বা ফাইল লক করার জন্য আমরা সাধারণত টেক্সট পাসওয়ার্ড ব্যবহার করি। কিন্তু কেমন হতো যদি আপনার ফাইলগুলো শুধুমাত্র আপনার কণ্ঠ বা ভয়েস (Voice) শুনলেই আনলক হতো? 

এই প্রজেক্টে আমরা **Speech Processing** এবং **Audio Analysis** ব্যবহার করে এমন একটি 'সিক্রেট ভল্ট' তৈরি করবো, যা পাসওয়ার্ড হিসেবে ইউজারের কথা রেকর্ড করবে এবং শুধু আপনার গলার স্বর (Pitch/Tone) মিলে গেলেই ফাইলগুলো ওপেন করে দিবে।

### কীভাবে কাজ করে? (How it works):
1. **Audio Recording:** পাইথনের `sounddevice` ব্যবহার করে প্রথমে আপনার ভয়েস রেকর্ড করে একটি ডেটাবেস (Reference) তৈরি করা হবে। 
2. **Feature Extraction:** `librosa` লাইব্রেরি ব্যবহার করে আপনার কণ্ঠের ফ্রিকোয়েন্সি বা পিচ (MFCC features) আলাদা করে সেভ করা হবে, কারণ প্রতিটি মানুষের গলার আওয়াজ গাণিতিকভাবে ভিন্ন।
3. **Authentication:** এরপর যখনই কেউ ভল্ট আনলক করতে চাইবে, তাকে পাসওয়ার্ড বলতে হবে। এআই তার ভয়েসের ফ্রিকোয়েন্সি আগের রেফারেন্সের সাথে মেলাবে (Cosine Similarity)। মিলে গেলে আনলক হবে, নাহলে অ্যাক্সেস ডিনাইড (Access Denied) দেখাবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install sounddevice librosa numpy scipy
```

### প্রজেক্টের কোড:
নিচের কোডটি ভয়েস অথেন্টিকেশনের মূল লজিকটি প্রদর্শন করে।

```python
import sounddevice as sd
from scipy.io.wavfile import write
import librosa
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
import time

# ভয়েস রেকর্ডের সেটিংস
FS = 44100  # Sample rate
SECONDS = 3  # ৩ সেকেন্ডের অডিও রেকর্ড হবে

def record_audio(filename="temp.wav"):
    """ইউজারের ভয়েস রেকর্ড করে ফাইলে সেভ করা"""
    print(f"🎤 Recording started... Please say your password.")
    # মাইক্রোফোন থেকে অডিও নেওয়া
    myrecording = sd.rec(int(SECONDS * FS), samplerate=FS, channels=1)
    sd.wait()  # ৩ সেকেন্ড অপেক্ষা করা
    # অডিওটি wav ফাইলে সেভ করা
    write(filename, FS, myrecording)
    print("✅ Recording finished!")
    return filename

def extract_features(file_path):
    """অডিও ফাইল থেকে মানুষের কণ্ঠের গাণিতিক বৈশিষ্ট্য (MFCC) বের করা"""
    try:
        # অডিও লোড করা
        audio, sample_rate = librosa.load(file_path, sr=None)
        # MFCC (Mel-frequency cepstral coefficients) এক্সট্র্যাক্ট করা
        mfccs = librosa.feature.mfcc(y=audio, sr=sample_rate, n_mfcc=40)
        # সবগুলোর গড় (Mean) বের করে একটি 1D ভেক্টর বানানো
        mfccs_scaled_features = np.mean(mfccs.T, axis=0)
        return mfccs_scaled_features
    except Exception as e:
        print(f"Error extracting features: {e}")
        return None

def voice_vault_system():
    print("=== Voice Authenticated Vault ===")
    
    # ১. প্রথমে রেফারেন্স ভয়েস রেকর্ড করা (Registration)
    print("\n[Step 1: Registration]")
    input("Press Enter to record your REFERENCE voice...")
    ref_file = record_audio("reference_voice.wav")
    ref_features = extract_features(ref_file)
    
    print("\n✅ Vault is now LOCKED with your voice fingerprint!")
    time.sleep(2)
    
    # ২. আনলক করার চেষ্টা করা (Authentication)
    print("\n[Step 2: Authentication]")
    input("Press Enter to try unlocking the vault...")
    test_file = record_audio("test_voice.wav")
    test_features = extract_features(test_file)
    
    if ref_features is not None and test_features is not None:
        # ৩. দুটি ভয়েসের মধ্যে মিল (Similarity) চেক করা
        # ভেক্টরগুলোকে 2D তে রূপান্তর করা হচ্ছে
        similarity = cosine_similarity([ref_features], [test_features])[0][0]
        match_percentage = similarity * 100
        
        print(f"\n🔍 Voice Match Score: {match_percentage:.2f}%")
        
        # যদি মিলের পরিমাণ ৯০% এর বেশি হয় (থ্রেশহোল্ড)
        if match_percentage > 90:
            print("🔓 ACCESS GRANTED: Welcome to your secret vault!")
            # (বাস্তবে এখানে ফাইল এনক্রিপ্ট/ডিক্রিপ্ট করার লজিক থাকে)
        else:
            print("🚫 ACCESS DENIED: Voice does not match the owner!")

if __name__ == "__main__":
    voice_vault_system()
```

### কোডটি কীভাবে শিখবেন?
1. **sounddevice:** এটি পাইথনে সরাসরি মাইক্রোফোন বা স্পিকার কন্ট্রোল করার সবচেয়ে ফাস্ট লাইব্রেরি। `sd.rec()` দিয়ে আমরা খুব সহজেই রিয়েল-টাইম অডিও রেকর্ড করতে পারি।
2. **librosa & MFCC:** মানুষের গলা থেকে যে শব্দ বের হয়, তার ফ্রিকোয়েন্সি ম্যাপ করার জন্য `MFCC` (Mel-frequency cepstral coefficients) ব্যবহৃত হয়। এটি মূলত আপনার গলার স্বরকে ৪০টি গাণিতিক সংখ্যায় (Features) রূপান্তর করে ফেলে, যা অন্য কারও সাথে হুবহু মিলবে না!
3. **Cosine Similarity:** ঠিক যেমনটা আমরা মুভি রেকমেন্ডেশনে ব্যবহার করেছিলাম, এখানেও আমরা রেফারেন্স অডিও এবং নতুন অডিও ভেক্টরের মধ্যকার মিল চেক করার জন্য `cosine_similarity` ব্যবহার করেছি। ১০০% মানে হলো দুটি ভয়েস হুবহু এক। 

> [!WARNING]
> **সতর্কতা:** ব্যাকগ্রাউন্ড নয়েজ (Noise) বা আপনার ঠাণ্ডা লাগলে গলার স্বর পরিবর্তন হতে পারে, তখন এআই আপনাকে চিনতে নাও পারে। তাই প্রফেশনাল সিস্টেমে ভয়েসের পাশাপাশি একটি ব্যাকআপ পিন (PIN) বা পাসওয়ার্ড রাখা ভালো!
