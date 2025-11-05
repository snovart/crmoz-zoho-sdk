<?php

namespace App\ModelsZoho;

use App\Models\Lead;
use App\Models\LeadActivity;
use ZohoCrmSDK\ModelsZoho\LeadZohoModel;

class LeadZoho extends LeadZohoModel
{
    // Local Eloquent model (used only during sync; this command does not modify it)
    protected $modelDB = Lead::class;

    // Required for createDataZoho(): defines the local → API field mapping for subforms
    protected $renamed = [
        'lead_activities' => [
            'activity_date' => 'Activity_Date',
            'activity_type' => 'Activity_Type',
            'note'          => 'Note',
            'created_by'    => 'Created_By',
        ],
    ];

    protected $subforms = [
        // Key — Zoho API name of the subform
        'Lead_Activities' => [
            // Internal SDK name of the subform (local identifier)
            'name'    => 'lead_activities',

            // Mapping: Zoho API field → local snake_case field
            'renamed' => [
                'Activity_Date' => 'activity_date',
                'Activity_Type' => 'activity_type',
                'Note'          => 'note',
                // 'Created_By' is a system field — should not be sent manually
            ],
            'modelDB'    => LeadActivity::class,
        ],
    ];
}
