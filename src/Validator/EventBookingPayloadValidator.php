<?php

namespace App\Validator;

use Valitron\Validator;

class EventBookingPayloadValidator extends Validator
{
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null) {
        parent::__construct($data, $fields, $lang, $langDir);

        $this->rule('required', ['event_id', 'attendee_ids']);
        $this->rule('numeric', ['event_id']);
        $this->rule('array', 'attendee_ids');
    }
}
