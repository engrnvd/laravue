<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    private $mobileOnly;

    public function __construct($mobileOnly = false)
    {
        $this->mobileOnly = $mobileOnly;
    }

    public function passes($attribute, $value)
    {
        if ($this->mobileOnly) return $this->isMobile($value);
        return $this->isPhone($value) || $this->isMobile($value);
    }

    private function isPhone($value)
    {
        return preg_match('/^0\d{9}$/', $value); // 051-51-30224
    }

    private function isMobile($value)
    {
        return preg_match('/^(0|(\+|00)92)3\d{9}$/', $value); // 03336353288 | +92333... | 0092333...
    }

    public function message()
    {
        $t = $this->mobileOnly ? 'mobile' : 'phone';
        return "Please enter a valid {$t} number";
    }
}
