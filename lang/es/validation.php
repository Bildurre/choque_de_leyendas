<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Validation Language Lines
  |--------------------------------------------------------------------------
  */

  'accepted' => 'El campo :attribute debe ser aceptado.',
  'active_url' => 'El campo :attribute no es una URL válida.',
  'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
  'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
  'alpha' => 'El campo :attribute solo puede contener letras.',
  'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
  'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
  'array' => 'El campo :attribute debe ser un array.',
  'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
  'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
  'between' => [
    'numeric' => 'El campo :attribute debe estar entre :min y :max.',
    'file' => 'El campo :attribute debe pesar entre :min y :max kilobytes.',
    'string' => 'El campo :attribute debe contener entre :min y :max caracteres.',
    'array' => 'El campo :attribute debe tener entre :min y :max elementos.',
  ],
  'boolean' => 'El campo :attribute debe ser verdadero o falso.',
  'confirmed' => 'La confirmación de :attribute no coincide.',
  'date' => 'El campo :attribute no es una fecha válida.',
  'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
  'date_format' => 'El campo :attribute no corresponde al formato :format.',
  'different' => 'Los campos :attribute y :other deben ser diferentes.',
  'digits' => 'El campo :attribute debe tener :digits dígitos.',
  'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
  'dimensions' => 'Las dimensiones de la imagen :attribute no son válidas.',
  'distinct' => 'El campo :attribute contiene un valor duplicado.',
  'email' => 'El campo :attribute debe ser un correo electrónico válido.',
  'exists' => 'El campo :attribute seleccionado no es válido.',
  'file' => 'El campo :attribute debe ser un archivo.',
  'filled' => 'El campo :attribute debe tener un valor.',
  'gt' => [
    'numeric' => 'El campo :attribute debe ser mayor que :value.',
    'file' => 'El campo :attribute debe pesar más de :value kilobytes.',
    'string' => 'El campo :attribute debe contener más de :value caracteres.',
    'array' => 'El campo :attribute debe tener más de :value elementos.',
  ],
  'gte' => [
    'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
    'file' => 'El campo :attribute debe pesar :value o más kilobytes.',
    'string' => 'El campo :attribute debe contener :value o más caracteres.',
    'array' => 'El campo :attribute debe tener :value elementos o más.',
  ],
  'image' => 'El campo :attribute debe ser una imagen.',
  'in' => 'El campo :attribute seleccionado no es válido.',
  'in_array' => 'El campo :attribute no existe en :other.',
  'integer' => 'El campo :attribute debe ser un número entero.',
  'ip' => 'El campo :attribute debe ser una dirección IP válida.',
  'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
  'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
  'json' => 'El campo :attribute debe ser una cadena JSON válida.',
  'lt' => [
    'numeric' => 'El campo :attribute debe ser menor que :value.',
    'file' => 'El campo :attribute debe pesar menos de :value kilobytes.',
    'string' => 'El campo :attribute debe contener menos de :value caracteres.',
    'array' => 'El campo :attribute debe tener menos de :value elementos.',
  ],
  'lte' => [
    'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
    'file' => 'El campo :attribute debe pesar :value o menos kilobytes.',
    'string' => 'El campo :attribute debe contener :value o menos caracteres.',
    'array' => 'El campo :attribute debe tener :value elementos o menos.',
  ],
  'max' => [
    'numeric' => 'El campo :attribute no debe ser mayor que :max.',
    'file' => 'El campo :attribute no debe pesar más de :max kilobytes.',
    'string' => 'El campo :attribute no debe contener más de :max caracteres.',
    'array' => 'El campo :attribute no debe tener más de :max elementos.',
  ],
  'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
  'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
  'min' => [
    'numeric' => 'El campo :attribute debe ser al menos :min.',
    'file' => 'El campo :attribute debe pesar al menos :min kilobytes.',
    'string' => 'El campo :attribute debe contener al menos :min caracteres.',
    'array' => 'El campo :attribute debe tener al menos :min elementos.',
  ],
  'not_in' => 'El campo :attribute seleccionado no es válido.',
  'not_regex' => 'El formato del campo :attribute no es válido.',
  'numeric' => 'El campo :attribute debe ser un número.',
  'password' => 'La contraseña es incorrecta.',
  'present' => 'El campo :attribute debe estar presente.',
  'regex' => 'El formato del campo :attribute no es válido.',
  'required' => 'El campo :attribute es obligatorio.',
  'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
  'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
  'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
  'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes.',
  'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
  'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
  'same' => 'Los campos :attribute y :other deben coincidir.',
  'size' => [
    'numeric' => 'El campo :attribute debe ser :size.',
    'file' => 'El campo :attribute debe pesar :size kilobytes.',
    'string' => 'El campo :attribute debe contener :size caracteres.',
    'array' => 'El campo :attribute debe contener :size elementos.',
  ],
  'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes: :values.',
  'string' => 'El campo :attribute debe ser una cadena de texto.',
  'timezone' => 'El campo :attribute debe ser una zona horaria válida.',
  'unique' => 'El campo :attribute ya ha sido tomado.',
  'uploaded' => 'El campo :attribute no pudo ser cargado.',
  'url' => 'El formato del campo :attribute no es válido.',
  'uuid' => 'El campo :attribute debe ser un UUID válido.',

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Language Lines
  |--------------------------------------------------------------------------
  */

  'custom' => [
    'cost' => [
      'regex' => 'El coste debe contener solo las letras R, G y B.',
    ],
    'color' => [
      'regex' => 'El color debe ser un código hexadecimal válido (ejemplo: #FF0000).',
    ],
    'total_attributes' => [
      'between' => 'El total de atributos debe estar entre :min y :max.',
    ],
    'deck_cards' => [
      'min' => 'El mazo debe tener al menos :min cartas.',
      'max' => 'El mazo debe tener como máximo :max cartas.',
    ],
    'deck_heroes' => [
      'min' => 'El mazo debe tener al menos :min héroe(s).',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Attributes
  |--------------------------------------------------------------------------
  */

  'attributes' => [
    'name' => 'nombre',
    'email' => 'correo electrónico',
    'password' => 'contraseña',
    'cost' => 'coste',
    'color' => 'color',
    'icon' => 'icono',
    'image' => 'imagen',
    'lore_text' => 'texto de trasfondo',
    'effect' => 'efecto',
    'restriction' => 'restricción',
    'passive_name' => 'nombre de la pasiva',
    'passive_description' => 'descripción de la pasiva',
    'agility' => 'agilidad',
    'mental' => 'mental',
    'will' => 'voluntad',
    'strength' => 'fuerza',
    'armor' => 'armadura',
    'total_attributes' => 'total de atributos',
  ],
];