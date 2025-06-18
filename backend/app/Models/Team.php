<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property int $strength
 * @property int $league_id
 */
class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'league_id',
        'name',
        'strength',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Soccer::class, 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Soccer::class, 'away_team_id');
    }
}
