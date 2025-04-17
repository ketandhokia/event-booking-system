<?php

namespace App\Validator;

use Valitron\Validator;

class RegisterPayloadValidator extends Validator
{
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null) {
        parent::__construct($data, $fields, $lang, $langDir);

        $this->rule('required', ['email', 'password']);
        $this->rule('email', 'email');
    }
}
