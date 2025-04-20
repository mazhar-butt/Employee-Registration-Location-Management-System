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

    'accepted' => 'فیلڈ :attribute کو قبول کیا جانا چاہیے۔',
    'accepted_if' => 'فیلڈ :attribute کو اس وقت قبول کیا جانا چاہیے جب :other :value ہو۔',
    'active_url' => 'فیلڈ :attribute ایک درست URL ہونا چاہیے۔',
    'after' => 'فیلڈ :attribute کی تاریخ :date کے بعد ہونی چاہیے۔',
    'after_or_equal' => 'فیلڈ :attribute کی تاریخ :date کے بعد یا برابر ہونی چاہیے۔',
    'alpha' => 'فیلڈ :attribute میں صرف حروف شامل ہونے چاہئیں۔',
    'alpha_dash' => 'فیلڈ :attribute میں صرف حروف، اعداد، ڈیش اور انڈر اسکور شامل ہونے چاہئیں۔',
    'alpha_num' => 'فیلڈ :attribute میں صرف حروف اور اعداد شامل ہونے چاہئیں۔',
    'array' => 'فیلڈ :attribute ایک صف ہونا چاہیے۔',
    'ascii' => 'فیلڈ :attribute میں صرف سنگل بائٹ الفانومیریک حروف اور علامات شامل ہونے چاہئیں۔',
    'before' => 'فیلڈ :attribute کی تاریخ :date سے پہلے ہونی چاہیے۔',
    'before_or_equal' => 'فیلڈ :attribute کی تاریخ :date سے پہلے یا برابر ہونی چاہیے۔',
    'between' => [
        'array' => 'فیلڈ :attribute میں :min اور :max آئٹمز کے درمیان ہونا چاہیے۔',
        'file' => 'فیلڈ :attribute کا حجم :min اور :max کلو بائٹس کے درمیان ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :min اور :max کے درمیان ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :min اور :max حروف کے درمیان ہونا چاہیے۔',
    ],
    'boolean' => 'فیلڈ :attribute صحیح یا غلط ہونا چاہیے۔',
    'can' => 'فیلڈ :attribute میں غیر مجاز قدر شامل ہے۔',
    'confirmed' => 'فیلڈ :attribute کی تصدیق میل نہیں کھاتی۔',
    'current_password' => 'پاس ورڈ غلط ہے۔',
    'date' => 'فیلڈ :attribute ایک درست تاریخ ہونی چاہیے۔',
    'date_equals' => 'فیلڈ :attribute کی تاریخ :date کے برابر ہونی چاہیے۔',
    'date_format' => 'فیلڈ :attribute کو شکل :format سے ملنا چاہیے۔',
    'decimal' => 'فیلڈ :attribute میں :decimal اعشاریہ مقامات ہونے چاہئیں۔',
    'declined' => 'فیلڈ :attribute کو مسترد کیا جانا چاہیے۔',
    'declined_if' => 'فیلڈ :attribute کو اس وقت مسترد کیا جانا چاہیے جب :other :value ہو۔',
    'different' => 'فیلڈ :attribute اور :other مختلف ہونے چاہئیں۔',
    'digits' => 'فیلڈ :attribute میں :digits عدد ہونے چاہئیں۔',
    'digits_between' => 'فیلڈ :attribute میں :min اور :max عدد کے درمیان ہونا چاہیے۔',
    'dimensions' => 'فیلڈ :attribute کی تصویر کے ابعاد غلط ہیں۔',
    'distinct' => 'فیلڈ :attribute میں دوہری قدر موجود ہے۔',
    'doesnt_end_with' => 'فیلڈ :attribute ان میں سے کسی ایک سے ختم نہیں ہونا چاہیے: :values۔',
    'doesnt_start_with' => 'فیلڈ :attribute ان میں سے کسی ایک سے شروع نہیں ہونا چاہیے: :values۔',
    'email' => 'فیلڈ :attribute ایک درست ای میل ایڈریس ہونا چاہیے۔',
    'ends_with' => 'فیلڈ :attribute ان میں سے کسی ایک سے ختم ہونا چاہیے: :values۔',
    'enum' => 'منتخب کردہ :attribute غلط ہے۔',
    'exists' => 'منتخب کردہ :attribute غلط ہے۔',
    'extensions' => 'فیلڈ :attribute میں مندرجہ ذیل توسیعات ہونی چاہئیں: :values۔',
    'file' => 'فیلڈ :attribute ایک فائل ہونی چاہیے۔',
    'filled' => 'فیلڈ :attribute میں ایک قدر ہونی چاہیے۔',
    'gt' => [
        'array' => 'فیلڈ :attribute میں :value سے زیادہ آئٹمز ہونے چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم :value کلو بائٹس سے زیادہ ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :value سے زیادہ ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :value سے زیادہ حروف ہونے چاہئیں۔',
    ],
    'gte' => [
        'array' => 'فیلڈ :attribute میں :value یا اس سے زیادہ آئٹمز ہونے چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم :value کلو بائٹس سے زیادہ یا اس کے برابر ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :value سے زیادہ یا اس کے برابر ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :value سے زیادہ یا اس کے برابر حروف ہونے چاہئیں۔',
    ],
    'hex_color' => 'فیلڈ :attribute ایک درست ہیکساڈیسمل رنگ ہونا چاہیے۔',
    'image' => 'فیلڈ :attribute ایک تصویر ہونی چاہیے۔',
    'in' => 'منتخب کردہ :attribute غلط ہے۔',
    'in_array' => 'فیلڈ :attribute کو :other میں موجود ہونا چاہیے۔',
    'integer' => 'فیلڈ :attribute ایک عدد ہونا چاہیے۔',
    'ip' => 'فیلڈ :attribute ایک درست IP ایڈریس ہونا چاہیے۔',
    'ipv4' => 'فیلڈ :attribute ایک درست IPv4 ایڈریس ہونا چاہیے۔',
    'ipv6' => 'فیلڈ :attribute ایک درست IPv6 ایڈریس ہونا چاہیے۔',
    'json' => 'فیلڈ :attribute ایک درست JSON سٹرنگ ہونی چاہیے۔',
    'lowercase' => 'فیلڈ :attribute میں چھوٹے حروف ہونے چاہئیں۔',
    'lt' => [
        'array' => 'فیلڈ :attribute میں :value سے کم آئٹمز ہونے چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم :value کلو بائٹس سے کم ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :value سے کم ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :value سے کم حروف ہونے چاہئیں۔',
    ],
    'lte' => [
        'array' => 'فیلڈ :attribute میں :value سے زیادہ آئٹمز نہیں ہونی چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم :value کلو بائٹس سے کم یا اس کے برابر ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :value سے کم یا اس کے برابر ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :value سے کم یا اس کے برابر حروف ہونے چاہئیں۔',
    ],
    'mac_address' => 'فیلڈ :attribute ایک درست MAC ایڈریس ہونا چاہیے۔',
    'max' => [
        'array' => 'فیلڈ :attribute میں زیادہ سے زیادہ :max آئٹمز ہونی چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم زیادہ سے زیادہ :max کلو بائٹس ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute زیادہ سے زیادہ :max ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں زیادہ سے زیادہ :max حروف ہونے چاہئیں۔',
    ],
    'max_digits' => 'فیلڈ :attribute میں زیادہ سے زیادہ :max عدد ہونے چاہئیں۔',
    'mimes' => 'فیلڈ :attribute ایک فائل ہونی چاہیے جس کی قسم: :values ہو۔',
    'mimetypes' => 'فیلڈ :attribute ایک فائل ہونی چاہیے جس کی قسم: :values ہو۔',
    'min' => [
        'array' => 'فیلڈ :attribute میں کم از کم :min آئٹمز ہونی چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم کم از کم :min کلو بائٹس ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute کم از کم :min ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں کم از کم :min حروف ہونے چاہئیں۔',
    ],
    'min_digits' => 'فیلڈ :attribute میں کم از کم :min عدد ہونے چاہئیں۔',
    'missing' => 'فیلڈ :attribute غائب ہونا چاہیے۔',
    'missing_if' => 'فیلڈ :attribute کو اس وقت غائب ہونا چاہیے جب :other :value ہو۔',
    'missing_unless' => 'فیلڈ :attribute کو غائب ہونا چاہیے جب تک کہ :other :values میں نہ ہو۔',
    'missing_with' => 'فیلڈ :attribute کو اس وقت غائب ہونا چاہیے جب :values موجود ہو۔',
    'missing_with_all' => 'فیلڈ :attribute کو اس وقت غائب ہونا چاہیے جب :values موجود ہوں۔',
    'multiple_of' => 'فیلڈ :attribute کو :value کا مضرب ہونا چاہیے۔',
    'not_in' => 'منتخب کردہ :attribute غلط ہے۔',
    'not_regex' => 'فیلڈ :attribute کا فارمٹ غلط ہے۔',
    'numeric' => 'فیلڈ :attribute ایک عدد ہونا چاہیے۔',
    'password' => [
        'letters' => 'فیلڈ :attribute میں کم از کم ایک حرف ہونا چاہیے۔',
        'mixed' => 'فیلڈ :attribute میں کم از کم ایک بڑا اور ایک چھوٹا حرف ہونا چاہیے۔',
        'numbers' => 'فیلڈ :attribute میں کم از کم ایک عدد ہونا چاہیے۔',
        'symbols' => 'فیلڈ :attribute میں کم از کم ایک علامت ہونا چاہیے۔',
        'uncompromised' => 'فیلڈ :attribute کا دیا گیا مواد ایک ڈیٹا لیک میں ظاہر ہوا ہے۔ براہ کرم ایک مختلف :attribute منتخب کریں۔',
    ],
    'present' => 'فیلڈ :attribute موجود ہونا چاہیے۔',
    'present_if' => 'فیلڈ :attribute کو موجود ہونا چاہیے جب :other :value ہو۔',
    'present_unless' => 'فیلڈ :attribute کو موجود ہونا چاہیے جب تک کہ :other :value نہ ہو۔',
    'present_with' => 'فیلڈ :attribute کو موجود ہونا چاہیے جب :values موجود ہو۔',
    'present_with_all' => 'فیلڈ :attribute کو موجود ہونا چاہیے جب :values موجود ہوں۔',
    'prohibited' => 'فیلڈ :attribute کی ممانعت ہے۔',
    'prohibited_if' => 'فیلڈ :attribute کی ممانعت ہے جب :other :value ہو۔',
    'prohibited_unless' => 'فیلڈ :attribute کی ممانعت ہے جب تک کہ :other :values میں نہ ہو۔',
    'prohibits' => 'فیلڈ :attribute :other کو موجود ہونے سے منع کرتا ہے۔',
    'regex' => 'فیلڈ :attribute کا فارمٹ غلط ہے۔',
    'required' => 'فیلڈ :attribute کی ضرورت ہے۔',
    'required_array_keys' => 'فیلڈ :attribute میں درج ذیل کے لئے اندراجات ہونے چاہئیں: :values۔',
    'required_if' => 'فیلڈ :attribute کی ضرورت ہے جب :other :value ہو۔',
    'required_if_accepted' => 'فیلڈ :attribute کی ضرورت ہے جب :other قبول کیا جائے۔',
    'required_unless' => 'فیلڈ :attribute کی ضرورت ہے جب تک کہ :other :values میں نہ ہو۔',
    'required_with' => 'فیلڈ :attribute کی ضرورت ہے جب :values موجود ہو۔',
    'required_with_all' => 'فیلڈ :attribute کی ضرورت ہے جب :values موجود ہوں۔',
    'required_without' => 'فیلڈ :attribute کی ضرورت ہے جب :values موجود نہ ہو۔',
    'required_without_all' => 'فیلڈ :attribute کی ضرورت ہے جب :values میں سے کوئی بھی موجود نہ ہو۔',
    'same' => 'فیلڈ :attribute کو :other کے برابر ہونا چاہیے۔',
    'size' => [
        'array' => 'فیلڈ :attribute میں :size آئٹمز ہونے چاہئیں۔',
        'file' => 'فیلڈ :attribute کا حجم :size کلو بائٹس ہونا چاہیے۔',
        'numeric' => 'فیلڈ :attribute :size ہونا چاہیے۔',
        'string' => 'فیلڈ :attribute میں :size حروف ہونے چاہئیں۔',
    ],
    'starts_with' => 'فیلڈ :attribute ان میں سے کسی ایک سے شروع ہونا چاہیے: :values۔',
    'string' => 'فیلڈ :attribute ایک سٹرنگ ہونی چاہیے۔',
    'timezone' => 'فیلڈ :attribute ایک درست ٹائم زون ہونا چاہیے۔',
    'unique' => 'فیلڈ :attribute پہلے ہی لیا جا چکا ہے۔',
    'uploaded' => 'فیلڈ :attribute اپ لوڈ کرنے میں ناکام رہا۔',
    'uppercase' => 'فیلڈ :attribute میں بڑے حروف ہونے چاہئیں۔',
    'url' => 'فیلڈ :attribute ایک درست URL ہونا چاہیے۔',
    'ulid' => 'فیلڈ :attribute ایک درست ULID ہونا چاہیے۔',
    'uuid' => 'فیلڈ :attribute ایک درست UUID ہونا چاہیے۔',

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

    'attributes' => [
        'first_name' => 'پہلا نام',
        'last_name' => 'آخری نام',
        'email' => 'ای میل',
        'username' => 'صارف نام',
        'password' => 'پاس ورڈ',
    ],
    'required' => 'یہ فیلڈ ضروری ہے',
    'string' => 'یہ ایک سٹرنگ ہونی چاہیے',
    'max' => [
        'string' => 'یہ سٹرنگ زیادہ سے زیادہ :max حروف کی ہونی چاہیے',
    ],
    'min' => [
        'string' => 'یہ سٹرنگ کم از کم :min حروف کی ہونی چاہیے',
    ],
    'array' => 'یہ ایک آرے ہونا چاہیے',
    'validation_failed' => ':تصدیق ناکام ہوئی'

];
