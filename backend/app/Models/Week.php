<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $number
 * @property int $league_id
 */
class Week extends Model
{
    /** @use HasFactory<\Database\Factories\WeekFactory> */
    use HasFactory;

    protected $fillable = ['league_id', 'week_number'];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(Soccer::class);
    }
}
