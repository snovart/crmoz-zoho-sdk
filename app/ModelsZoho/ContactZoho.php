<?php

namespace App\ModelsZoho;

use App\Models\Contact;
use ZohoCrmSDK\ModelsZoho\ContactZohoModel;

class ContactZoho extends ContactZohoModel
{
    protected $modelDB = Contact::class;
}
