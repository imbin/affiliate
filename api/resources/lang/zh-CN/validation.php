<?php

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

    'accepted' => ':attribute 必须接受',
    'active_url' => ':attribute 必须是一个合法的 URL',
    'after' => ':attribute 必须是 :date 之后的一个日期',
    'after_or_equal' => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha' => ':attribute 只能包含字母',
    'alpha_dash' => ':attribute 只能包含字母、数字、中划线或下划线',
    'alpha_num' => ':attribute 只能包含字母和数字',
    'array' => ':attribute 必须是一个数组',
    'before' => ':attribute 必须是 :date 之前的一个日期',
    'before_or_equal' => ':attribute 必须是 :date 之前或相同的一个日期',
    'between' => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file' => ':attribute 必须在 :min 到 :max KB 之间',
        'string' => ':attribute 必须在 :min 到 :max 个字符之间',
        'array' => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean' => ':attribute 必须是 true 或false, 1 或 0',
    'confirmed' => ':attribute 二次确认不匹配',
    'date' => ':attribute 必须是一个有效的日期',
    'date_equals' => ':attribute 必须等于指定日期 :date',
    'date_format' => ':attribute 与给定的格式 :format 不符合',
    'different' => ':attribute 必须不同于 :other',
    'digits' => ':attribute 必须是 :digits 位',
    'digits_between' => ':attribute 必须在 :min 和 :max 位之间',
    'dimensions' => ':attribute 无效的图片尺寸',
    'distinct' => ':attribute 具有重复值',
    'email' => ':attribute 必须是一个有效的电子邮件地址',
    'ends_with' => ':attribute 必须以 :values 结尾',
    'exists' => '选中的 :attribute 已存在',
    'file' => ':attribute 必须是一个文件',
    'filled' => ':attribute 是必填的',
    'gt' => [
        'numeric' => ':attribute 必须大于 :value',
        'file' => ':attribute 必须大于 :value KB',
        'string' => ':attribute 必须大于 :value 个字符',
        'array' => ':attribute 必须大于 :value 个',
    ],
    'gte' => [
        'numeric' => ':attribute 必须大于或等于 :value',
        'file' => ':attribute 必须大于或等于 :value kb',
        'string' => ':attribute 必须大于或等于 :value 位字符',
        'array' => ':attribute 必须大于或等于 :value 个',
    ],
    'image' => ':attribute 必须是 jpeg, png, bmp 或者 gif 格式的图片',
    'in' => '选定的 :attribute 是无效的',
    'in_array' => ':attribute 字段不存在于 :other',
    'integer' => ':attribute 必须是个整数',
    'ip' => ':attribute 必须是一个合法的 IP 地址',
    'ipv4' => ':attribute 必须是一个合法的 IPv4 地址',
    'ipv6' => ':attribute 必须是一个合法的 IPv6 地址',
    'json' => ':attribute 必须是一个合法的 JSON 字符串',
    'lt' => [
        'numeric' => ':attribute 必须小于 :value',
        'file' => ':attribute 必须小于 :value KB',
        'string' => ':attribute 必须小于 :value characters',
        'array' => ':attribute 必须小于 :value 个',
    ],
    'lte' => [
        'numeric' => ':attribute 必须小于或等于 :value',
        'file' => ':attribute 必须小于或等于 :value KB',
        'string' => ':attribute 必须小于或等于 :value 位字符',
        'array' => ':attribute 必须小于或等于 :value 个',
    ],
    'max' => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file' => ':attribute 的最大为 :max KB',
        'string' => ':attribute 不能大于 :max 位字符',
        'array' => ':attribute 不能大于 :max 个',
    ],
    'mimes' => ':attribute 必须是以下文件类型: :values',
    'mimetypes' => ':attribute 必须是以下文件类型: :values',
    'min' => [
        'numeric' => ':attribute 必须最小 :min',
        'file' => ':attribute 必须最小 :min KB',
        'string' => ':attribute 必须最小 :min 个字符',
        'array' => ':attribute 必须最小 :min 个',
    ],
    'not_in' => '选中的 :attribute 是无效的',
    'not_regex' => ':attribute 格式是无效的',
    'numeric' => ':attribute 必须是数字格式',
    'password' => '密码不正确',
    'present' => ':attribute 必须存在',
    'regex' => ':attribute 格式不正确',
    'required' => ':attribute 是必填的',
    'required_if' => ':attribute 是必填的，当 :other 是 :value 的时候',
    'required_unless' => ':attribute 是必填的,除非 :other 在 :values 列表中',
    'required_with' => ':attribute 是必填的当 :values 存在时',
    'required_with_all' => ':attribute 是必填的当 :values 都存在时',
    'required_without' => ':attribute 是必填的当 :values 不存在时',
    'required_without_all' => ':attribute 是必填的当 :values 都不存在时',
    'same' => ':attribute 必须与 :other 相同',
    'size' => [
        'numeric' => ':attribute 必须 :size',
        'file' => ':attribute 必须 :size KB',
        'string' => ':attribute 必须 :size 个字符',
        'array' => ':attribute 必须包含 :size 个',
    ],
    'starts_with' => ':attribute 必须以下列开头: :values',
    'string' => ':attribute 必须是个字符串',
    'timezone' => ':attribute 必须是个有效的时区',
    'unique' => ':attribute 必须是不能重复的',
    'uploaded' => ':attribute 上传失败',
    'url' => ':attribute 必须是有效的 URL 格式',
    'uuid' => ':attribute 必须是个有效的 UUID',

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
        'mobile' => [
            'mobile' => ':attribute 手机号格式不正确',
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
