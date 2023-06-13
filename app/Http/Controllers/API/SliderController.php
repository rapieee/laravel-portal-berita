<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;


class SliderController extends Controller
{
    public function index() {
        try {
            $slider = Slider::latest()->paginate('10');

            if($slider){
                return ResponseFormatter::success($slider, 'Data slider berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data slider tidak ada', 404);
            }
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authenticated Failed', 500);
        }
    }

    public function create(Request $request)
    {
        try {
            //validate request
            $this->validate($request, [
                'url' => 'required|string|max:225',
                'image' => 'required|mimes:png,jpg,jpeg'
            ]);

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/sliders', $image->hashName());

            $slider = Slider::create([
                'url' => $request->url,
                'image' => $image->hashName()
            ]);

            if ($slider) {
                return ResponseFormatter::success($slider, 'Data slider berhasil ditambahkan');
            } else {
                return ResponseFormatter::error(null, 'Data slider gagal ditemukan', 404);
            }
            
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'data' => null,
                'message' => 'Data gagal ditemukan',
                'error' => $error
            ]);
        }
        
    }
}
