# Django (Zero to Hero) কমপ্লিট গাইড

পাইথনের সবচেয়ে বিখ্যাত, বড় এবং পাওয়ারফুল ওয়েব ফ্রেমওয়ার্ক হলো **Django** (উচ্চারণ: জ্যাঙ্গো)। ইন্সটাগ্রাম (Instagram), পিন্টারেস্ট (Pinterest), বা স্পটিফাইয়ের (Spotify) মতো বড় বড় কোম্পানিগুলো তাদের ব্যাকএন্ডে জ্যাঙ্গো ব্যবহার করে।

জ্যাঙ্গো **"Batteries-Included"** কনসেপ্ট ফলো করে। অর্থাৎ, একটি সিকিউর ওয়েবসাইট বানানোর জন্য যা যা লাগে (লগিন সিস্টেম, ডেটাবেস ORM, সিকিউরিটি)—সবকিছু জ্যাঙ্গোতে আগে থেকেই বানিয়ে দেওয়া আছে।

এই টিউটোরিয়ালে আমরা একদম **বিগিনার (Beginner)** লেভেলের প্রোজেক্ট সেটআপ থেকে শুরু করে **অ্যাডভান্সড (Advanced)** লেভেলের Signals, ORM Query এবং Django Rest Framework (DRF) পর্যন্ত বিস্তারিত শিখবো।

---

## 🟢 বিগিনার লেভেল (Beginner)

### ১. ইনস্টলেশন এবং প্রোজেক্ট তৈরি (Setup)
প্রথমে জ্যাঙ্গো ইনস্টল করে একটি প্রোজেক্ট শুরু করা যাক:
```bash
pip install django

# 'mywebsite' নামে একটি নতুন প্রোজেক্ট তৈরি করা
django-admin startproject mywebsite
cd mywebsite
```
জ্যাঙ্গোতে একটি বড় প্রোজেক্টকে অনেকগুলো ছোট ছোট অ্যাপে (App) ভাগ করা হয়। চলুন 'blog' নামে একটি অ্যাপ বানাই:
```bash
python manage.py startapp blog
```
এরপর `mywebsite/settings.py` ফাইলে গিয়ে `INSTALLED_APPS` এর ভেতর `'blog'` নামটি যুক্ত করে দিতে হবে।

### ২. হ্যালো ওয়ার্ল্ড ভিউ (Views) এবং ইউআরএল (URLs)
ভিউ (View) হলো মূলত একটি ফাংশন, যা ইউজারের রিকোয়েস্ট নিয়ে রেসপন্স ফেরত দেয়।

```python
# blog/views.py
from django.http import HttpResponse

def home(request):
    return HttpResponse("<h1>Hello World! Welcome to Django.</h1>")
```
এবার এই ভিউকে একটি লিংকের (URL) সাথে যুক্ত করতে হবে। প্রথমে `mywebsite/urls.py` তে যান:
```python
# mywebsite/urls.py
from django.contrib import admin
from django.urls import path, include

urlpatterns = [
    path('admin/', admin.site.urls),
    path('', include('blog.urls')), # blog অ্যাপের ইউআরএলগুলো এখানে যুক্ত হলো
]
```
এবার `blog` ফোল্ডারের ভেতর `urls.py` নামে একটি নতুন ফাইল বানান:
```python
# blog/urls.py
from django.urls import path
from . import views

urlpatterns = [
    path('', views.home, name='home'), 
]
```
টার্মিনালে `python manage.py runserver` লিখে ব্রাউজারে `http://127.0.0.1:8000` এ গেলে "Hello World!" দেখতে পাবেন।

---

## 🟡 ইন্টারমিডিয়েট লেভেল (Intermediate)

### ৩. ডেটাবেস মডেল এবং মাইগ্রেশন (Models & Migrations)
জ্যাঙ্গোর সবচেয়ে বড় ম্যাজিক হলো এর ORM। আপনাকে SQL কোড লিখতে হবে না, শুধু পাইথন ক্লাস লিখবেন।

```python
# blog/models.py
from django.db import models
from django.contrib.auth.models import User

class Post(models.Model):
    title = models.CharField(max_length=200) # ছোট টেক্সট
    content = models.TextField() # বড় টেক্সট
    author = models.ForeignKey(User, on_delete=models.CASCADE) # One-to-Many রিলেশনশিপ
    created_at = models.DateTimeField(auto_now_add=True) # অটোমেটিক সময় সেভ হবে

    # এডমিন প্যানেলে অবজেক্টের নাম হিসেবে title দেখাবে
    def __str__(self):
        return self.title
```
কোড লেখা শেষে ডেটাবেস টেবিল তৈরি করতে টার্মিনালে কমান্ড দিন:
```bash
python manage.py makemigrations
python manage.py migrate
```

### ৪. এডমিন প্যানেল কাস্টমাইজেশন (Admin Panel)
জ্যাঙ্গোতে রেডিমেড একটি এডমিন প্যানেল থাকে (লগিনের জন্য `python manage.py createsuperuser` দিয়ে ইউজার বানাতে হয়)। আমরা চাইলে এডমিন প্যানেলটিকে আরও সুন্দর করতে পারি।

```python
# blog/admin.py
from django.contrib import admin
from .models import Post

# শুধু register না করে কাস্টমাইজ করা
@admin.register(Post)
class PostAdmin(admin.ModelAdmin):
    list_display = ('title', 'author', 'created_at') # কলাম হিসেবে এগুলো দেখাবে
    search_fields = ('title', 'content') # সার্চ বক্স যুক্ত হবে
    list_filter = ('created_at', 'author') # ডানপাশে ফিল্টার অপশন আসবে
```

