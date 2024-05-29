<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
     public function ApiData()
    {
        try {
            $jsonResponse = Storage::get('products.json');
            $products = json_decode($jsonResponse, true)['call'];
        } catch (\Exception $exception){
            Log::error('Ошибка при чтении JSON'.$exception->getMessage());
            return response()->json(['error' => 'Ошибка при чтении JSON']);
        }

        $data = [];

        foreach ($products as $productName => $productDetails) {
            if(isset($productDetails['tradeble']) && $productDetails['tradeble'] == "true") {
                $imageUrl = $productDetails['image']['link'];
                $imageName = $productDetails['image_name'] .'.jpg';
                $imageContent = @file_get_contents($imageUrl);


                $filePatch = 'images/' . $imageName;
                Storage::put($filePatch, $imageContent);

                $data[] = [
                    'image_name' => $imageName,
                    'file_path' => Storage::url($filePatch),
                    'link' => $imageUrl,
                    'name' => $productName
                ];
            }
        }
        return response()->json($data);
    }
}
