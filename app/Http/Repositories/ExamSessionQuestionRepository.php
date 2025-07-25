<?php

namespace App\Http\Repositories;

use App\Models\ExamSessionQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamSessionQuestionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExamSessionQuestion::class);
    }

    public function create(array $data){
        $lastSequence = $this->model->where('exam_session_id', $data['exam_session_id'])->orderBy('sequence', 'desc')->first();
        $data['sequence'] = ($lastSequence ? $lastSequence->sequence : 0) + 1;

        $question = ExamSessionQuestion::create($data);
        foreach ($data['choices'] as $choiceId) {
            $question->choices()->create([
                "exam_session_question_id" => $question->id,
                "question_bank_choice_id" => $choiceId
            ]);
        }
        return $question;
    }

    public function update(Model $model, array $data)
    {

        $model->update($data);
        $model->refresh();
        if (isset($data['choices'])) {
            $model->choices()->delete();

            foreach ($data['choices'] as $choiceId) {
                $model->choices()->create([
                    'exam_session_question_id' => $model->id,
                    'question_bank_choice_id' => $choiceId,
                ]);
            }
        }

        return $model;
    }

    public function getExamSessionQuestionsByAttemptId($attempt_id){
        return $this->model->where('attempt_id', $attempt_id)
        ->orderBy('sequence', 'asc')
        ->get();
    }
}