### ৫. ফর্মস (Django Forms)
ইউজারের কাছ থেকে ডেটা নেওয়ার জন্য HTML এ ফর্ম না লিখে, জ্যাঙ্গোর ফর্মস ব্যবহার করা সিকিউরড।

```python
# blog/forms.py
from django import forms
from .models import Post

class PostForm(forms.ModelForm):
    class Meta:
        model = Post
        fields = ['title', 'content']
```

---

## 🔴 অ্যাডভান্সড লেভেল (Advanced)

### ৬. ক্লাস-বেসড ভিউ (Class-Based Views / CBV)
ফাংশন-বেসড ভিউ (FBV) এর বদলে আমরা চাইলে OOP কনসেপ্ট ব্যবহার করে রেডিমেড ক্লাস-বেসড ভিউ ব্যবহার করতে পারি, এতে কোড অনেক ছোট হয়ে যায়!

```python
# blog/views.py
from django.views.generic import ListView, DetailView
from .models import Post

# সবগুলো পোস্ট দেখানোর ভিউ (ListView)
class PostListView(ListView):
    model = Post
    template_name = 'blog/home.html'
    context_object_name = 'posts' # HTML এ এই নামে ডেটা পাঠানো হবে
    ordering = ['-created_at'] # নতুনগুলো আগে আসবে

# নির্দিষ্ট একটি পোস্ট বিস্তারিত দেখার ভিউ (DetailView)
class PostDetailView(DetailView):
    model = Post
    template_name = 'blog/post_detail.html'
```

### ৭. অ্যাডভান্সড ডেটাবেস কোয়েরি (ORM: Q and F Objects)
যখন ডেটাবেস থেকে জটিল কন্ডিশন দিয়ে ডেটা খুঁজতে হয়:

```python
from blog.models import Post
from django.db.models import Q, F

# ১. সাধারণ ফিল্টার: যার Author 'admin'
admin_posts = Post.objects.filter(author__username='admin')

# ২. Q Object (OR কন্ডিশন): টাইটেলে 'Python' অথবা 'Django' আছে এমন পোস্ট
posts = Post.objects.filter(Q(title__icontains='Python') | Q(title__icontains='Django'))

# ৩. F Object: ধরুন পোস্টে views নামে একটি ফিল্ড আছে। সব পোস্টের ভিউ ১ করে বাড়াতে চাই।
# এটি ডেটাবেস লেভেলে ফাস্ট আপডেট করে।
Post.objects.all().update(views=F('views') + 1)
```

### ৮. সিগন্যালস (Django Signals)
সিগন্যালস হলো ট্রিগার। যেমন: "নতুন ইউজার রেজিস্ট্রেশন করলে অটোমেটিক তার একটি প্রোফাইল তৈরি হয়ে যাবে"। এখানে 'রেজিস্ট্রেশন' হলো ট্রিগার।

```python
# blog/signals.py
from django.db.models.signals import post_save
from django.dispatch import receiver
from django.contrib.auth.models import User
from .models import Profile # ধরি Profile নামে একটি মডেল আছে

# যখনই User মডেলে নতুন ডেটা সেভ (post_save) হবে, এই ফাংশনটি রান হবে!
@receiver(post_save, sender=User)
def create_user_profile(sender, instance, created, **kwargs):
    if created: # যদি ইউজারটি নতুন তৈরি হয়
        Profile.objects.create(user=instance)
```

### ৯. API তৈরি করা (Django Rest Framework - DRF)
বর্তমান যুগে জ্যাঙ্গো দিয়ে শুধু ওয়েবসাইট বানানো হয় না, মূলত React বা মোবাইল অ্যাপের জন্য API (JSON Date) বানানো হয়। এর জন্য **Django Rest Framework (DRF)** ব্যবহৃত হয়।

প্রথমে ইনস্টল করুন: `pip install djangorestframework`

```python
# blog/serializers.py (মডেলকে JSON এ রূপান্তর করার টুল)
from rest_framework import serializers
from .models import Post

class PostSerializer(serializers.ModelSerializer):
    class Meta:
        model = Post
        fields = '__all__'

# blog/api_views.py
from rest_framework.decorators import api_view
from rest_framework.response import Response
from .models import Post
from .serializers import PostSerializer

@api_view(['GET'])
def api_all_posts(request):
    posts = Post.objects.all()
    # সবগুলো পোস্টকে JSON এ রূপান্তর করা হলো (many=True দিয়ে বোঝানো হলো এখানে লিস্ট আছে)
    serializer = PostSerializer(posts, many=True)
    return Response(serializer.data)
```

### সারসংক্ষেপ (Conclusion)
আপনি যদি কোনো ই-কমার্স, সোশ্যাল মিডিয়া, বা এন্টারপ্রাইজ লেভেলের বড় ওয়েবসাইট বানাতে চান—যেখানে সিকিউরিটি, ডেটাবেস স্কেলিং এবং অ্যাডমিন প্যানেল জরুরি, সেখানে **Django** এর কোনো বিকল্প নেই! এটি শেখা শুরুতে কিছুটা কঠিন মনে হলেও, একবার এর স্ট্রাকচার বুঝে গেলে আপনি যেকোনো প্রোজেক্ট খুব দ্রুত দাঁড় করাতে পারবেন।
