<?php

use App\Extendables\Core\Http\Enums\ValidationErrorCodeEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ValidationErrorCodeEnum::ACCEPTED->value,
    'accepted_if' => ValidationErrorCodeEnum::ACCEPTED->value,
    'active_url' => ValidationErrorCodeEnum::URL->value,
    'after' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'after_or_equal' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'alpha' => ValidationErrorCodeEnum::ALPHA->value,
    'alpha_dash' => ValidationErrorCodeEnum::ALPHA_DASH->value,
    'alpha_num' => ValidationErrorCodeEnum::ALPHA_NUM->value,
    'array' => ValidationErrorCodeEnum::ARRAY->value,
    'before' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'before_or_equal' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'between' => [
        'numeric' => ValidationErrorCodeEnum::BETWEEN->value,
        'file' => ValidationErrorCodeEnum::BETWEEN->value,
        'string' => ValidationErrorCodeEnum::BETWEEN->value,
        'array' => ValidationErrorCodeEnum::BETWEEN->value,
    ],
    'boolean' => ValidationErrorCodeEnum::BOOLEAN->value,
    'confirmed' => ValidationErrorCodeEnum::CONFIRMED->value,
    'current_password' => ValidationErrorCodeEnum::PASSWORD->value,
    'date' => ValidationErrorCodeEnum::DATE->value,
    'date_equals' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'date_format' => ValidationErrorCodeEnum::DATE_FORMAT->value,
    'declined' => ValidationErrorCodeEnum::DECLINED->value,
    'declined_if' => ValidationErrorCodeEnum::DECLINED->value,
    'different' => ValidationErrorCodeEnum::DIFFERENT->value,
    'digits' => ValidationErrorCodeEnum::DIGITS->value,
    'digits_between' => ValidationErrorCodeEnum::DIGITS_BETWEEN->value,
    'dimensions' => ValidationErrorCodeEnum::DIMENSIONS->value,
    'distinct' => ValidationErrorCodeEnum::DISTINCT->value,
    'email' => ValidationErrorCodeEnum::EMAIL->value,
    'ends_with' => ValidationErrorCodeEnum::ENDS_WITH->value,
    'enum' => ValidationErrorCodeEnum::IN->value,
    'exists' => ValidationErrorCodeEnum::EXISTS->value,
    'file' => ValidationErrorCodeEnum::FILE->value,
    'filled' => ValidationErrorCodeEnum::FILLED->value,
    'gt' => [
        'numeric' => ValidationErrorCodeEnum::GT->value,
        'file' => ValidationErrorCodeEnum::GT->value,
        'string' => ValidationErrorCodeEnum::GT->value,
        'array' => ValidationErrorCodeEnum::GT->value,
    ],
    'gte' => [
        'numeric' => ValidationErrorCodeEnum::GTE->value,
        'file' => ValidationErrorCodeEnum::GTE->value,
        'string' => ValidationErrorCodeEnum::GTE->value,
        'array' => ValidationErrorCodeEnum::GTE->value,
    ],
    'image' => ValidationErrorCodeEnum::IMAGE->value,
    'in' => ValidationErrorCodeEnum::IN->value,
    'in_array' => ValidationErrorCodeEnum::IN_ARRAY->value,
    'integer' => ValidationErrorCodeEnum::INTEGER->value,
    'ip' => ValidationErrorCodeEnum::IP->value,
    'ipv4' => ValidationErrorCodeEnum::IPV4->value,
    'ipv6' => ValidationErrorCodeEnum::IPV6->value,
    'json' => ValidationErrorCodeEnum::JSON->value,
    'lt' => [
        'numeric' => ValidationErrorCodeEnum::LT->value,
        'file' => ValidationErrorCodeEnum::LT->value,
        'string' => ValidationErrorCodeEnum::LT->value,
        'array' => ValidationErrorCodeEnum::LT->value,
    ],
    'lte' => [
        'numeric' => ValidationErrorCodeEnum::LTE->value,
        'file' => ValidationErrorCodeEnum::LTE->value,
        'string' => ValidationErrorCodeEnum::LTE->value,
        'array' => ValidationErrorCodeEnum::LTE->value,
    ],
    'mac_address' => ValidationErrorCodeEnum::MAC_ADDRESS->value,
    'max' => [
        'numeric' => ValidationErrorCodeEnum::MAX->value,
        'file' => ValidationErrorCodeEnum::MAX->value,
        'string' => ValidationErrorCodeEnum::MAX->value,
        'array' => ValidationErrorCodeEnum::MAX->value,
    ],
    'mimes' => ValidationErrorCodeEnum::MIMES->value,
    'mimetypes' => ValidationErrorCodeEnum::MIMES->value,
    'min' => [
        'numeric' => ValidationErrorCodeEnum::MIN->value,
        'file' => ValidationErrorCodeEnum::MIN->value,
        'string' => ValidationErrorCodeEnum::MIN->value,
        'array' => ValidationErrorCodeEnum::MIN->value,
    ],
    'multiple_of' => ValidationErrorCodeEnum::MULTIPLE_OF->value,
    'not_in' => ValidationErrorCodeEnum::IN->value,
    'not_regex' => ValidationErrorCodeEnum::IN->value,
    'numeric' => ValidationErrorCodeEnum::NUMERIC->value,
    'password' => ValidationErrorCodeEnum::PASSWORD->value,
    'present' => ValidationErrorCodeEnum::PRESENT->value,
    'prohibited' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_if' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_unless' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibits' => ValidationErrorCodeEnum::PROHIBITS,
    'regex' => ValidationErrorCodeEnum::IN->value,
    'required' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_array_keys' => ValidationErrorCodeEnum::REQUIRED_ARRAY_KEYS->value,
    'required_if' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_unless' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_with' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_with_all' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_without' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_without_all' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_if_accepted' => ValidationErrorCodeEnum::REQUIRED->value,
    'same' => ValidationErrorCodeEnum::SAME->value,
    'size' => [
        'numeric' => ValidationErrorCodeEnum::SIZE->value,
        'file' => ValidationErrorCodeEnum::SIZE->value,
        'string' => ValidationErrorCodeEnum::SIZE->value,
        'array' => ValidationErrorCodeEnum::SIZE->value,
    ],
    'starts_with' => ValidationErrorCodeEnum::STARTS_WITH->value,
    'string' => ValidationErrorCodeEnum::STRING->value,
    'timezone' => ValidationErrorCodeEnum::TIMEZONE->value,
    'unique' => ValidationErrorCodeEnum::UNIQUE->value,
    'uploaded' => ValidationErrorCodeEnum::UPLOADED->value,
    'url' => ValidationErrorCodeEnum::URL->value,
    'uuid' => ValidationErrorCodeEnum::UUID->value,
    'doesnt_end_with' => ValidationErrorCodeEnum::DOES_NOT_END_WITH->value,
    'doesnt_start_with' => ValidationErrorCodeEnum::DOES_NOT_START_WITH->value,
    'lowercase' => ValidationErrorCodeEnum::LOWERCASE->value,
    'uppercase' => ValidationErrorCodeEnum::UPPERCASE->value,
    'max_digits' => ValidationErrorCodeEnum::DIGIT_MAX->value,
    'min_digits' => ValidationErrorCodeEnum::DIGIT_MIN->value,

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
