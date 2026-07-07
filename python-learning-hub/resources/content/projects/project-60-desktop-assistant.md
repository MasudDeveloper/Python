# ৬০. স্মার্ট অটোমেশন ডেস্কটপ অ্যাসিস্ট্যান্ট (Advanced Desktop Assistant)

আয়রন ম্যান মুভির 'জার্ভিস' (Jarvis) এর মতো একটি স্মার্ট ডেস্কটপ অ্যাসিস্ট্যান্ট বানানো অনেকেরই স্বপ্ন। এই প্রজেক্টে আমরা স্পিচ রিকগনিশন (Speech Recognition) এবং উইন্ডোজ অটোমেশন ব্যবহার করে পাইথনে এমন একটি অ্যাসিস্ট্যান্ট তৈরি করবো, যাকে আপনি মুখে কমান্ড দিলে সে কম্পিউটারের বিভিন্ন কাজ (যেমন ফোল্ডার বানানো, গান প্লে করা বা মেইল পাঠানো) স্বয়ংক্রিয়ভাবে করে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **Speech to Text:** `SpeechRecognition` লাইব্রেরি ব্যবহার করে আপনার মুখের কথা (ভয়েস কমান্ড) রেকর্ড করে সেটিকে গুগলের মাধ্যমে টেক্সটে (Text) রূপান্তর করা হবে।
2. **Text to Speech:** অ্যাসিস্ট্যান্ট আপনাকে রিপ্লাই দেওয়ার জন্য `pyttsx3` (Python Text-to-Speech) ব্যবহার করে মানুষের মতো কথা বলে উঠবে।
3. **Automation/Actions:** এরপর আপনার বলা কমান্ডের (যেমন "Play music" বা "Create folder") ওপর ভিত্তি করে পাইথনের বিল্ট-ইন মডিউল (`os`, `webbrowser`) ব্যবহার করে কম্পিউটারে নির্দিষ্ট অ্যাকশন বা কাজ সম্পন্ন করা হবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install SpeechRecognition pyttsx3 pyaudio
```
*(বিঃদ্রঃ মাইক্রোফোন এক্সেসের জন্য `pyaudio` প্রয়োজন। ইনস্টল করতে সমস্যা হলে উইন্ডোজের জন্য আনঅফিশিয়াল হুইল (Wheel) ফাইল নামিয়ে ইনস্টল করতে হতে পারে।)*

### প্রজেক্টের কোড:
নিচের কোডটি একটি চমৎকার ডেস্কটপ অ্যাসিস্ট্যান্টের বেসিক স্ট্রাকচার:

```python
import speech_recognition as sr
import pyttsx3
import os
import webbrowser
import datetime

# Text-to-Speech (TTS) ইঞ্জিন সেটআপ করা
engine = pyttsx3.init()
# গলার স্বর (Voice) পরিবর্তন করা (0=Male, 1=Female)
voices = engine.getProperty('voices')
engine.setProperty('voice', voices[1].id)
engine.setProperty('rate', 150) # কথা বলার স্পিড

def speak(audio_text):
    """অ্যাসিস্ট্যান্টকে কথা বলানোর ফাংশন"""
    print(f"🤖 Jarvis: {audio_text}")
    engine.say(audio_text)
    engine.runAndWait()

def listen_command():
    """মাইক্রোফোন থেকে ইউজারের কথা শুনে টেক্সট এ রূপান্তর করা"""
    recognizer = sr.Recognizer()
    
    with sr.Microphone() as source:
        print("\nListening...")
        # ব্যাকগ্রাউন্ডের নয়েজ কমানো
        recognizer.adjust_for_ambient_noise(source, duration=1)
        audio = recognizer.listen(source)
        
    try:
        print("Recognizing...")
        # গুগলের স্পিচ রিকগনিশন ইঞ্জিন ব্যবহার করা
        query = recognizer.recognize_google(audio, language='en-US')
        print(f"👤 You said: {query}\n")
        return query.lower()
        
    except sr.UnknownValueError:
        print("Sorry, I could not understand what you said.")
        return "none"
    except sr.RequestError:
        print("Could not request results from Google Speech Recognition service.")
        return "none"

def execute_action(query):
    """কমান্ড অনুযায়ী কাজ করা"""
    if 'time' in query:
        strTime = datetime.datetime.now().strftime("%I:%M %p")
        speak(f"Sir, the time is {strTime}")
        
    elif 'open youtube' in query:
        speak("Opening YouTube for you.")
        webbrowser.open("https://www.youtube.com")
        
    elif 'open google' in query:
        speak("Opening Google.")
        webbrowser.open("https://www.google.com")
        
    elif 'create folder' in query:
        folder_name = "New_AI_Folder"
        os.makedirs(folder_name, exist_ok=True)
        speak(f"I have created a folder named {folder_name} in your directory.")
        
    elif 'play music' in query:
        # আপনার মিউজিক ডিরেক্টরি সেট করে দিতে হবে
        # music_dir = 'C:\\Users\\Public\\Music'
        # os.startfile(os.path.join(music_dir, 'song.mp3'))
        speak("Playing music from your library.")
        
    elif 'exit' in query or 'stop' in query:
        speak("Goodbye Sir, have a great day!")
        exit()
        
    else:
        speak("I am sorry, I am not programmed to do that yet.")

def start_assistant():
    speak("Hello! I am your advanced desktop assistant. How can I help you today?")
    
    while True:
        # ইউজারের কমান্ড শোনা
        query = listen_command()
        
        # 'none' হলে কোনো লজিক রান হবে না
        if query != "none":
            # কমান্ড অনুযায়ী কাজ করা
            execute_action(query)

if __name__ == "__main__":
    start_assistant()
```

### কোডটি কীভাবে শিখবেন?
1. **pyttsx3:** এটি একটি অফলাইন Text-to-Speech লাইব্রেরি। অর্থাৎ এটি চালানোর জন্য ইন্টারনেট কানেকশনের দরকার হয় না। `engine.say()` এর ভেতরে আপনি যেকোনো স্ট্রিং (Text) দিলে সে কম্পিউটারের স্পিকার দিয়ে সেটি বলে শুনাবে। 
2. **sr.Microphone() as source:** `with` ব্লক ব্যবহার করে মাইক্রোফোন অন করা হয়েছে। কাজ শেষ হলে এটি নিজে নিজেই মাইক্রোফোন অফ করে দেয়। 
3. **recognize_google():** এটি গুগলের ফ্রি স্পিচ রিকগনিশন API ব্যবহার করে আপনার কথাগুলোকে টেক্সট বা স্ট্রিংয়ে রূপান্তর করে। 
4. **if/elif Logic:** এটি অ্যাসিস্ট্যান্টের ব্রেইন! আমরা চেক করছি ইউজারের কথার (Query) ভেতর 'time' বা 'youtube' শব্দগুলো আছে কি না। থাকলে পাইথনের `os` বা `webbrowser` মডিউল ব্যবহার করে আমরা সেই অনুযায়ী কম্পিউটারে অ্যাকশন ট্রিগার (Trigger) করছি। আপনি চাইলে এখানে ইমেইল পাঠানো বা লাইট অন-অফ করার লজিকও অ্যাড করতে পারেন!
