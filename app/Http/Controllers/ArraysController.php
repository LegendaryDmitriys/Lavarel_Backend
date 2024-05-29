<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ArraysController extends Controller
{

    public function Arrays(){
        $data = [
            'category' => [
                'one' => [
                    'priority' => '3',
                    'views' => [
                        'user_count' => 345,
                        'bot_count' => 9392
                    ]
                ],
                'two' => [
                    'priority' => '1',
                    'views' => [
                        'user_count' => 123222,
                        'bot_count' => 99
                    ]
                ],
                'three' => [
                    'priority' => '2',
                    'views' => [
                        'user_count' => 23,
                        'bot_count' => 1
                    ]
                ],
            ],
        ];

        $botCounts = array_column(array_column($data['category'], 'views'), 'bot_count');
        $maxBotCount = max($botCounts);
        $minBotCount = min($botCounts);


        $sorted = $data['category'];
        array_multisort(array_column($sorted, 'priority'), SORT_ASC, $sorted);



        $userCounts = [];
        $botCounts = [];
        foreach ($sorted as $item) {
            $userCounts[] = $item['views']['user_count'];
            $botCounts[] = $item['views']['bot_count'];
        }

        echo "Максимальный 'bot_count': " . $maxBotCount . "\n";
        echo "Минимальный 'bot_count': " . $minBotCount . "\n";
        echo "Все значения 'user_count': " . implode(", ", $userCounts) . "\n";
        echo "Все значения 'bot_count': " . implode(", ", $botCounts) . "\n";

    }
}
