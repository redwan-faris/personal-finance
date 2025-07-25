<?php

namespace Database\Factories;

use App\Models\QuestionBank;
use App\Models\Exam;
use App\Models\QuestionCategory;
use App\Models\QuestionBankChoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuestionBankFactory extends Factory
{
    protected $model = QuestionBank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $examId = Exam::inRandomOrder()->first()->id;
        $categoryId = QuestionCategory::inRandomOrder()->first()->id ;

        $questionType = ['single', 'multiple', 'truefalse'][rand(0, 2)];

        $choicesCount = ($questionType === 'truefalse') ? 2 : rand(2, 5);
        $correctChoicesCount = ($questionType === 'multiple') ? rand(1, $choicesCount) : 1;

        $question = [
            'exam_id' => $examId,
            'question_text' => Str::random(20),
            'choices_count' => $choicesCount,
            'question_type' => $questionType,
            'correct_choices_count' => $correctChoicesCount,
            'category_id' => $categoryId,
        ];

        $questionBank = QuestionBank::create($question);

        for ($i = 0; $i < $choicesCount; $i++) {
            QuestionBankChoice::firstOrCreate([
                'bank_question_id' => $questionBank->id,
                'choice_text' => Str::random(10),
                'is_correct_choice' => ($i < $correctChoicesCount),
            ]);
        }

        return $question;
    }
}
