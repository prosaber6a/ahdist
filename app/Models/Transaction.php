<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'account_id',
        'type',
        'amount',
        'party_id',
        'operation_id',
        'description',
        'debit',
        'credit'
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');

    }
}
