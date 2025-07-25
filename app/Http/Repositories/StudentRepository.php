<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Student;

class StudentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Student::class);
    }


    public function findByPhoneNumberOrEmail($phoneNumber, $email)
    {
        return $this->model->where('phone_number', $phoneNumber)->orWhere('email', $email)->first();
    }

    public function getStudentsByExamSessionId($examSessionId)
    {
        return $this->model->whereHas('attempts', function ($query) use ($examSessionId) {
            $query->where('exam_session_id', $examSessionId);
        })->get();
    }

}
