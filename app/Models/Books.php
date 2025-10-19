<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Books extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'person_type',
        'document_type',
        'document_number',
        'first_name',
        'last_name',
        'is_minor',
        'email',
        'phone',
        'country',
        'item_type',
        'item_description',
        'has_payment_proof',
        'attached_files',
        'claims_amount',
        'claimed_amount',
        'claim_type',
        'claim_description',
        'request',
        'claim_date',
        'preferred_contact_method',
        'data_processing_consent',
        'signature',
        'form_id',
    ];

    protected $casts = [
        'is_minor' => 'boolean',
        'has_payment_proof' => 'boolean',
        'attached_files' => 'array',
        'claims_amount' => 'boolean',
        'claimed_amount' => 'float', // cambiar de decimal a float
        'claim_date' => 'date',
        'data_processing_consent' => 'boolean',
    ];
}
