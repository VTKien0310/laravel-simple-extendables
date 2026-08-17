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
    'any_of' => ValidationErrorCodeEnum::IN->value,
    'array' => ValidationErrorCodeEnum::ARRAY->value,
    'array_keys' => ValidationErrorCodeEnum::ARRAY_KEYS->value,
    'ascii' => ValidationErrorCodeEnum::ASCII->value,
    'base64' => ValidationErrorCodeEnum::BASE64->value,
    'before' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'before_or_equal' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'between' => [
        'array' => ValidationErrorCodeEnum::BETWEEN->value,
        'file' => ValidationErrorCodeEnum::BETWEEN->value,
        'numeric' => ValidationErrorCodeEnum::BETWEEN->value,
        'string' => ValidationErrorCodeEnum::BETWEEN->value,
    ],
    'boolean' => ValidationErrorCodeEnum::BOOLEAN->value,
    'can' => ValidationErrorCodeEnum::CAN->value,
    'confirmed' => ValidationErrorCodeEnum::CONFIRMED->value,
    'contains' => ValidationErrorCodeEnum::CONTAINS->value,
    'current_password' => ValidationErrorCodeEnum::PASSWORD->value,
    'date' => ValidationErrorCodeEnum::DATE->value,
    'date_equals' => ValidationErrorCodeEnum::DATE_EQUALS->value,
    'date_format' => ValidationErrorCodeEnum::DATE_FORMAT->value,
    'decimal' => ValidationErrorCodeEnum::DECIMAL->value,
    'declined' => ValidationErrorCodeEnum::DECLINED->value,
    'declined_if' => ValidationErrorCodeEnum::DECLINED->value,
    'different' => ValidationErrorCodeEnum::DIFFERENT->value,
    'digits' => ValidationErrorCodeEnum::DIGITS->value,
    'digits_between' => ValidationErrorCodeEnum::DIGITS_BETWEEN->value,
    'dimensions' => ValidationErrorCodeEnum::DIMENSIONS->value,
    'distinct' => ValidationErrorCodeEnum::DISTINCT->value,
    'doesnt_contain' => ValidationErrorCodeEnum::DOES_NOT_CONTAIN->value,
    'doesnt_end_with' => ValidationErrorCodeEnum::DOES_NOT_END_WITH->value,
    'doesnt_start_with' => ValidationErrorCodeEnum::DOES_NOT_START_WITH->value,
    'email' => ValidationErrorCodeEnum::EMAIL->value,
    'encoding' => ValidationErrorCodeEnum::ENCODING->value,
    'ends_with' => ValidationErrorCodeEnum::ENDS_WITH->value,
    'enum' => ValidationErrorCodeEnum::IN->value,
    'exists' => ValidationErrorCodeEnum::EXISTS->value,
    'extensions' => ValidationErrorCodeEnum::EXTENSIONS->value,
    'file' => ValidationErrorCodeEnum::FILE->value,
    'filled' => ValidationErrorCodeEnum::FILLED->value,
    'gt' => [
        'array' => ValidationErrorCodeEnum::GT->value,
        'file' => ValidationErrorCodeEnum::GT->value,
        'numeric' => ValidationErrorCodeEnum::GT->value,
        'string' => ValidationErrorCodeEnum::GT->value,
    ],
    'gte' => [
        'array' => ValidationErrorCodeEnum::GTE->value,
        'file' => ValidationErrorCodeEnum::GTE->value,
        'numeric' => ValidationErrorCodeEnum::GTE->value,
        'string' => ValidationErrorCodeEnum::GTE->value,
    ],
    'hex_color' => ValidationErrorCodeEnum::HEX_COLOR->value,
    'image' => ValidationErrorCodeEnum::IMAGE->value,
    'in' => ValidationErrorCodeEnum::IN->value,
    'in_array' => ValidationErrorCodeEnum::IN_ARRAY->value,
    'in_array_keys' => ValidationErrorCodeEnum::IN_ARRAY_KEYS->value,
    'integer' => ValidationErrorCodeEnum::INTEGER->value,
    'ip' => ValidationErrorCodeEnum::IP->value,
    'ipv4' => ValidationErrorCodeEnum::IPV4->value,
    'ipv6' => ValidationErrorCodeEnum::IPV6->value,
    'json' => ValidationErrorCodeEnum::JSON->value,
    'list' => ValidationErrorCodeEnum::LIST->value,
    'lowercase' => ValidationErrorCodeEnum::LOWERCASE->value,
    'lt' => [
        'array' => ValidationErrorCodeEnum::LT->value,
        'file' => ValidationErrorCodeEnum::LT->value,
        'numeric' => ValidationErrorCodeEnum::LT->value,
        'string' => ValidationErrorCodeEnum::LT->value,
    ],
    'lte' => [
        'array' => ValidationErrorCodeEnum::LTE->value,
        'file' => ValidationErrorCodeEnum::LTE->value,
        'numeric' => ValidationErrorCodeEnum::LTE->value,
        'string' => ValidationErrorCodeEnum::LTE->value,
    ],
    'mac_address' => ValidationErrorCodeEnum::MAC_ADDRESS->value,
    'max' => [
        'array' => ValidationErrorCodeEnum::MAX->value,
        'file' => ValidationErrorCodeEnum::MAX->value,
        'numeric' => ValidationErrorCodeEnum::MAX->value,
        'string' => ValidationErrorCodeEnum::MAX->value,
    ],
    'max_digits' => ValidationErrorCodeEnum::DIGIT_MAX->value,
    'mimes' => ValidationErrorCodeEnum::MIMES->value,
    'mimetypes' => ValidationErrorCodeEnum::MIMES->value,
    'min' => [
        'array' => ValidationErrorCodeEnum::MIN->value,
        'file' => ValidationErrorCodeEnum::MIN->value,
        'numeric' => ValidationErrorCodeEnum::MIN->value,
        'string' => ValidationErrorCodeEnum::MIN->value,
    ],
    'min_digits' => ValidationErrorCodeEnum::DIGIT_MIN->value,
    'missing' => ValidationErrorCodeEnum::MISSING->value,
    'missing_if' => ValidationErrorCodeEnum::MISSING->value,
    'missing_unless' => ValidationErrorCodeEnum::MISSING->value,
    'missing_with' => ValidationErrorCodeEnum::MISSING->value,
    'missing_with_all' => ValidationErrorCodeEnum::MISSING->value,
    'multiple_of' => ValidationErrorCodeEnum::MULTIPLE_OF->value,
    'not_in' => ValidationErrorCodeEnum::IN->value,
    'not_regex' => ValidationErrorCodeEnum::IN->value,
    'numeric' => ValidationErrorCodeEnum::NUMERIC->value,
    'password' => ValidationErrorCodeEnum::PASSWORD->value,
    'present' => ValidationErrorCodeEnum::PRESENT->value,
    'present_if' => ValidationErrorCodeEnum::PRESENT->value,
    'present_unless' => ValidationErrorCodeEnum::PRESENT->value,
    'present_with' => ValidationErrorCodeEnum::PRESENT->value,
    'present_with_all' => ValidationErrorCodeEnum::PRESENT->value,
    'prohibited' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_if' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_if_accepted' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_if_declined' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibited_unless' => ValidationErrorCodeEnum::PROHIBITED->value,
    'prohibits' => ValidationErrorCodeEnum::PROHIBITS->value,
    'regex' => ValidationErrorCodeEnum::IN->value,
    'required' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_array_keys' => ValidationErrorCodeEnum::REQUIRED_ARRAY_KEYS->value,
    'required_if' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_if_accepted' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_if_declined' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_unless' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_with' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_with_all' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_without' => ValidationErrorCodeEnum::REQUIRED->value,
    'required_without_all' => ValidationErrorCodeEnum::REQUIRED->value,
    'same' => ValidationErrorCodeEnum::SAME->value,
    'size' => [
        'array' => ValidationErrorCodeEnum::SIZE->value,
        'file' => ValidationErrorCodeEnum::SIZE->value,
        'numeric' => ValidationErrorCodeEnum::SIZE->value,
        'string' => ValidationErrorCodeEnum::SIZE->value,
    ],
    'starts_with' => ValidationErrorCodeEnum::STARTS_WITH->value,
    'string' => ValidationErrorCodeEnum::STRING->value,
    'timezone' => ValidationErrorCodeEnum::TIMEZONE->value,
    'unique' => ValidationErrorCodeEnum::UNIQUE->value,
    'uploaded' => ValidationErrorCodeEnum::UPLOADED->value,
    'uppercase' => ValidationErrorCodeEnum::UPPERCASE->value,
    'url' => ValidationErrorCodeEnum::URL->value,
    'ulid' => ValidationErrorCodeEnum::ULID->value,
    'uuid' => ValidationErrorCodeEnum::UUID->value,

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
