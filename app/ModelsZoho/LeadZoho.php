<?php

namespace App\ModelsZoho;

use App\Models\Lead;
use ZohoCrmSDK\ModelsZoho\LeadZohoModel;

class LeadZoho extends LeadZohoModel
{
    protected $modelDB = Lead::class;
}
