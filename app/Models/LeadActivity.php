<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // uncomment if softDelete=true

class LeadActivity extends Model
{
    // use SoftDeletes; // uncomment if softDelete=true

    protected $table = 'lead_activities';

    // Allow mass assignment for all columns (adjust if you prefer $fillable)
    protected $guarded = [];

    // Helpful casts
    protected $casts = [
        'activity_date' => 'date',
    ];
}
