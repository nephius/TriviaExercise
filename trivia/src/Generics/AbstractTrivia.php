<?php

namespace App\Generics;

/**
 * Class AbstractTrivia
 *
 * @author Mareks Galanskis
 */
class AbstractTrivia implements TriviaInterface
{
    /**
     * Base
     *
     * @var string
     */
    protected static $base;

    /**
     * @var int
     */
    public static $type;

    /**
     * Get path
     *
     * @param int $targetAnswer
     *
     * @return string
     */
    public function getPath(int $targetAnswer): string
    {
        return implode('/', [$targetAnswer, static::$base]);
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType(): int
    {
        return static::$type;
    }
}
