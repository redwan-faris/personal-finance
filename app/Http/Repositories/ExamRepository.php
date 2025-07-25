<?php

namespace App\Http\Repositories;

use App\Models\Exam;

class ExamRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Exam::class);
    }

    public function getByCategory($category){
        return $this->model->where('category', $category)->get();
    }


}
