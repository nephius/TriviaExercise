<?php

namespace App\Services;


use App\Model\TriviaEnum;

/**
 * Class TriviaFormatter
 *
 * @author Mareks Galanskis
 */
class TriviaFormatter
{
    /**
     * Format question
     *
     * @param string $question
     * @param int $triviaType
     *
     * @return string
     */
    public static function formatQuestion(string $question, int $triviaType): string
    {
        switch ($triviaType) {
            case TriviaEnum::FRAGMENT:
                return self::formatFragment($question);
            // Cases for other types
            default:
                return $question;
        }
    }
    /**
     * Format fragment
     *
     * @param string $fragment
     *
     * @return string
     */
    private static function formatFragment(string $fragment): string
    {
        return "What is ${fragment}?";
    }
}
