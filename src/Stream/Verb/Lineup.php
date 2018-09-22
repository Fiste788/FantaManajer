<?php

namespace App\Stream\Verb;

use App\Stream\StreamActivityInterface;
use App\Stream\StreamSingleActivity;

class Lineup extends StreamSingleActivity implements StreamActivityInterface
{

    public function getBody()
    {
        $lineup = $this->activity->offsetGet('object');
        $regular = array_splice($lineup->dispositions, 0, 11);
        $players = [];
        foreach ($regular as $disposition) {
            $players[] = $disposition->member->player->surname;
        }

        return implode(', ', $players);
    }

    public function getTitle()
    {
        return __('{0} has setup lineup for matchday {1}', [
            $this->activity['activities'][0]->offsetGet('actor')->name,
            $this->activity['activities'][0]->offsetGet('object')->matchday->number
        ]);
    }

    public function getIcon()
    {
        return 'star';
    }

    public static function contain()
    {
        return ['Matchdays', 'Dispositions.Members.Players'];
    }
}
