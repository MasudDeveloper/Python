# ৪৫. ফুল-স্ট্যাক ই-কমার্স ওয়েবসাইট (E-commerce Web App)

আপনি যদি ওয়েব ডেভেলপমেন্টে ক্যারিয়ার গড়তে চান, তবে পোর্টফোলিওতে একটি ফুল-স্ট্যাক ই-কমার্স ওয়েবসাইট থাকা বাধ্যতামূলক। শুধুমাত্র ফ্রন্টএন্ড ডিজাইন করলেই হবে না, রিয়েল-ওয়ার্ল্ড ই-কমার্সে ডাটাবেস ম্যানেজমেন্ট এবং পেমেন্ট গেটওয়ে (Payment Gateway) অ্যাড করা খুবই গুরুত্বপূর্ণ।

এই প্রজেক্টে আমরা **Django** ফ্রেমওয়ার্ক এবং **Stripe** পেমেন্ট গেটওয়ে ব্যবহার করে একটি ই-কমার্স স্টোরের চেকআউট (Checkout) বা পেমেন্ট সিস্টেম তৈরি করবো।

### কীভাবে কাজ করে? (How it works):
1. **Product Database:** জ্যাঙ্গো (Django) এর মডেল (Model) ব্যবহার করে ডাটাবেসে প্রোডাক্টের নাম এবং দাম সেভ করা হবে।
2. **Checkout Page:** ইউজার যখন কোনো প্রোডাক্ট কেনার জন্য 'Buy Now' বাটনে ক্লিক করবে, তখন সে একটি চেকআউট পেইজে চলে যাবে।
3. **Stripe Integration:** পেমেন্টের জন্য আমরা ইউজারের ক্রেডিট কার্ডের ডেটা সরাসরি আমাদের ডাটাবেসে সেভ করবো না (এটি রিস্কি)। আমরা Stripe API ব্যবহার করে সিকিউরডভাবে পেমেন্ট প্রসেস করবো।
4. **Order Confirmation:** পেমেন্ট সফল হলে ইউজারের অর্ডার কনফার্ম করে ডাটাবেসে সেভ করা হবে এবং তাকে একটি Success পেইজে পাঠিয়ে দেওয়া হবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:

```bash
pip install django stripe
```

### প্রজেক্টের কোড:

পুরো জ্যাঙ্গো প্রজেক্ট একটি ফাইলে লেখা সম্ভব নয়, তবে নিচে ই-কমার্সের সবচেয়ে গুরুত্বপূর্ণ অংশ—**পেমেন্ট ভিউ (Payment View)** এর লজিক দেওয়া হলো:

```python
import stripe
from django.shortcuts import render, redirect
from django.conf import settings
from django.http import JsonResponse
from .models import Product, Order

# আপনার Stripe সিক্রেট কি (Secret Key) সেট করুন
stripe.api_key = settings.STRIPE_SECRET_KEY

def checkout(request, product_id):
    """ইউজারকে চেকআউট পেইজ দেখানো"""
    product = Product.objects.get(id=product_id)
    context = {
        'product': product,
        # ফ্রন্টএন্ডে কার্ডের ইনফো নেওয়ার জন্য পাবলিক কি দরকার
        'stripe_public_key': settings.STRIPE_PUBLIC_KEY 
    }
    return render(request, 'store/checkout.html', context)

def process_payment(request, product_id):
    """Stripe এর মাধ্যমে পেমেন্ট প্রসেস করা"""
    if request.method == 'POST':
        product = Product.objects.get(id=product_id)
        # ফ্রন্টএন্ড থেকে আসা Stripe টোকেন
        stripe_token = request.POST.get('stripeToken') 

        try:
            # Stripe এ কাস্টমারের পেমেন্ট চার্জ করা
            charge = stripe.Charge.create(
                # Stripe এ টাকার অ্যামাউন্ট সেন্ট (Cent) এ হিসাব হয়, তাই ১০০ দিয়ে গুণ
                amount=int(product.price * 100), 
                currency='usd',
                description=f'Payment for {product.name}',
                source=stripe_token,
            )

            # পেমেন্ট সফল হলে ডাটাবেসে অর্ডার সেভ করা
            if charge.paid:
                Order.objects.create(
                    product=product,
                    customer_email=request.POST.get('email'),
                    amount_paid=product.price,
                    status='Completed'
                )
                # পেমেন্ট সাকসেস পেইজে পাঠিয়ে দেওয়া
                return redirect('payment_success')

        except stripe.error.CardError as e:
            # কার্ডে ব্যালেন্স না থাকলে বা ভুল হলে
            body = e.json_body
            err  = body.get('error', {})
            print(f"Card Error: {err.get('message')}")
            return redirect('payment_failed')
            
        except Exception as e:
            # অন্য যেকোনো সার্ভার এরর
            print(f"Server Error: {e}")
            return redirect('payment_failed')

    return redirect('home')

def payment_success(request):
    """পেমেন্ট সফল হওয়ার পর থ্যাংক ইউ পেইজ"""
    return render(request, 'store/success.html')
```

### কোডটি কীভাবে শিখবেন?
1. **Django Models:** `Product.objects.get(id=product_id)` এর মাধ্যমে আমরা ডাটাবেস (SQL) থেকে নির্দিষ্ট আইডি ধরে একটি প্রোডাক্টের তথ্য খুঁজে বের করেছি। জ্যাঙ্গোতে ডাটাবেস হ্যান্ডেল করা খুবই সহজ!
2. **Stripe Token:** পেমেন্ট করার সময় ইউজারের কার্ড নম্বর আমাদের সার্ভারে আসে না। ফ্রন্টএন্ড থেকে Stripe সরাসরি ডেটা নিয়ে আমাদের শুধু একটি ইউনিক টোকেন (`stripe_token`) দেয়। এটি হ্যাকিংয়ের ঝুঁকি শূন্য করে দেয়।
3. **Error Handling (try-except):** পেমেন্টের সময় নানা সমস্যা হতে পারে (যেমন ইউজারের কার্ড ব্লক থাকা)। তাই আমরা `try-except` ব্যবহার করে `stripe.error.CardError` গুলো ক্যাচ (Catch) করেছি, যেন সমস্যা হলে ওয়েবসাইট ক্র্যাশ না করে বরং ইউজারকে সুন্দর একটি মেসেজ দেখায়। 

> [!TIP]
> **লোকাল পেমেন্ট গেটওয়ে:** আপনি যদি বাংলাদেশের জন্য ওয়েবসাইট বানান, তবে Stripe এর বদলে **SSLCommerz** বা **aamarPay** এর API ব্যবহার করতে পারেন। তাদের ডকুমেন্টেশন দেখে ঠিক এভাবেই API রিকোয়েস্ট পাঠাতে হয়।
