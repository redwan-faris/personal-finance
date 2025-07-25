<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'people';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'balance',
        'note',
    ];

    protected $casts = [
        'balance' => 'integer',
    ];
}
