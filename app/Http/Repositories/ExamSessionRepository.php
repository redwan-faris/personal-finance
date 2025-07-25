<?php

namespace App\Http\Repositories;

use App\Models\ExamSession;

class ExamSessionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExamSession::class);
    }

    public function getExamSessionsByExamId($exam)
{
    return $this->model
        ->join('exams', 'exam_sessions.exam_id', '=', 'exams.id')
        ->where('exam_sessions.is_active', 1)
        ->where('exam_sessions.exam_id', $exam->id)
        ->get(['exam_sessions.*', 'exams.category','exams.name as exam_name','exams.image_path as exam_image_path','exams.description as exam_description','exams.max_grade','exams.questions_count']);
}


    public function getValidExamSessions(){
        return $this->model->where('is_active', 1)->whereDate('session_date', date('Y-m-d'))->orderBy('id', 'desc')->get();
    }



}
