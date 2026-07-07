## ১৪. লাইভ প্রজেক্ট: ডেস্কটপ ওয়েদার নোটিফায়ার (Weather Notifier)

সারাদিন কাজ করার সময় আমরা অনেকেই আবহাওয়ার খোঁজ নিতে ভুলে যাই। এই প্রজেক্টটি ইন্টারনেট থেকে আপনার শহরের লাইভ আবহাওয়া সংগ্রহ করবে এবং কিছুক্ষণ পর পর কম্পিউটারের স্ক্রিনে একটি পপ-আপ নোটিফিকেশন দিয়ে জানাবে। 

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের দুটি এক্সটার্নাল লাইব্রেরি লাগবে:
1. **requests:** ইন্টারনেট (API) থেকে ডেটা আনার জন্য।
2. **plyer:** কম্পিউটারে নোটিফিকেশন দেখানোর জন্য।

টার্মিনালে এই কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install requests plyer
```

### প্রজেক্টের কোড:

এই প্রজেক্টে আমরা **OpenWeatherMap** এর ফ্রি API ব্যবহার করবো। আপনাকে `openweathermap.org` এ একটি ফ্রি অ্যাকাউন্ট খুলে API Key সংগ্রহ করতে হবে। 

```python
import requests
from plyer import notification
import time

def get_weather(city_name, api_key):
    # OpenWeatherMap এর API URL
    url = f"http://api.openweathermap.org/data/2.5/weather?q={city_name}&appid={api_key}&units=metric"
    
    try:
        # API তে রিকোয়েস্ট পাঠানো
        response = requests.get(url)
        data = response.json()
        
        # যদি রিকোয়েস্ট সফল হয় (HTTP 200)
        if data["cod"] == 200:
            main = data["main"]
            weather_desc = data["weather"][0]["description"]
            
            # তাপমাত্রা এবং অন্যান্য তথ্য
            temperature = main["temp"]
            humidity = main["humidity"]
            
            # নোটিফিকেশনে যে লেখাটি দেখাবে
            title = f"Weather Update for {city_name}"
            message = f"Temp: {temperature}°C\nHumidity: {humidity}%\nCondition: {weather_desc.title()}"
            
            return title, message
        else:
            return "Error", "City Not Found!"
            
    except requests.exceptions.RequestException as e:
        return "Connection Error", "Please check your internet connection."

def notify_me(title, message):
    # কম্পিউটারে নোটিফিকেশন দেখানোর ফাংশন
    notification.notify(
        title=title,
        message=message,
        app_icon=None, # এখানে চাইলে কোনো .ico ইমেজের পাথ দিতে পারেন
        timeout=10     # নোটিফিকেশন ১০ সেকেন্ড স্ক্রিনে থাকবে
    )

if __name__ == "__main__":
    # আপনার শহরের নাম এবং API Key দিন
    CITY = "Dhaka"
    API_KEY = "your_openweathermap_api_key_here" # আপনার API Key বসান
    
    while True:
        title, msg = get_weather(CITY, API_KEY)
        notify_me(title, msg)
        
        # প্রতি ১ ঘণ্টা (৩৬০০ সেকেন্ড) পর পর আপডেট জানাবে
        time.sleep(3600)
```

> [!TIP] 
> **বিঃদ্রঃ** এই স্ক্রিপ্টটি রান করে মিনিমাইজ করে রাখলে এটি ব্যাকগ্রাউন্ডে চলতে থাকবে এবং প্রতি ১ ঘণ্টা পর পর আপনাকে নোটিফিকেশন দিবে। প্রোগ্রামটি বন্ধ করতে হলে টার্মিনালে `Ctrl + C` চাপুন।

---