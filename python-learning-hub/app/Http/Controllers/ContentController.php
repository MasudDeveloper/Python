<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function home()
    {
        $sidebar = $this->getSidebar();
        return view('home', compact('sidebar'));
    }

    public function show($category, $slug)
    {
        // Only allow specific categories
        if (!in_array($category, ['tutorials', 'projects', 'libraries', 'core', 'database', 'desktop', 'interview'])) {
            abort(404);
        }

        $sidebar = $this->getSidebar();
        $path = resource_path("content/{$category}/{$slug}.md");

        if (!File::exists($path)) {
            abort(404);
        }

        $content = File::get($path);
        $htmlContent = Str::markdown($content);

        // Extract title from first line if possible (assuming it starts with #)
        $firstLine = Str::of($content)->explode("\n")->first();
        $title = trim(str_replace('#', '', $firstLine));
        if (empty($title)) {
            $title = Str::title(str_replace('-', ' ', $slug));
        }

        return view('content', compact('sidebar', 'htmlContent', 'title', 'category'));
    }

    private function getSidebar()
    {
        $sidebar = [
            'core' => [],
            'database' => [],
            'desktop' => [],
            'tutorials' => [],
            'projects' => [],
            'interview' => [],
            'libraries' => [] // Will become associative array of categories
        ];

        $libraryCategories = [
            'Web Development' => ['module-django', 'module-fastapi', 'module-flask'],
            'Data Science' => ['module-pandas', 'module-numpy', 'module-matplotlib'],
            'Web Scraping & API' => ['module-requests', 'module-beautifulsoup4', 'module-selenium'],
            'Machine Learning & AI' => ['module-scikit-learn', 'module-tensorflow'],
            'Image Processing & Others' => ['module-opencv', 'module-pillow'],
            'File & System' => ['module-os', 'module-sys', 'module-shutil', 'module-pathlib'],
            'Data & Time' => ['module-datetime', 'module-collections', 'module-json'],
            'Math & Logic' => ['module-math', 'module-random', 'module-statistics'],
            'Advanced Utility' => ['module-re', 'module-itertools', 'module-functools'],
            'Networking & Web' => ['module-urllib', 'module-socket'],
            'Concurrency' => ['module-threading', 'module-multiprocessing', 'module-asyncio'],
        ];

        foreach (array_keys($sidebar) as $category) {
            $path = resource_path("content/{$category}");
            if (File::exists($path)) {
                $files = File::files($path);
                foreach ($files as $file) {
                    if ($file->getExtension() === 'md') {
                        $name = $file->getFilenameWithoutExtension();
                        
                        $content = File::get($file->getPathname());
                        $firstLine = Str::of($content)->explode("\n")->first();
                        $title = trim(str_replace('#', '', $firstLine));
                        if (empty($title)) {
                            $title = str_replace('-', ' ', Str::title($name));
                        }

                        $item = [
                            'title' => $title,
                            'slug' => $name,
                            'url' => url("/{$category}/{$name}")
                        ];

                        if ($category === 'libraries') {
                            $libCat = 'Others';
                            foreach ($libraryCategories as $catName => $slugs) {
                                if (in_array($name, $slugs)) {
                                    $libCat = $catName;
                                    break;
                                }
                            }
                            if (!isset($sidebar['libraries'][$libCat])) {
                                $sidebar['libraries'][$libCat] = [];
                            }
                            $sidebar['libraries'][$libCat][] = $item;
                        } else {
                            $sidebar[$category][] = $item;
                        }
                    }
                }
            }

            // Sorting
            if ($category === 'libraries') {
                foreach ($sidebar['libraries'] as &$catItems) {
                    usort($catItems, function($a, $b) {
                        return strnatcmp($a['slug'], $b['slug']);
                    });
                }
            } else {
                usort($sidebar[$category], function($a, $b) {
                    return strnatcmp($a['slug'], $b['slug']);
                });
            }
        }

        return $sidebar;
    }
}
