<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(strpos($value, "-#-")) {
            $data = explode("-#-", $value);
            if(!is_numeric($data[1]) || count($data) != 2) {
                $fail("دیتا نادرست است از لیست انتخاب کنید");
            }
        } else {
            $fail("دیتا نادرست است از لیست انتخاب کنید");
        }
    }
}
