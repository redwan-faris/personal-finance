<?php
namespace App\Http\Repositories;

use App\Models\QuestionCategory;

class QuestionCategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(QuestionCategory::class);
    }

}
