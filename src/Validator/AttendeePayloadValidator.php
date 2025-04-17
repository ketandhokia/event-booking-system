<?php

namespace App\Validator;

use Valitron\Validator;

class AttendeePayloadValidator extends Validator
{
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null) {
        parent::__construct($data, $fields, $lang, $langDir);

        $this->rule('required', ['name', 'email', 'phone']);
        $this->rule('email', 'email');
    }
}
