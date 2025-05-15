<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerInvoice extends Model
{
    protected $table = 'customer_invoice';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'subtotal',
        'delivery',
        'discount',
        'total',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'street_address',
        'apartment',
        'city',
        'postcode',
        'payment_method',
        'status'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(CustomerInvoiceDetail::class, 'invoice_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 