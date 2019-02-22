<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class gp extends Model
{
    protected $fillable = [
        'id',
        'daily_stock_purchase',
        'intake',
        'budget_estimate',
        'GP(£)',
        'GP(%)',    
        'day',    
        'date',    
        'week_id',    
        'unique_week_id',       
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
