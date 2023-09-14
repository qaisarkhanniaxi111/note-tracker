<?php

return [
    'clinician' => [
        'default_password' => env('DEFAULT_CLINICIAN_PASSWORD')
    ],

    'customer_support_mail' => env('CUSTOMER_SUPPORT_MAIL'),
    'customer_support_contact_page' => env('CUSTOMER_SUPPORT_CONTACT_PAGE'),

    'copy-right-owner' => [
        'name' => env('COPY_RIGHT_OWNER_NAME'),
        'website' => env('COPY_RIGHT_OWNER_WEBSITE')
    ],
];
