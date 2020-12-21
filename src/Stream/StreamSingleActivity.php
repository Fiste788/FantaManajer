<?php
declare(strict_types=1);

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Stream;

use Cake\I18n\FrozenTime;

/**
 * Description of StreamSingleActivity
 *
 * @author Stefano
 */
abstract class StreamSingleActivity extends StreamActivity
{
    /**
     * Get time
     *
     * @return \Cake\I18n\FrozenTime
     * @throws \RuntimeException
     */
    public function getTime(): FrozenTime
    {
        /** @var \Cake\I18n\FrozenTime|null $res */
        $res = $this->activity->offsetGet('time');
        if ($res) {
            return $res;
        }

        throw new \RuntimeException('Unable to determine updated at activity time');
    }
}
