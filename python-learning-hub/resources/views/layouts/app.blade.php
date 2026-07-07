<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Python Learning Hub')</title>
    <!-- Tailwind CSS (CDN for simplicity) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tiro+Bangla:ital@0;1&display=swap');
        
        body {
            font-family: 'Tiro Bangla', serif;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 10px;
        }

        /* Markdown Content Styling */
        .markdown-body h1 { font-size: 2.25rem; font-weight: bold; color: #1e3a8a; border-bottom: 2px solid #bfdbfe; padding-bottom: 0.5rem; margin-bottom: 1.5rem; margin-top: 2rem;}
        .markdown-body h2 { font-size: 1.875rem; font-weight: bold; color: #1e40af; margin-top: 2rem; margin-bottom: 1rem; }
        .markdown-body h3 { font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-top: 1.5rem; margin-bottom: 1rem; }
        .markdown-body p { font-size: 1.125rem; line-height: 1.75; color: #374151; margin-bottom: 1.25rem; }
        .markdown-body ul { list-style-type: disc; padding-left: 2rem; margin-bottom: 1.25rem; font-size: 1.125rem; color: #4b5563; }
        .markdown-body li { margin-bottom: 0.5rem; }
        .markdown-body pre { background-color: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; font-family: ui-monospace, monospace; }
        .markdown-body code { font-family: ui-monospace, monospace; background-color: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 0.25rem; color: #b91c1c; font-size: 0.9em;}
        .markdown-body pre code { background-color: transparent; padding: 0; color: inherit; }
        .markdown-body blockquote { border-left: 4px solid #3b82f6; background-color: #eff6ff; padding: 1rem; margin-bottom: 1.5rem; color: #1e40af; border-radius: 0 0.5rem 0.5rem 0; }
        .markdown-body a { color: #2563eb; text-decoration: none; }
        .markdown-body a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-80 bg-gray-900 text-white flex flex-col h-full shadow-xl z-10 flex-shrink-0">
        <div class="p-6 bg-gray-800 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-blue-400">পাইথন লার্নিং হাব</h2>
            <p class="text-gray-400 text-sm mt-1">প্রজেক্ট ও টিউটোরিয়াল কালেকশন</p>
            <input type="text" id="searchInput" class="mt-4 w-full bg-gray-700 text-white rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="সার্চ করুন...">
        </div>
        
        <div class="flex-1 overflow-y-auto sidebar-scroll p-4">
            <!-- Core Python -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Core Python (কোর পাইথন)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['core'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('core/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">শীঘ্রই আসছে...</li>
                    @endforelse
                </ul>
            </div>

            <!-- Database -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Database (ডেটাবেস)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['database'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('database/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">শীঘ্রই আসছে...</li>
                    @endforelse
                </ul>
            </div>

            <!-- Desktop Software -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Desktop GUI (ডেস্কটপ সফটওয়্যার)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['desktop'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('desktop/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">শীঘ্রই আসছে...</li>
                    @endforelse
                </ul>
            </div>

            <!-- Projects & Roadmap -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Projects (প্রজেক্টস ও রোডম্যাপ)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['projects'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('projects/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">শীঘ্রই আসছে...</li>
                    @endforelse
                </ul>
            </div>

            <!-- Interview Q&A -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Interview Q&A (ইন্টারভিউ প্রস্তুতি)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['interview'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('interview/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">শীঘ্রই আসছে...</li>
                    @endforelse
                </ul>
            </div>

            <!-- Tutorials -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Tutorials (টিউটোরিয়াল)</h3>
                <ul class="space-y-1">
                    @forelse($sidebar['tutorials'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('tutorials/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2 text-gray-500 text-sm italic">কোনো টিউটোরিয়াল নেই</li>
                    @endforelse
                </ul>
            </div>

            <!-- Libraries -->
            <div class="mb-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Libraries (লাইব্রেরি/মডিউল)</h3>
                @forelse($sidebar['libraries'] as $catName => $items)
                    <div class="mb-4">
                        <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 pl-2 border-l-2 border-gray-600">{{ $catName }}</h4>
                        <ul class="space-y-1">
                            @foreach($items as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" class="searchable-item block px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white transition-colors {{ request()->is('libraries/'.$item['slug']) ? 'bg-blue-600 text-white hover:bg-blue-700' : '' }}">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="px-3 py-2 text-gray-500 text-sm italic">কোনো লাইব্রেরি নেই</div>
                @endforelse
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
        
        <!-- Navbar / Header -->
        <header class="bg-white border-b border-gray-200 shadow-sm z-10 p-4 flex justify-between items-center flex-shrink-0">
            <h1 class="text-xl font-bold text-gray-800">পাইথন প্রজেক্টস ও লাইব্রেরি</h1>
            <nav class="space-x-6 text-sm font-medium text-gray-600">
                <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                <a href="https://github.com" target="_blank" class="hover:text-blue-600 transition-colors">GitHub</a>
                <a href="#" class="hover:text-blue-600 transition-colors">About</a>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto" id="main-content">
            <div class="max-w-4xl mx-auto py-10 px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.searchable-item');
            
            items.forEach(item => {
                // If nested in categories, parent is li -> ul -> div (the category block)
                if (item.textContent.toLowerCase().includes(term)) {
                    item.parentElement.style.display = 'block';
                } else {
                    item.parentElement.style.display = 'none';
                }
            });
        });

        // Scroll sidebar to active item on load
        document.addEventListener('DOMContentLoaded', function() {
            const activeItem = document.querySelector('.searchable-item.bg-blue-600');
            if (activeItem) {
                // Scroll the sidebar so the active item is in the center
                activeItem.scrollIntoView({ behavior: 'auto', block: 'center' });
            }
        });
    </script>
</body>
</html>
