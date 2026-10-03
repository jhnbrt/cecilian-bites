<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Currency Symbol
    |--------------------------------------------------------------------------
    |
    | The symbol shown in front of every price on the cashier dashboard.
    |
    */

    'currency' => env('RESTAURANT_CURRENCY', '$'),

    /*
    |--------------------------------------------------------------------------
    | Admin Registration Code
    |--------------------------------------------------------------------------
    |
    | Anyone can register as a cashier, but creating an admin account from the
    | public register page requires this secret code. Leave it empty to turn
    | public admin registration off completely (admins can then only be created
    | by another admin from the Staff page).
    |
    */

    'admin_registration_code' => env('ADMIN_REGISTRATION_CODE'),

];
