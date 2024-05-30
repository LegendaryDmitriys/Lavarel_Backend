<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Laravel\Prompts\search;

class SearchController extends Controller
{
    public function search($data, $name, $price){

        $res = [];

        foreach ($data as $category => $details){
            if ($details['name'] == $name || $details['price'] == $price){
                $res[] = $details;
            }
        }
        $res = array_unique($res, SORT_REGULAR);


        return $res;
    }

    public function dataSearch(){
         $data = [
            'category1' => [
                'price' => 100,
                'name' => 'name1'
            ],
            'category2' => [
                'price' => 250,
                'name' => 'name2'
            ],
            'category3' => [
                'price' => 100,
                'name' => 'name3'
            ]
        ];

        $seacrhRes = $this -> search($data, 'name5', 100);

        dump($seacrhRes ? 'Категория найдена: ' . print_r($seacrhRes, true) : 'Категория не найдена.');

    }

}
