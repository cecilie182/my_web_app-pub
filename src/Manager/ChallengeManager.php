<?php

namespace App\Manager;

class ChallengeManager
{
    /**
     * Levels must contain an even number of elements
     * @param array $levels
     * @return array
     */
    public function makeTeams(array $levels)
    {
        sort($levels);

        // place each kids into two teams of equal size
        $team1 = $team2 = array();
        foreach ($levels as $key => $level) {
            $key%2 == 0 ? $team1[] = $level : $team2[] = $level;
        }

        $sum1 = array_sum($team1);
        $sum2 = array_sum($team2);
        $gap = ($sum1 - $sum2 > 0) ? $sum1 - $sum2 : $sum2 - $sum1;

        // make the skill level being as even as possible between the two teams
        foreach ($team1 as $k1 => $v1) {
            foreach ($team2 as $k2 => $v2) {

                if ($gap > abs(($sum1 - $v1 + $v2) - ($sum2 - $v2 + $v1))) {
                    $team1[$k1] = $v2;
                    $team2[$k2] = $v1;

                    $sum1 = array_sum($team1);
                    $sum2 = array_sum($team2);
                    $gap = ($sum1 - $sum2 > 0) ? $sum1 - $sum2 : $sum2 - $sum1;
                }
            }
        }

        return [$team1, $team2];
    }
}