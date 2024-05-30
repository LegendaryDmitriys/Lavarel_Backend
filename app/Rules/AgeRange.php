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
