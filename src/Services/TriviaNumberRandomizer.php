<?php

namespace App\Services;


use App\TriviaException;

/**
 * Class TriviaNumberRandomizer
 *
 * @author Mareks Galanskis
 */
class TriviaNumberRandomizer
{
    /**
     * Get random trivia number
     *
     * @return int

     * @throws TriviaException
     */
    public static function getRandomTriviaNumber(): int
    {
        try {
            return random_int(1, 200);
        } catch (\Exception $e) {
            throw new TriviaException('Number generator cannot generate sufficient entropy');
        }
    }

    /**
     * Get close number
     *
     * @param int $number
     * @param int $nNumbers
     *
     * @return array
     */
    public static function getCloseNumbers(int $number, int $nNumbers): array
    {
        $max = $number + $nNumbers - 1;

        return range($number, $max);
    }
}
