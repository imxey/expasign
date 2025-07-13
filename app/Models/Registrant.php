<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registrant extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                if (empty($model->id)) {
                    $model->id = (string) Str::uuid();
                }
            }
        );
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'nim',
        'school',
        'category',
        'nominal',
        'receipt',
        'isSubmit',
        'isEdu',
        'isExpa',
        'code',
        'status',
    ];
}
