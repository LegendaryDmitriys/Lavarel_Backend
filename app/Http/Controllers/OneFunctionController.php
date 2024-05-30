<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OneFunctionController extends Controller
{
    public function getData(){
        return mt_rand(0, 60);
    }

    public function low_quantity($data)
    {
        return $data - ($data * 0.5);
    }

    public function high_quantity($data)
    {
        return $data * 0.5;
    }

    public function medium_quantity()
    {
        return 0;
    }

    public function handlerData()
    {
        $data = $this->getData();

        if ($data < 7){
            $result =$this->low_quantity($data);
        } elseif ($data > 40){
            $result = $this->high_quantity($data);
        } elseif ($data == 10){
            $result = $this->medium_quantity();
        } else {
            $result = 0;
        }

        $round = round($result);
        return $round;
    }

   public function countUnique($start, $end)
   {
        $unique = [];

        for ($i = $start; $i <= $end; $i++) {
            $data = $this->getData();
            $res = $this->handlerData($data);

            if (!in_array($res, $unique)){
                $unique[] = $res;
            }

        }

        return count($unique);
   }

    public function testCountUnique(){
        $count = $this->countUnique(1,15);
        $count2 = $this->countUnique(3,55);
        $count3 = $this->countUnique(9,43);

        echo "\n"."Уникальные значения 1-15:". " " .$count ."\n","Уникальные значения 3-55:" . " " .$count2 ."\n", "Уникальные значения 9-43:" . " " .$count3;
    }
}
