<?php
namespace App\Http\Repositories;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\QuestionBank;
use App\Models\QuestionCategory;
use App\Models\Student;

class StatisticsRepository
{
    public function __construct()
    {}

    public function getGeneralStatistics(){
        $examCount = Exam::all()->count();
        $examSessionCount = ExamSession::all()->count();
        $questionsCount = QuestionBank::all()->count();
        $studentsCount = Student::all()->count();
        $activeSessionsCount = ExamSession::where( 'is_active',true)->count();
        return compact('examCount', 'examSessionCount', 'questionsCount', 'studentsCount', 'activeSessionsCount');
    }

}
