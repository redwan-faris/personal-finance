<?php

namespace App\Http\Repositories;

use App\Models\ExamSessionAttempt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExamSessionAttemptRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExamSessionAttempt::class);
    }

    public function getByStudentAndSession()
    {
        return ExamSessionAttempt::whereHas('examSession', function (Builder $query) {
                $query->where('session_date', Carbon::today());
            })
            ->whereNotNull('image_path')
            ->whereNotNull('signature_path')
            ->where('status', '!=', 'completed')
            ->with('student')
            ->with('examSession.exam')
            ->get();
    }

}
