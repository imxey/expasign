<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
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
        'team_id',
        'name',
        'nim',
        'email',
        'phone',
        'school',
        'igLink',
        'followExpa',
        'followEdu',
        'followMp',
        'repostSg',
        'role',
    ];

    /**
     * Tipe data native untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Tidak ada cast khusus yang dibutuhkan dari schema,
        // tapi jika perlu bisa ditambahkan di sini.
    ];

    /**
     * Relasi many-to-one ke model Team.
     * Satu peserta milik satu tim.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
