<?php
namespace App\Services;

use App\Models\GrandWardRound;

class WardRoundService
{
    public function endActivity(GrandWardRound $round)
    {
        $round->update([
            'to_time' => now()->format('H:i'),
            'involvement' => 'P',
            'ended_at' => now()
        ]);
    }

    public function toggleStatus(GrandWardRound $round)
    {
        $round->update([
            'involvement' => $round->involvement === 'A' ? 'P' : 'A'
        ]);
    }
}
