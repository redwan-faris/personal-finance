<?php

namespace App\Http\Services;

use App\Http\Repositories\ExamSessionAttemptRepository;
use App\Http\Repositories\StudentRepository;

class StudentService
{
    private $studentRepository;
    private $examSessionAttemptRepository;

    public function __construct()
    {
        $this->studentRepository = new StudentRepository();
        $this->examSessionAttemptRepository = new ExamSessionAttemptRepository();
    }

    public function createStudent($data)
    {
        $student = $this->studentRepository->findByPhoneNumberOrEmail($data['phone_number'], $data['email']);

        if (is_null($student)) {
            $student = $this->studentRepository->create($data);
        }

        // Create exam session attempt
        $this->examSessionAttemptRepository->create([
            'student_id' => $student->id,
            'exam_session_id' => $data['exam_session_id'],
        ]);

        // Return the created student
        return $student;
    }
}
