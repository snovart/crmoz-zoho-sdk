<?php

return [
    'system_symbol_to_string' => [
        '$' => 'zoho_system_'
    ],

    'ignored_attributes_all' => [
        'Tag',
    ],

    'ignored_attributes_subforms_all' => [
        'Owner',
        'Modified_By',
        'Created_By',
        '$approval',
        '$process_flow',
        '$followed',
        '$editable',
        '$approved',
        '$currency_symbol',
        'Tag',
        'Modified_Time',
        'Created_Time',
        'Last_Activity_Time',
        'Name'
    ],
    'suffix_id_subform_without_table' => '_subform_id',
    'system_fields' => [
        [
            'api_name' => 'Modified_By',
            'data_type' => 'lookup',
        ],
        [
            'api_name' => 'Created_By',
            'data_type' => 'lookup',
        ],
        [
            'api_name' => 'Stage_History',
            'data_type' => 'lookup',
        ],
    ]
];