@extends('layouts.app')

@section('title', 'Welcome to Python Learning Hub')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-8 text-center mt-10">
    <div class="w-24 h-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>
    </div>
    <h1 class="text-4xl font-bold text-gray-800 mb-4">পাইথন লার্নিং হাবে আপনাকে স্বাগতম!</h1>
    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
        এটি আপনার ব্যক্তিগত পাইথন লার্নিং প্ল্যাটফর্ম। বামপাশের মেনু থেকে আপনার পছন্দমতো টিউটোরিয়াল, প্রজেক্ট অথবা লাইব্রেরি সিলেক্ট করে শেখা শুরু করুন।
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <div class="p-6 bg-blue-50 rounded-lg border border-blue-100">
            <h3 class="text-xl font-bold text-blue-800 mb-2">Tutorials</h3>
            <p class="text-blue-600 text-sm">পাইথনের বেসিক ও এডভান্সড কনসেপ্টগুলো শিখুন।</p>
        </div>
        <div class="p-6 bg-green-50 rounded-lg border border-green-100">
            <h3 class="text-xl font-bold text-green-800 mb-2">Projects</h3>
            <p class="text-green-600 text-sm">রিয়েল ওয়ার্ল্ড প্রজেক্ট তৈরি করে পোর্টফোলিও ভারী করুন।</p>
        </div>
        <div class="p-6 bg-purple-50 rounded-lg border border-purple-100">
            <h3 class="text-xl font-bold text-purple-800 mb-2">Libraries</h3>
            <p class="text-purple-600 text-sm">পাইথনের জনপ্রিয় সব মডিউল ও লাইব্রেরির ব্যবহার জানুন।</p>
        </div>
    </div>
</div>
@endsection
