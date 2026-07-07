## ১২. লাইভ প্রজেক্ট: পার্সোনাল ভয়েস অ্যাসিস্ট্যান্ট (Jarvis/Siri)

এবার চলুন আমরা পাইথন দিয়ে নিজেদের জন্য একটি ছোট্ট ভয়েস অ্যাসিস্ট্যান্ট বানাই। এটি আপনার মুখের কথা শুনে টেক্সটে রূপান্তর করবে এবং সে অনুযায়ী কাজ করবে (যেমন: গুগলে সার্চ করা বা সময় বলা)।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের বেশ কিছু লাইব্রেরি লাগবে:
1. **SpeechRecognition:** আপনার মুখের কথাকে (Audio) টেক্সটে রূপান্তর করার জন্য।
2. **pyttsx3:** পাইথনকে দিয়ে কথা বলানোর জন্য (Text to Speech)।
3. **pyaudio:** মাইক্রোফোন থেকে সাউন্ড রেকর্ড করার জন্য।
4. **pywhatkit:** ইউটিউবে ভিডিও প্লে করা বা গুগলে সার্চ করার জন্য।
5. **wikipedia:** উইকিপিডিয়া থেকে তথ্য খোঁজার জন্য।

টার্মিনালে কমান্ডগুলো দিয়ে লাইব্রেরিগুলো ইনস্টল করুন:
```bash
pip install SpeechRecognition pyttsx3 pywhatkit wikipedia
pip install pyaudio
```

### প্রজেক্টের কোড:

```python
import speech_recognition as sr
import pyttsx3
import pywhatkit
import datetime
import wikipedia

# ভয়েস ইঞ্জিন সেটআপ (যাতে পাইথন কথা বলতে পারে)
engine = pyttsx3.init()
voices = engine.getProperty('voices')
engine.setProperty('voice', voices[1].id) # 1 মানে ফিমেল ভয়েস, 0 মানে মেল ভয়েস

def talk(text):
    """এই ফাংশনটি টেক্সট ইনপুট নিয়ে সাউন্ড হিসেবে আউটপুট দিবে"""
    engine.say(text)
    engine.runAndWait()

def take_command():
    """মাইক্রোফোন থেকে ইউজারের কথা শুনে সেটি টেক্সটে কনভার্ট করবে"""
    listener = sr.Recognizer()
    try:
        with sr.Microphone() as source:
            print("Listening...")
            # আশেপাশের নয়েজ ফিল্টার করা
            listener.adjust_for_ambient_noise(source)
            # কথা শোনা
            voice = listener.listen(source)
            # গুগল API ব্যবহার করে কথাকে টেক্সটে কনভার্ট করা
            command = listener.recognize_google(voice)
            command = command.lower()
            if 'jarvis' in command:
                command = command.replace('jarvis', '')
                print(command)
    except Exception as e:
        print("Could not understand audio. Error:", e)
        return ""
    return command

def run_assistant():
    """মূল ফাংশন যা কমান্ড অনুযায়ী কাজ করবে"""
    command = take_command()
    print("You said:", command)
    
    if 'play' in command:
        # 'play [গান/ভিডিও]' বললে সেটি ইউটিউবে প্লে করবে
        song = command.replace('play', '').strip()
        talk('Playing ' + song)
        pywhatkit.playonyt(song)
        
    elif 'time' in command:
        # 'what time is it' বললে সময় বলবে
        time = datetime.datetime.now().strftime('%I:%M %p')
        print(time)
        talk('Current time is ' + time)
        
    elif 'search' in command:
        # গুগলে সার্চ করবে
        search_query = command.replace('search', '').strip()
        talk('Searching for ' + search_query)
        pywhatkit.search(search_query)
        
    elif 'who is' in command or 'what is' in command:
        # উইকিপিডিয়া থেকে তথ্য খুঁজে বের করে পড়বে
        person = command.replace('who is', '').replace('what is', '').strip()
        info = wikipedia.summary(person, 1) # 1 মানে প্রথম লাইনটি পড়বে
        print(info)
        talk(info)
        
    elif 'stop' in command or 'exit' in command:
        talk("Goodbye! Have a nice day.")
        exit()
        
    else:
        talk("Please say the command again.")

# প্রোগ্রাম শুরু করা
talk("Hello, I am your assistant. How can I help you?")
while True:
    run_assistant()
```

> [!TIP]
> **বিঃদ্রঃ** `pyaudio` ইনস্টল করার সময় মাঝে মাঝে উইন্ডোজে Error আসতে পারে। সেক্ষেত্রে গুগলে `How to install pyaudio in windows` লিখে সার্চ করে সল্যুশন বের করে নেওয়া প্রোগ্রামিংয়ের একটি বড় অংশ!

---