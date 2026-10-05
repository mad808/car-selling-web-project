<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_id',
        'title',
        'model',
        'year',
        'price',
        'mileage',
        'fuel_type',
        'transmission',
        'description',
        'image',
        'is_sold',
        'status',
        'admin_note',
        'ai_review',
        'ai_verdict',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'year'    => 'integer',
        'mileage' => 'integer',
        'price'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')->where('is_sold', false);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}