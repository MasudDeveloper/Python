# ৩১. নিজস্ব এআই চ্যাটজিপিটি (RAG Pipeline AI)

বর্তমান সময়ে আর্টিফিশিয়াল ইন্টেলিজেন্স বা এআই (AI) এর সবচেয়ে অ্যাডভান্সড এবং ডিমান্ডিং টপিক হলো **RAG (Retrieval-Augmented Generation)**। 

সাধারণ চ্যাটজিপিটি (ChatGPT) কে যদি আপনি আপনার কোম্পানির নির্দিষ্ট কোনো রুলসবুক বা গত বছরের ফাইন্যান্সিয়াল রিপোর্ট সম্পর্কে প্রশ্ন করেন, সে উত্তর দিতে পারবে না, কারণ তার কাছে ওই প্রাইভেট ডেটা নেই। কিন্তু RAG আর্কিটেকচার ব্যবহার করে আমরা এমন একটি চ্যাটবট বানাতে পারি, যাকে আপনি পিডিএফ বা টেক্সট ফাইল দিয়ে দিলে সে শুধু ওই ফাইলের ভেতর থেকেই মানুষের প্রশ্নের সঠিক উত্তর খুঁজে দিবে!

### কীভাবে কাজ করে? (How it works):
1. **Document Loading:** প্রথমে আমরা আমাদের প্রাইভেট পিডিএফ (PDF) বা টেক্সট ফাইলটিকে পাইথনে রিড করবো।
2. **Text Splitting:** পুরো পিডিএফটিকে ছোট ছোট প্যারাগ্রাফ বা খণ্ডে (Chunks) ভাগ করা হবে, যাতে এআই সহজেই সেগুলো মনে রাখতে পারে।
3. **Vector Database:** এই ছোট খণ্ডগুলোকে `Embeddings` (গাণিতিক সংখ্যায়) রূপান্তর করে একটি ভেক্টর ডেটাবেসে (যেমন FAISS বা ChromaDB) সেভ করা হবে।
4. **Retrieval & QA:** এরপর ইউজার যখন কোনো প্রশ্ন করবে, বটটি ওই ডেটাবেস থেকে সবচেয়ে কাছাকাছি উত্তরটি খুঁজে বের করবে এবং OpenAI এর সাহায্যে সুন্দর ভাষায় গুছিয়ে উত্তর দিবে।

### প্রয়োজনীয় লাইব্রেরি (Libraries):
এই প্রজেক্টের জন্য আমাদের `langchain` (LLM ফ্রেমওয়ার্ক), `openai` (এআই মডেল), `faiss-cpu` (ভেক্টর ডেটাবেস) এবং `pypdf` (পিডিএফ রিডার) লাগবে।

টার্মিনালে নিচের কমান্ডটি লিখে লাইব্রেরিগুলো ইনস্টল করে নিন:
```bash
pip install langchain openai faiss-cpu pypdf tiktoken
```
*(বিঃদ্রঃ এই প্রজেক্টটি রান করার জন্য আপনার একটি OpenAI API Key লাগবে, যা আপনি platform.openai.com থেকে ফ্রিতে বা সামান্য খরচে সংগ্রহ করতে পারবেন)*

### প্রজেক্টের কোড:

ধরে নিচ্ছি আপনার ফোল্ডারে `company_rules.pdf` নামে একটি পিডিএফ ফাইল আছে। নিচের কোডটি কপি করে রান করুন।

```python
import os
from langchain.document_loaders import PyPDFLoader
from langchain.text_splitter import CharacterTextSplitter
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.vectorstores import FAISS
from langchain.chains import RetrievalQA
from langchain.llms import OpenAI

def custom_ai_chatbot():
    print("=== Custom AI Chatbot (RAG Pipeline) ===\n")
    
    # আপনার OpenAI API Key এখানে দিন
    os.environ["OPENAI_API_KEY"] = "sk-your-openai-api-key-here"
    
    # ১. পিডিএফ ফাইলটি লোড করা
    print("Loading PDF document...")
    loader = PyPDFLoader("company_rules.pdf")
    documents = loader.load()
    
    # ২. টেক্সটগুলোকে ছোট ছোট খণ্ডে (Chunks) ভাগ করা
    print("Splitting text into chunks...")
    text_splitter = CharacterTextSplitter(chunk_size=1000, chunk_overlap=200)
    texts = text_splitter.split_documents(documents)
    
    # ৩. ভেক্টর ডেটাবেস তৈরি করা (OpenAI Embeddings ব্যবহার করে)
    print("Building Vector Database (FAISS)...")
    embeddings = OpenAIEmbeddings()
    vector_store = FAISS.from_documents(texts, embeddings)
    
    # ৪. চ্যাটবটের লজিক বা চেইন (QA Chain) তৈরি করা
    print("Initializing AI Chatbot...\n")
    qa_bot = RetrievalQA.from_chain_type(
        llm=OpenAI(temperature=0), 
        chain_type="stuff", 
        retriever=vector_store.as_retriever()
    )
    
    # ৫. ইউজারের কাছ থেকে প্রশ্ন নেওয়া এবং উত্তর দেওয়া
    while True:
        query = input("Ask a question about the document (or type 'exit' to stop): ")
        
        if query.lower() == 'exit':
            print("Goodbye!")
            break
            
        print("Thinking...")
        # এআই কে প্রশ্নটি করা হচ্ছে
        result = qa_bot.run(query)
        
        print(f"\n🤖 Answer: {result}\n")
        print("-" * 50)

if __name__ == "__main__":
    custom_ai_chatbot()
```

> [!WARNING]
> **সতর্কতা:** OpenAI API ব্যবহারের জন্য ইন্টারনেট কানেকশন প্রয়োজন। আপনার API Key কখনো গিটহাব (GitHub) বা পাবলিক কোথাও শেয়ার করবেনবিধা।

### কোডটি কীভাবে শিখবেন?
1. **LangChain Framework:** ল্যাংচেইন (LangChain) হলো বর্তমান সময়ের সবচেয়ে জনপ্রিয় এলএলএম (LLM - Large Language Model) ফ্রেমওয়ার্ক। বিভিন্ন এআই টুলসকে একসাথে যুক্ত করে একটি 'চেইন' (Chain) বানানোর প্রসেস আপনি এখান থেকে শিখতে পারবেন।
2. **Text Embeddings & Vector Stores:** এআই কিন্তু আমাদের মতো শব্দ বোঝে না, সে বোঝে সংখ্যা (Numbers/Vectors)। `OpenAIEmbeddings` কীভাবে সাধারণ টেক্সটকে গাণিতিক ভেক্টরে রূপান্তর করে এবং `FAISS` কীভাবে সেই ভেক্টরগুলোকে দ্রুত সার্চ করার জন্য সেভ করে রাখে, সেটি এই প্রজেক্টের মূল জাদুকরী অংশ।
3. **Chunking Strategy:** `chunk_size=1000` এবং `chunk_overlap=200` এর মানে হলো পুরো বইটিকে ১০০০ ক্যারেক্টারের ছোট ছোট পৃষ্ঠায় ভাগ করা এবং প্রতিটি পৃষ্ঠার সাথে আগের পৃষ্ঠার ২০০ ক্যারেক্টার যুক্ত রাখা, যাতে কনটেক্সট (Context) হারিয়ে না যায়। এটি প্রম্পট ইঞ্জিনিয়ারিংয়ের একটি দারুণ টেকনিক!
