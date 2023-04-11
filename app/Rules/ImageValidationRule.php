<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ImageValidationRule implements Rule, DataAwareRule, ValidatorAwareRule
{
    private array $messages;
    private array $data;
    private \Illuminate\Validation\Validator $validator;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    private function fail($messages): bool
    {
        $messages = collect(Arr::wrap($messages))->map(function ($message) {
            return $this->validator->getTranslator()->get($message);
        })->all();

        $this->messages = array_merge($this->messages, $messages);

        return false;
    }

    public function passes($attribute, $value): bool
    {
        $this->messages = [];

        $validator = Validator::make($this->data, [
            $attribute => 'image|mimes:jpeg,png,jpg,webp,gif|max:1024',
        ], $this->validator->customMessages, $this->validator->customAttributes);

        if ($validator->fails()) {
            return $this->fail($validator->messages()->all());
        }

        if (!empty($this->messages)) {
            return false;
        }

        return true;
    }

    public function message(): array
    {
        return $this->messages;
    }
}
