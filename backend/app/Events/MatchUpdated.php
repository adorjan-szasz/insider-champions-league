<?php

namespace App\Events;

use App\Models\Soccer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchUpdated
{
    use Dispatchable, SerializesModels;

    public Soccer $match;

    public function __construct(Soccer $match)
    {
        $this->match = $match;
    }
}
