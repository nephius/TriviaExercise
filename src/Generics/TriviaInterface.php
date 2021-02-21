<?php

namespace App\Generics;

/**
 * Interface TriviaInterface
 *
 * @author Mareks Galanskis
 */
interface TriviaInterface
{
    /**
     * Get fragment
     *
     * @param int $targetAnswer
     *
     * @return string
     */
    public function getPath(int $targetAnswer): string;

    /**
     * Get type
     *
     * @return int
     */
    public function getType(): int;
}
