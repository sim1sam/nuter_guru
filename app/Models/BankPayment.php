<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'account_info',
        'cash_on_delivery_status',
        'manual_payment_status',
        'manual_payment_info',
    ];
}
