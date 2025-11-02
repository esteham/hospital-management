<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('date', 'desc')->get();

        // ensure image field is a full URL when possible
        $newsTransformed = $news->map(function ($item) {
            if ($item->image) {
                $img = $item->image;
                if (!Str::startsWith($img, ['http://', 'https://'])) {
                    $item->image = Storage::url($img);
                }
            }
            return $item;
        });

        return response()->json($newsTransformed);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);

        // ensure image field is a full URL when possible
        if ($news->image) {
            $img = $news->image;
            if (!Str::startsWith($img, ['http://', 'https://'])) {
                $news->image = Storage::url($img);
            }
        }

        return response()->json($news);
    }
}
