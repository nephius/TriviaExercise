<?php

namespace App\Services;

use App\Resources\FragmentTriviaResource;
use App\TriviaException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class TriviaManager
 *
 * @author Mareks Galanskis
 */
class TriviaManager
{
    /**
     * Max answers
     */
    private const MAX_CORRECT = 1;

    /**
     * @var FragmentTriviaResource
     */
    private $fragmentTriviaResource;


    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Trivia manager constructor
     * @param FragmentTriviaResource $fragmentTriviaResource
     * @param LoggerInterface $logger
     */
    public function __construct(FragmentTriviaResource $fragmentTriviaResource, LoggerInterface $logger)
    {
        $this->fragmentTriviaResource = $fragmentTriviaResource;
        $this->logger = $logger;
    }

    /**
     * Get question
     *
     * @param int $targetAnswer
     *
     * @return string
     *
     * @throws TriviaException
     */
    public function getQuestion(int $targetAnswer): string
    {
        try {
            return TriviaFormatter::formatQuestion(
                $this->fragmentTriviaResource->getQuestion($targetAnswer),
                $this->fragmentTriviaResource->getTrivia()->getType()
            );
        } catch (ConnectException $e) {
            $logMessage = implode(',', ['Network failure', "message: {$e->getMessage()}"]);

            $this->logger->error($logMessage);
        } catch (ClientException $e) {
            $logMessage = implode(',', ['URL not found', "message: {$e->getMessage()}"]);

            $this->logger->error($logMessage);
        } catch (GuzzleException $e) {
            $logMessage = implode(',', ['Unhandled exception', "message: {$e->getMessage()}"]);

            $this->logger->error($logMessage);
        }

        throw new TriviaException('Cannot continue', 0);
    }

    /**
     * Get answers
     *
     * @param int $answer
     * @param int $nAnswers
     *
     * @return array
     */
    public function getAnswers(int $answer, int $nAnswers = 4): array
    {
        $closeNumbers = TriviaNumberRandomizer::getCloseNumbers($answer, $nAnswers);

        shuffle($closeNumbers);

        return $closeNumbers;
    }

    /**
     * Did user win
     *
     * @param $iterations
     *
     * @return bool
     */
    public function didUserWin(int $iterations): bool
    {
        return $iterations >= self::MAX_CORRECT;
    }

    /**
     * Is answer correct
     *
     * @param string $userAnswer
     * @param string $correctAnswer
     *
     * @return bool
     */
    public function isAnswerCorrect(string $userAnswer, string $correctAnswer): bool
    {
        return strcmp($userAnswer, $correctAnswer) === 0;
    }

    /**
     * Get max iterations
     *
     * @return int
     */
    public function getMaxIterations(): int
    {
        return self::MAX_CORRECT;
    }
}
