<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Submission extends Model
{
    //
    public function registrant(): BelongsTo
    {
        return $this->belongsTo(Registrant::class);
    }
    protected $table = 'submission';
    protected $fillable = [
        'id',
        'registrant_id',
        'file',
    ];
}
