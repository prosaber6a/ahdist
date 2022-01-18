<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'party_id',
        'w_no',
        'truck_no',
        'product_id',
        'bag',
        'bag_weight',
        'send_weight',
        'receive_weight',
        'final_weight',
        'labour_value',
        'labour_bill',
        'rate',
        'truck_fare_operation',
        'truck_fare',
        'amount',
        'note',
        'type',
    ];

    public function party () {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function product () {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
