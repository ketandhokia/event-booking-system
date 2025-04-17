<?php

namespace App\Validator;

use Valitron\Validator;

class VenuePayloadValidator extends Validator
{
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null) {
        parent::__construct($data, $fields, $lang, $langDir);

        $this->rule('required', ['name', 'iso_code', 'capacity']);
        $this->rule('lengthMax', 'iso_code', 2);
        $this->rule('numeric', 'capacity');
    }
}
