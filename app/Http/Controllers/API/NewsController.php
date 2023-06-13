<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class NewsController extends Controller
{
    public function index()
    {
        try {
            $news = News::latest()->paginate('10');

            if ($news) {
                return ResponseFormatter::success($news, 'Data news berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data news tidak ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authenticated Failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $news = News::findOrFail($id);

            if ($news) {
                return ResponseFormatter::success($news, 'Data news berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data news tidak ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authenticatedn Failed', 500);
        }
    }
}
