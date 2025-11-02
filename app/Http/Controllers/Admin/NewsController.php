<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderBy('date','desc')->get();
        return Inertia::render('Admin/News/Index', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $stripped = strip_tags($value);
                    if (empty(trim($stripped))) {
                        $fail('The content field is required.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $data = $request->only(['title','excerpt','content','category','date']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('image')) {
            // allow passing an image path or url as string
            $data['image'] = $request->input('image');
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::findOrFail($id);
        return Inertia::render('Admin/News/Show', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);
        return Inertia::render('Admin/News/Edit', [
            'news' => $news,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $stripped = strip_tags($value);
                    if (empty(trim($stripped))) {
                        $fail('The content field is required.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $data = $request->only(['title','excerpt','content','category','date']);

        if ($request->hasFile('image')) {
            // delete old file if exists
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);
        // delete stored image if present
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        if (request()->expectsJson()) {
            return response()->json(['message' => 'News deleted successfully']);
        }

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    /**
     * Upload image for Quill editor.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $path = $request->file('image')->store('news', 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json(['url' => $url]);
    }
}
