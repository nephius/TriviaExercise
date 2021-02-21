<?php

namespace App\Model;

use App\Generics\AbstractTrivia;

/**
 * Class FragmentTrivia
 *
 * @author Mareks Galanskis
 */
class FragmentTrivia extends AbstractTrivia
{
    /**
     * Query parameter
     */
    private const PARAMETER = 'fragment';
    /**
     * Base
     *
     * @var string
     */
    protected static $base = 'trivia';

    /**
     * @var int
     */
    public static $type = TriviaEnum::FRAGMENT;

    /**
     * Get path
     *
     * @param int $targetAnswer
     *
     * @return string
     */
    public function getPath(int $targetAnswer): string
    {
        return implode('?', [parent::getPath($targetAnswer), self::PARAMETER]);
    }
}
