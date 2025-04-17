<?php

namespace App\Validator;

use Valitron\Validator;

class EventPayloadValidator extends Validator
{
    public function __construct($data = [], $fields = [], $lang = null, $langDir = null) {
        parent::__construct($data, $fields, $lang, $langDir);

        if ($data['action'] == 'new') {
            $this->rule('required', [
                'venue_id',
                'title',
                'description',
                'start_time',
                'end_time',
                'total_seats',
                'price'
            ]);
        }
        $this->rule('numeric', ['venue_id', 'total_seats', 'price']);
        $this->rule('date', ['start_time', 'end_time']);
    }
}
