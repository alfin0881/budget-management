<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPlanning extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'month',
        'budget_amount'
    ];
}
