<?php

namespace App\Http\Repositories;

use App\Models\QuestionBank;
use App\Models\QuestionBankChoice;
use Illuminate\Database\Eloquent\Model;

class QuestionBankRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(QuestionBank::class);
    }

    public function create(array $data){
        $question = parent::create($data);
        $choices = $data['choices'];
        foreach($choices as $choice){
            $choice['bank_question_id'] = $question->id;
            QuestionBankChoice::create($choice);
        }
        return $question;
    }

    public function update(Model $question, array $data){
        $question->update($data);
        QuestionBankChoice::where('bank_question_id', $question->id)->delete();
        if(array_key_exists('choices',$data)){
            foreach($data['choices'] as $choice){
                $choice['bank_question_id'] = $question->id;
                QuestionBankChoice::create($choice);
            }
        }
        return $question;
    }
}
