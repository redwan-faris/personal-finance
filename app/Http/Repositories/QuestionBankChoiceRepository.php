<?php
namespace App\Http\Repositories;

use App\Models\QuestionBankChoice;

class QuestionBankChoiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(QuestionBankChoice::class);
    }


}
