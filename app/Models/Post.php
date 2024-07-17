<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        // Define fillable attributes here
        'title','body'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Format the date attribute before saving
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where('name', 'like', '%' . $search . '%')
        );
    }
}