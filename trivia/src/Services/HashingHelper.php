<?php
namespace App\Services;


/**
 * Class HashingHelper
 *
 * @author Mareks Galanskis
 */
class HashingHelper
{
    /**
     * Hash trivia question
     *
     * @param string $question
     *
     * @return int
     */
    public static function hashTriviaQuestion(string $question): int
    {
        return crc32($question);
    }
}
