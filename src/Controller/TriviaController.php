<?php

namespace App\Controller;

use App\Services\HashingHelper;
use App\Services\TriviaNumberRandomizer;
use App\TriviaException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TriviaManager;

/**
 * Class TriviaController
 *
 * @author Mareks Galanskis
 */
class TriviaController extends AbstractController
{
    private const ITERATIONS_KEY = 'iterations';

    private const PREV_QUESTIONS_KEY = 'prev_questions';

    private const CORRECT_ANSWER_KEY = 'correct_answer';

    private const ANSWER_KEY = 'answer';

    /**
     * @var
     */
    private $triviaManager;

    /**
     * Trivia controller constructor
     * @param TriviaManager $triviaManager
     */
    public function __construct(TriviaManager $triviaManager)
    {
        $this->triviaManager = $triviaManager;
    }

    /**
     * @Route("/", name="trivia", methods={"GET"})
     * @param SessionInterface $session
     * @return Response
     */
    public function index(SessionInterface $session): Response
    {
        $prevQuestions = $session->get(self::PREV_QUESTIONS_KEY, []);
        $iterations = (int) $session->get(self::ITERATIONS_KEY, 1);

        try{
            $answer = TriviaNumberRandomizer::getRandomTriviaNumber();
            do {
                $question = $this->triviaManager->getQuestion($answer);
            } while(in_array(HashingHelper::hashTriviaQuestion($question), $prevQuestions, true)
            );
        } catch (TriviaException $e) {
            return $this->render('trivia/error.html.twig');
        }

        $answerOptions = $this->triviaManager->getAnswers($answer);

        $prevQuestions[] += HashingHelper::hashTriviaQuestion($question);

        $session->set(self::PREV_QUESTIONS_KEY, $prevQuestions);
        $session->set(self::CORRECT_ANSWER_KEY, $answer);

        $maxIterations = $this->triviaManager->getMaxIterations();

        return $this->render('trivia/index.html.twig', [
            'question' => $question,
            'answers' => $answerOptions,
            'iterations' => $iterations,
            'maxIterations' => $maxIterations,
        ]);
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function post(Request $request, SessionInterface $session): Response
    {
        if (!(
            $session->has(self::ITERATIONS_KEY)
            || $session->has(self::CORRECT_ANSWER_KEY)
            || $session->has(self::ANSWER_KEY))
        ) {
            return $this->redirectToRoute('trivia');
        }

        $iterations = $session->get(self::ITERATIONS_KEY, 1);
        $correctAnswer = $session->get(self::CORRECT_ANSWER_KEY);
        $submittedAnswer = $request->get(self::ANSWER_KEY);

        $winner = null;

        if (!$this->triviaManager->isAnswerCorrect($submittedAnswer, $correctAnswer)) {
            $winner = false;
        } elseif ($this->triviaManager->didUserWin($iterations)) {
            $winner = true;
        }

        if ($winner !== null) {
            $session->clear();

            return $this->render('trivia/end.html.twig', [
                'winner' => $winner,
                'answer' => $correctAnswer,
            ]);
        }

        $session->set(self::ITERATIONS_KEY, ++$iterations);

        return  $this->redirectToRoute('trivia');
    }
}
