<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory, HasUuids;

    /**
     * Tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Menandakan jika ID tidak auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'team_name',
        'category',
        'nominal',
        'receipt_path',
        'isExpa',
        'isEdu',
        'isSubmit',
        'code',
        'status',
    ];

    /**
     * Tipe data native untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'isExpa' => 'boolean',
        'isEdu' => 'boolean',
        'isSubmit' => 'boolean',
    ];

    /**
     * Relasi one-to-many ke model Participant.
     * Satu tim punya banyak peserta.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
    
}
