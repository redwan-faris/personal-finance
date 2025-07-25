<?php
namespace App\Http\Repositories;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamSessionAttempt;
use App\Models\ExamSessionAttemptChoice;
use App\Models\ExamSessionQuestion;
use App\Models\ExamSessionQuestionChoice;
use Illuminate\Support\Facades\DB;

class ExamSessionAttemptChoiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExamSessionAttemptChoice::class);
    }

    public function create(array $data)
    {
        $answers = [];

        $this->model->where('exam_session_attempt_id', $data['exam_session_attempt_id'])
                    ->where('exam_session_question_id', $data['exam_session_question_id'])
                    ->delete();

        $choices = $data['choices'];
        foreach ($choices as $choice) {
            $answerData = [
                'exam_session_attempt_id' => $data['exam_session_attempt_id'],
                'exam_session_question_id' => $data['exam_session_question_id'],
                'exam_session_question_choice_id' => $choice
            ];
            $choice = $this->model->create($answerData);
            $answers[] = $choice;
        }

        return $answers;
    }


    public function getByQuestionIdAndAttemptId($questionId,$attemptId){
        return $this->model->where('exam_session_question_id',$questionId)->where('exam_session_attempt_id',$attemptId)->get();
    }


    public function getAnswersByAttemptId($attemptId){
        $attempt = ExamSessionAttempt::with('examSession')
            ->where('id', $attemptId)
            ->first();

        if (!$attempt || !$attempt->examSession) {
            return [];
        }

        $questions = ExamSessionQuestion::with(['questionBank', 'choices'])
            ->where('exam_session_id', $attempt->exam_session_id)
            ->orderBy('sequence', 'asc')
            ->get();

        if ($questions->isEmpty()) {
            return [];
        }

        $answers = $this->model
            ->where('exam_session_attempt_id', $attemptId)
            ->get();

        $grouped = [];

        foreach ($questions as $question) {
            if ($question->id) {
                $grouped[$question->id] = [
                    'exam_session_attempt_id' => $attemptId,
                    'exam_session_question_id' => $question->id,
                    'sequence' => $question->sequence,
                    'choices' => []
                ];
            }
        }

        foreach ($answers as $answer) {
            $questionId = $answer->exam_session_question_id;
            $choiceId = $answer->exam_session_question_choice_id;

            if (isset($grouped[$questionId])) {
                $grouped[$questionId]['choices'][] = $choiceId;
            }
        }

        // Filter out questions with no choices
        $grouped = array_filter($grouped, function($question) {
            return !empty($question['choices']);
        });

        uasort($grouped, function($a, $b) {
            return ($a['sequence'] ?? 0) - ($b['sequence'] ?? 0);
        });

        return array_values($grouped);
    }

    public function getAnswersWithObjectsByAttemptId($attemptId, $perPage = 10){
        $attempt = ExamSessionAttempt::with('examSession')
            ->where('id', $attemptId)
            ->first();

        if (!$attempt || !$attempt->examSession) {
            return [];
        }

        $questions = ExamSessionQuestion::with(['questionBank', 'choices.choice'])
            ->where('exam_session_id', $attempt->exam_session_id)
            ->orderBy('sequence', 'asc')
            ->get();

        if ($questions->isEmpty()) {
            return [];
        }

        $answers = $this->model
            ->with(['examSessionQuestion', 'examSessionQuestion.choices.choice'])
            ->where('exam_session_attempt_id', $attemptId)
            ->get();

        $grouped = [];

        foreach ($questions as $question) {
            if ($question->id) {
                $grouped[$question->id] = [
                    'exam_session_attempt_id' => $attemptId,
                    'exam_session_question_id' => $question->id,
                    'sequence' => $question->sequence,
                    'question' => $question,
                    'choices' => []
                ];
            }
        }

        foreach ($answers as $answer) {
            $questionId = $answer->exam_session_question_id;
            $choice = $answer->examSessionQuestion->choices->where('id', $answer->exam_session_question_choice_id)->first();

            if (isset($grouped[$questionId]) && $choice) {
                $grouped[$questionId]['choices'][] = $choice;
            }
        }

        // Filter out questions with no choices
        $grouped = array_filter($grouped, function($question) {
            return !empty($question['choices']);
        });

        uasort($grouped, function($a, $b) {
            return ($a['sequence'] ?? 0) - ($b['sequence'] ?? 0);
        });

        $items = array_values($grouped);

        // Create a custom paginator
        $page = request()->get('page', 1);
        $total = count($items);
        $items = array_slice($items, ($page - 1) * $perPage, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
    }

    public function gradeExam($attemptId){
        $answers = $this->getAnswersByAttemptId($attemptId);
        $attempt = ExamSessionAttempt::where("id", $attemptId)
            ->with(['examSession.exam', 'examSession.questions'])
            ->first();

        if (!$attempt || !$attempt->examSession || !$attempt->examSession->exam) {
            return ['finalGrade' => 0, 'maxGrade' => 0];
        }

        $weightSum = 0;
        $correctAnswersCount=0;
        $wrongAnswersCount=0;

        foreach($answers as $answer){
            $question = ExamSessionQuestion::with(['questionBank', 'choices.choice'])
                ->where('id', $answer['exam_session_question_id'])
                ->first();

            if (!$question || !$question->questionBank) {
                continue;
            }

            $correctAnswers = $question->questionBank->correct_choices_count;
            $correctChoice = 0;
            $choices = $answer['choices'] ?? [];

            if (empty($choices)) {
                continue;
            }

            foreach($choices as $choice){
                $bankChoice = ExamSessionQuestionChoice::with('choice')
                    ->where('id', $choice)
                    ->first();

                if ($bankChoice && $bankChoice->choice && $bankChoice->choice->is_correct_choice) {
                    $correctChoice++;
                }
            }

            if($correctAnswers == $correctChoice) {
                $weightSum += $question->weight;
                $correctAnswersCount = $correctAnswersCount + 1;
            }else{
                $wrongAnswersCount = $wrongAnswersCount + 1;
            }
        }

        $maxGrade = $attempt->examSession->exam->max_grade;
        $maxWeight = DB::table('exam_session_questions')
            ->where('exam_session_questions.exam_session_id', $attempt->exam_session_id)
            ->where('exam_session_questions.attempt_id', $attemptId)
            ->sum('weight');

        if ($maxWeight == 0) {
            return ['finalGrade' => 0, 'maxGrade' => $maxGrade];
        }

        $finalGrade = ($weightSum * $maxGrade) / $maxWeight;
        return ['finalGrade' => $finalGrade, 'maxGrade' => $maxGrade,'correctAnswersCount' => $correctAnswersCount,'wrongAnswersCount' =>$wrongAnswersCount];
    }

}
