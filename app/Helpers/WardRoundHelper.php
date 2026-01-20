<?php

namespace App\Helpers;

use App\Models\GrandWardRound;
use Illuminate\Support\Str;

class WardRoundHelper
{
    public static function generateToken(GrandWardRound $round)
    {
        if (!$round->token) {
            $round->update([
                'token' => Str::uuid()
            ]);
        }
    }

    public static function rowColor(GrandWardRound $round)
    {
        return $round->token ? '#c8f7c5' : '#f7c5c5';
    }
}
