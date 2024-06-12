<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':Attribute muss akzeptiert werden.',
    'active_url'           => ':Attribute ist keine gültige Internet-Adresse.',
    'after'                => ':Attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'       => ':Attribute muss ein Datum nach dem :date oder gleich dem :Date sein.',
    'alpha'                => ':Attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash'           => ':Attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.',
    'alpha_num'            => ':Attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array'                => ':Attribute muss ein Array sein.',
    'before'               => ':Attribute muss ein Datum vor dem :Date sein.',
    'before_or_equal'      => ':Attribute muss ein Datum vor dem :Date oder gleich dem :Date sein.',
    'between'              => [
        'numeric' => ':Attribute muss zwischen :min & :max liegen.',
        'file'    => ':Attribute muss zwischen :min & :max Kilobytes groß sein.',
        'string'  => ':Attribute muss zwischen :min & :max Zeichen lang sein.',
        'array'   => ':Attribute muss zwischen :min & :max Elemente haben.',
    ],
    'boolean'              => ":Attribute muss entweder 'true' oder 'false' sein.",
    'confirmed'            => ':Attribute stimmt nicht mit der Bestätigung überein.',
    'date'                 => ':Attribute muss ein gültiges Datum sein.',
    'date_format'          => ':Attribute entspricht nicht dem gültigen Format für :format.',
    'different'            => ':Attribute und :other müssen sich unterscheiden.',
    'digits'               => ':Attribute muss :digits Stellen haben.',
    'digits_between'       => ':Attribute muss zwischen :min und :max Stellen haben.',
    'dimensions'           => ':Attribute hat ungültige Bildabmessungen.',
    'distinct'             => ':Attribute beinhaltet einen bereits vorhandenen Wert.',
    'email'                => ':Attribute muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Der gewählte Wert für :Attribute ist ungültig.',
    'file'                 => ':Attribute muss eine Datei sein.',
    'filled'               => ':Attribute muss ausgefüllt sein.',
    'gt'                   => [
        'numeric' => ':Attribute muss mindestens :min sein.',
        'file'    => ':Attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => ':Attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':Attribute muss mindestens :min Elemente haben.',
    ],
    'gte'                  => [
        'numeric' => ':Attribute muss größer oder gleich :min sein.',
        'file'    => ':Attribute muss größer oder gleich :min Kilobytes sein.',
        'string'  => ':Attribute muss größer oder gleich :min Zeichen lang sein.',
        'array'   => ':Attribute muss größer oder gleich :min Elemente haben.',
    ],
    'image'                => ':Attribute muss ein Bild sein.',
    'in'                   => 'Der gewählte Wert für :Attribute ist ungültig.',
    'in_array'             => 'Der gewählte Wert für :Attribute kommt nicht in :other vor.',
    'integer'              => ':Attribute muss eine ganze Zahl sein.',
    'ip'                   => ':Attribute muss eine gültige IP-Adresse sein.',
    'ipv4'                 => ':Attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'                 => ':Attribute muss eine gültige IPv6-Adresse sein.',
    'json'                 => ':Attribute muss ein gültiger JSON-String sein.',
    'lt'                   => [
        'numeric' => ':Attribute muss kleiner :min sein.',
        'file'    => ':Attribute muss kleiner :min Kilobytes groß sein.',
        'string'  => ':Attribute muss kleiner :min Zeichen lang sein.',
        'array'   => ':Attribute muss kleiner :min Elemente haben.',
    ],
    'lte'                  => [
        'numeric' => ':Attribute muss kleiner oder gleich :min sein.',
        'file'    => ':Attribute muss kleiner oder gleich :min Kilobytes sein.',
        'string'  => ':Attribute muss kleiner oder gleich :min Zeichen lang sein.',
        'array'   => ':Attribute muss kleiner oder gleich :min Elemente haben.',
    ],
    'max'                  => [
        'numeric' => ':Attribute darf maximal :max sein.',
        'file'    => ':Attribute darf maximal :max Kilobytes groß sein.',
        'string'  => ':Attribute darf maximal :max Zeichen haben.',
        'array'   => ':Attribute darf nicht mehr als :max Elemente haben.',
    ],
    'mimes'                => ':Attribute muss den Dateityp :values haben.',
    'mimetypes'            => ':Attribute muss den Dateityp :values haben.',
    'min'                  => [
        'numeric' => ':Attribute muss mindestens :min sein.',
        'file'    => ':Attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => ':Attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':Attribute muss mindestens :min Elemente haben.',
    ],
    'not_in'               => 'Der gewählte Wert für :attribute ist ungültig.',
    'not_regex'            => ':Attribute hat ein ungültiges Format.',
    'numeric'              => ':Attribute muss eine Zahl sein.',
    'present'              => ':Attribute muss vorhanden sein.',
    'regex'                => ':Attribute Format ist ungültig.',
    'required'             => ':Attribute muss ausgefüllt sein.',
    'required_if'          => ':Attribute muss ausgefüllt sein, wenn :Other :value ist.',
    'required_unless'      => ':Attribute muss ausgefüllt sein, wenn :Other nicht :values ist.',
    'required_with'        => ':Attribute muss angegeben werden, wenn :Values ausgefüllt wurde.',
    'required_with_all'    => ':Attribute muss angegeben werden, wenn :Values ausgefüllt wurde.',
    'required_without'     => ':Attribute muss angegeben werden, wenn :Values nicht ausgefüllt wurde.',
    'required_without_all' => ':Attribute muss angegeben werden, wenn keines der Felder :Values ausgefüllt wurde.',
    'same'                 => ':Attribute und :Other müssen übereinstimmen.',
    'size'                 => [
        'numeric' => ':Attribute muss gleich :size sein.',
        'file'    => ':Attribute muss :size Kilobyte groß sein.',
        'string'  => ':Attribute muss :size Zeichen lang sein.',
        'array'   => ':Attribute muss genau :size Elemente haben.',
    ],
    'string'               => ':Attribute muss ausgefüllt sein.',
    'timezone'             => ':Attribute muss eine gültige Zeitzone sein.',
    'unique'               => ':Attribute ist schon vergeben.',
    'uploaded'             => ':Attribute konnte nicht hochgeladen werden.',
    'url'                  => ':Attribute muss eine URL sein.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'Name',
        'username'              => 'Benutzername',
        'email'                 => 'E-Mail-Adresse',
        'first_name'            => 'Vorname',
        'last_name'             => 'Nachname',
        'password'              => 'Passwort',
        'password_confirmation' => 'Passwort-Bestätigung',
        'city'                  => 'Stadt',
        'country'               => 'Land',
        'address'               => 'Adresse',
        'phone'                 => 'Telefonnummer',
        'mobile'                => 'Handynummer',
        'age'                   => 'Alter',
        'sex'                   => 'Geschlecht',
        'gender'                => 'Geschlecht',
        'day'                   => 'Tag',
        'month'                 => 'Monat',
        'year'                  => 'Jahr',
        'hour'                  => 'Stunde',
        'minute'                => 'Minute',
        'second'                => 'Sekunde',
        'title'                 => 'Titel',
        'content'               => 'Inhalt',
        'description'           => 'Beschreibung',
        'excerpt'               => 'Auszug',
        'date'                  => 'Datum',
        'time'                  => 'Uhrzeit',
        'available'             => 'verfügbar',
        'size'                  => 'Größe',
        'files.*'               => "Datei",

        'charity_percentage' => "Charityanteil",
        'starting_bid' => "Startgebot",
        'new_image' => "Bild",
        'auction' => '"Zur Versteigerung"',
        'true' => "aktiviert",
        'false' => "deaktiviert",
        'social' => "Link zu Social Media",
        'additional_info' => "Zusätzliche Informationen",
        'duration' => "Dauer",
        'language' => "Sprache",
        'languages' => "Sprachen",
        'name_en' => "Name (Englisch)",
        'approved' => "Bestätigt",
        'requirements' => "Anforderungen",
    ],
];
