<?php

namespace App\Http\Services;

use App\Http\Repositories\ExamRepository;
use App\Models\ExamSessionQuestion;
use Illuminate\Support\Str;

class ExamSessionService {



    private $examRepository;

    public function __construct()
    {
        $this->examRepository = new ExamRepository();
    }


    public function create(mixed $data){

        $exam = $this->examRepository->get($data['exam_id']);
        $session = $exam->sessions()->create($data);
        $questionBank = $exam->questions()->inRandomOrder()->limit($exam->questions_count)->get();
        $sequence = 1;
        foreach ($questionBank as $question) {
            $correctChoicesCount = $question->correct_choices_count;
            $choicesCount = $question->choices_count;

            $randomChoices = $question->choices()->inRandomOrder()->limit($choicesCount)->get();
            $correctChoices = $question->choices()->where('is_correct_choice', true)->inRandomOrder()->limit($correctChoicesCount)->get();

            $choices = $randomChoices->merge($correctChoices);

            $examSessionQuestion = ExamSessionQuestion::create([
                'exam_session_id' => $session->id,
                'sequence' => $sequence,
                'bank_question_id' => $question->id,
                'weight' => 1,
            ]);

            foreach ($choices as $choice) {
                $examSessionQuestion->choices()->create([
                    "exam_session_question_id" => $question->id,
                    "question_bank_choice_id" => $choice->id
                ]);
            }

            $sequence = $sequence + 1;
    }
    return $session;
    }

}
