<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Lineup;
use Authorization\IdentityInterface;

class LineupPolicy
{
    /**
     * Can current
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canCurrent(IdentityInterface $user, Lineup $lineup)
    {
        return true;
    }

    /**
     * Can add
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }

    /**
     * Can edit
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }

    /**
     * Can delete
     *
     * @param \Authorization\IdentityInterface $user User
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Lineup $lineup)
    {
        return $user->hasTeam($lineup->team_id);
    }
}
