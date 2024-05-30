<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


<h1 align="center"> Разработка на Lavarel </h1>

# Задание 1 

Апи возвращает json массив вида {“call”:{“product_name1”:{},“product_name2”:{}, …}. 
Необходимо обработать его, чтобы на выходе получить массив $data, где file_path путь к преобразованной и сохраненной под именем = ‘image_name ’ картинке. Условием для преобразования является значение tradeble = true.

```php
$data = [
	‘iamge_name’ = > ‘image_name’,
	‘link’ => ‘link’,
	‘file_path’ => ‘/image_folder/image_name.jpeg’,
	‘name’ => ‘name’
]
```


```php
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
```
Содержание json-файла: 
```json
{
  "call": {
    "product_name1": {
        "tradeble": "true",
        "name": "main_window",
        "image_name": "sun",
        "image": {
            "link": "https://img.freepik.com/premium-photo/sun-with-sun-sky_421632-12290.jpg",
            "base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAAAAAAD/2wBDAA..."
        }
    },
    "product_name2": {
          "tradeble": "false",
          "name": "another_window",
          "image_name": "sun",
          "image": {
            "link": "https://img.freepik.com/free-photo/fresh-yellow-daisy-single-flower-close-up-beauty-generated-by-ai_188544-15543.jpg",
            "base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAAAAAAD/2wBDAA..."
          }
    },
    "product_name3": {
        "tradeble": "true",
        "name": "main_window",
        "image_name": "moon",
        "image": {
            "link": "https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Moon_farside_LRO_color_mosaic.png/300px-Moon_farside_LRO_color_mosaic.png",
            "base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAAAAAAD/2wBDAA..."
        }
    }
  }
}

```



Полученный ответ:
```json
[
    {
        "image_name": "sun.jpg",
        "file_path": "\/storage\/images\/sun.jpg",
        "link": "https:\/\/img.freepik.com\/premium-photo\/sun-with-sun-sky_421632-12290.jpg",
        "name": "product_name1"
    },
    {
        "image_name": "moon.jpg",
        "file_path": "\/storage\/images\/moon.jpg",
        "link": "https:\/\/upload.wikimedia.org\/wikipedia\/commons\/thumb\/7\/73\/Moon_farside_LRO_color_mosaic.png\/300px-Moon_farside_LRO_color_mosaic.png",
        "name": "product_name3"
    }
]
```

# Задание 2

Дан примерный массив $data, необходимо написать код, который обработает данный массив следующим образом:
1. Выводит максимальный 'bot_count'
2. Выводит минимальный 'bot_count'
3. Выводит все значения user_count и bot_count в порядке ASC по значению 'priority'

Код должен оставаться полностью рабочим, при расширении массива без изменения его структуры
```php
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

    ]
];
```
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ArraysController extends Controller
{

    public function arrays(){
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
```
```
Максимальный 'bot_count': 9392
Минимальный 'bot_count': 1
Все значения 'user_count': 123222, 23, 345
Все значения 'bot_count': 99, 1, 9392
```
# Задание 3

Дан массив $data. Ожидаемая длина массива более ста. Необходимо реализовать функцию поиска, которая будет принимать [ price, name ] и возвращать элементы массива $data, в котором совпадение было найдено совпадение хотя бы по одному из атрибутов. Также необходимо, чтобы в возвращаемом массива отсутствовали дубликаты.

```php
$data = [
	‘category1’ => [
		‘price’ => int,
		‘name’ => ‘name’
	],
           ‘category2’ => [
		‘price’ => int,
		‘name’ => ‘name’
	],
            ‘category2’ => [
		‘price’ => int,
		‘name’ => ‘name’
	],
	…
]
```

```php
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

        dump($seacrhRes ? 'Найдено : ' . print_r($seacrhRes, true) : 'Категория не найдена.');

    }

}

```
![image](https://github.com/LegendaryDmitriys/Lavarel_Backend/assets/106886232/2376a3ba-b41c-4593-9374-5de8cb13fa50)

# Задание 4

1.Некая функция возвращает переменную $data типа int, необходимо написать обработчик, который в зависимости от возвращаемого значения будет вызывать определенную функцию, а после округлять результат до целых чисел. (условия применения функций описаны в приложении)
2. Взяв за основу написанный обработчик, необходимо реализовать еще одну функцию, которая вместо одного параметра принимает два. Функция должна выводить количество уникальных результатов выполнения основной функции при получении всех числовых значений между полученными параметрами, включая их.
3. Используя функцию из задачи 2 посчитать количество уникальных результатов выполнения, используя как параметры конечные точки указанных множеств: от 1 до 15; от 3 до 55; от 9 до 43;

В результате выполнения заданий должно получиться 5 функций и результаты выполнения функцией из задачи 2 задания 3.

Условия определения функции:
При $data меньше семи, выполнить функцию "low_quantity", в котором от значения параметра будет отнято $data * 0,5.
При $data больше сорока, выполнить функцию "high_quantity", в котором значение будет умножено на 0,5.
При $data равной десять, выполнить функцию "medium_quantity", в которой необходимо вернуть 0.

```php
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
```

```
Уникальные значения 1-15: 6 Уникальные значения 3-55: 12 Уникальные значения 9-43: 13
```

# Задание 5
На frontend стороне сайта реализована форма регистрации на сайте, которая при отправке пользователем формирует массив $data. Необходимо создать функцию, которая будет отвечать за проверку валидности приходящих данных. В случае успешной проверки функция должна вернуть подтверждающий статус и сообщение об успехе. В случае провала функция должна вернуть сообщение валидации, по которому проверка не была пройдена. 

Правила валидирования данных:
'email' - должен присутствовать, должен содержать “@”, должен быть длиннее пяти символов.
'password' - должен присутствовать, должен совпадать с полем 'repit_password', должен быть длиннее восьми символов, должен содержать буквы и цифры.
'repit_password' -  должен присутствовать, должен совпадать с полем 'repit_password'.
'phone_number' - если присутствует, должен быть длиннее 5 символов.
'name' -  должен присутствовать, может содержать только буквы.
'came_from' - если присутствует, должен совпадать с одним из элементов массива [‘site’, ‘city’, ‘tv’, ‘others’]
'date_birth'  - должен присутствовать, возраст должен быть больше 15 и меньше 67 лет


Массив $data
```php
 [
    'email' => string,
    'password' => string,
    'repit_password' => string,
    'phone_number' => int,
    'name' => string,
    'came_from' => string,
    'date_birth' => date,
 ]
```
Ответ функции:
```php
[	
	‘status’ => bol,
	‘message => string
]
```


```php
<?php

namespace App\Http\Controllers;

use App\Rules\AgeRange;
use Illuminate\Support\Facades\Validator;
class ValidationController extends Controller
{
    public function validateRequest(){
         $data = [
            'email' => "dimakllockov24@gmail.com",
            'password' => "Дима@1213",
            'repit_password' => "Дима@1213",
            'phone_number' => "123456789",
            'name' => "Дима",
            'came_from' => "tv",
            'date_birth' => "01/01/2009",
        ];
         $validated = Validator::make($data, [
            'email' => 'required|email|min:6',
            'password' => 'required|min:9|regex:/[a-zA-Zа-яА-ЯёЁ]/|regex:/[0-9]/|same:repit_password',
            'repit_password' => 'required|same:password',
            'phone_number' => 'digits_between:6,15',
            'name' => 'required|string|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/|max:30',
            'came_from' => 'string|in:site,city,tv,others',
            'date_birth' => ['required', 'date_format:d/m/Y', new AgeRange()],
         ], [
            'email.required' => 'Поле электронной почты обязательно для заполнения.',
            'email.email' => 'Поле электронной почты должно содержать действительный адрес электронной почты.',
            'email.min' => 'Электронная почта должна быть длиннее пяти символов.',
            'password.required' => 'Поле пароля обязательно для заполнения.',
            'password.min' => 'Пароль должен быть длиннее восьми символов.',
            'password.regex' => 'Пароль должен содержать буквы и цифры.',
            'password.same' => 'Пароль должен совпадать с полем подтверждения пароля.',
            'repit_password.required' => 'Поле подтверждения пароля обязательно для заполнения.',
            'repit_password.same' => 'Поле подтверждения пароля должно совпадать с паролем.',
            'phone_number.digits_between' => 'Номер телефона должен содержать от 5 до 15 цифр.',
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.regex' => 'Имя должно содержать только буквы.',
            'name.max' => 'Имя не должно превышать 30 символов.',
            'came_from.string' => 'Поле откуда пришел  должно быть строкой.',
            'came_from.in' => 'Поле откуда пришел должно содержать одно из допустимых значений.',
            'date_birth.required' => 'Поле даты рождения обязательно для заполнения.',
            'date_birth.date_format' => 'Дата рождения должна быть в формате дд/мм/гггг.',
         ]);

         if ($validated->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => $validated->errors()
            ]);
        }

        return response()->json([
            'status' => 'true',
            'message' => 'Регистрация успешна'
        ]);


    }
}
```

Для поля date_birth использовал кастомное правило валидации для проверки возраста:

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class AgeRange implements Rule
{
     /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       $dateBirth = Carbon::createFromFormat('d/m/Y', $value);
       $age = $dateBirth->diffInYears(Carbon::now());

       return $age > 15 && $age < 67;

    }

      /**
     * Get the validation error message.
     *
     * @return string
     */

    public function message()
    {
        return 'Возраст должен быть больше 15 и меньше 67 лет.';
    }

}

```


Ответ если данные указаны правильно:
```json
{
    "status": "true",
    "message": "Регистрация успешна"
}
```
Ответ, если данные не верны:
```json
{
    "status": "false",
    "message": {
        "email": [
            "Поле электронной почты должно содержать действительный адрес электронной почты."
        ],
        "password": [
            "Пароль должен быть длиннее восьми символов.",
            "Пароль должен содержать буквы и цифры.",
            "Пароль должен совпадать с полем подтверждения пароля."
        ],
        "repit_password": [
            "Поле подтверждения пароля должно совпадать с паролем."
        ],
        "came_from": [
            "Поле откуда пришел должно содержать одно из допустимых значений."
        ],
        "date_birth": [
            "Дата рождения должна быть в формате дд/мм/гггг.",
            "Возраст должен быть больше 15 и меньше 67 лет."
        ]
    }
}
```

