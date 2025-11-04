<?php

namespace App\ModelsZoho;

use App\Models\Account;
use ZohoCrmSDK\ModelsZoho\AccountZohoModel;

class AccountZoho extends AccountZohoModel
{
    protected $modelDB = Account::class;
}
