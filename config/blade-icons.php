<?php


return [
    /*
    |--------------------------------------------------------------------------
    | Icons Sets
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir los conjuntos de iconos predeterminados. Proporciona
    | un nombre de clave para tu conjunto de iconos y combina las opciones
    | disponibles a continuación.
    |
    */
    'sets' => [
        'default' => [
            'prefix' => 'custom-prefix',
            'path' => '',           
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Clases Globales Predeterminadas
    |--------------------------------------------------------------------------
    |
    | Puedes definir clases que se aplicarán a todos los iconos por defecto.
    |
    */
    'class' => '',

    /*
    |--------------------------------------------------------------------------
    | Atributos Globales Predeterminados
    |--------------------------------------------------------------------------
    |
    | Puedes definir atributos globales que se aplicarán a todos los iconos por defecto.
    |
    */
    'attributes' => [
        // Ejemplo: 'width' => 50, 'height' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Icono de Respaldo Global
    |--------------------------------------------------------------------------
    |
    | Define un icono de respaldo global si uno no se encuentra en ningún conjunto.
    |
    */
    'fallback' => '',

    /*
    |--------------------------------------------------------------------------
    | Configuración de Componentes
    |--------------------------------------------------------------------------
    |
    | Opciones para configurar componentes Blade relacionados con los iconos.
    |
    */
    'components' => [
        'disabled' => false,  // Asegúrate de que no esté deshabilitado
        'default' => 'icon',  // Nombre del componente predeterminado
    ],
];
