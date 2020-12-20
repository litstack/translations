<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route-Prefix
    |--------------------------------------------------------------------------
    |
    | This option controls under which route-prefix the translation manager will
    | be located, e.g. http://your-domain.tld/admin/translations.
    |
    */

    'route_prefix' => 'translations',

    /*
    |--------------------------------------------------------------------------
    | Permission Group
    |--------------------------------------------------------------------------
    |
    | The name of the permission group, that guards the translations crud.
    | Set to `NULL` to allow any litstack user to manage translations.
    |
    */

    'permission-group' => 'translations',
];
