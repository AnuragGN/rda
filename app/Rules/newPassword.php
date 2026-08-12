<?php

namespace App\Rules;

use App\Models\ClientInfo;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class newPassword implements Rule
{
    public $minLength = 6;
    public $length = true;
    public $uppercase = true;
    public $lowercase = true;
    public $numeric = true;
    public $specialChar = true;

    public function __construct(){}

    // https://stackoverflow.com/questions/31539727/laravel-password-validation-rule

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->minLength = ClientInfo::isGNA() ? 6 : 8;

        $this->length = (Str::length($value) >= $this->minLength);
        $this->uppercase = (Str::lower($value) !== $value);
        $this->lowercase = (Str::upper($value) !== $value);
        $this->numeric = ((bool) preg_match('/[0-9]/', $value));

        if(ClientInfo::isJCF()) {
            $this->specialChar = true;
        } else {
            $this->specialChar = ((bool) preg_match('/[^A-Za-z0-9]/', $value));
        }

        return ($this->length && $this->lowercase && $this->uppercase && $this->numeric && $this->specialChar);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (!$this->uppercase) {
            return 'The :attribute must contain at least one uppercase character.';
        } else if (!$this->lowercase) {
            return 'The :attribute must contain at least one lowercase character.';
        } else if (!$this->numeric) {
            return 'The :attribute must contain at least one number.';
        } else if (!$this->specialChar) {
            return 'The :attribute must contain at least one special character.';
        } else if (!$this->length) {
            return 'The :attribute must be at least ' . $this->minLength . ' characters.';
        }
        return 'The validation error message.';
    }
}
