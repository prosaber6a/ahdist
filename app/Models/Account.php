<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank',
        'branch',
        'name',
        'acc_no',
        'initial_balance',
        'note',
        'status',
    ];
}
