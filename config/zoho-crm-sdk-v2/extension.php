<?php

return [
    'client_id' => env('ZOHO_CRM_CLIENT_ID_EXT',null),
    'layout_id' => env('ZOHO_CRM_LAYOUT_ID_EXT',null),
    'record_id' => env('ZOHO_CRM_RECORD_ID_EXT',null),
    'company_fields' => [
        'plan' => 'string',
        'paid_at' => 'date_time',
    ]
];
