<?php

namespace App\Support;

use App\Models\Course;
use Illuminate\Support\Collection;

class ExamReport
{
    public const PASS_MARK = 50.0;

    public const TYPES = ['all', 'assignment', 'quiz', 'project', 'exam'];

    /**
     * Build a results broadsheet for a course, optionally scoped to a
     * cohort and a single assessment type (e.g. exam only).
     */
    public static function build(Course $course, ?int $cohortId = null, string $type = 'all'): array
    {
        $type = in_array($type, self::TYPES, true) ? $type : 'all';

        $registrations = $course->registrations()
            ->with(['student.user', 'student.cohort'])
            ->get()
            ->filter(fn ($registration) => $registration->student !== null)
            ->when($cohortId, fn (Collection $items) => $items->filter(
                fn ($registration) => (int) $registration->student->cohort_id === $cohortId
            ));

        $gradesByStudent = $course->grades()
            ->when($type !== 'all', fn ($query) => $query->where('type', $type))
            ->get()
            ->groupBy('student_profile_id');

        $rows = $registrations
            ->map(function ($registration) use ($gradesByStudent) {
                $student = $registration->student;
                $grades = $gradesByStudent->get($student->id, collect());

                $obtained = (float) $grades->sum('score');
                $max = (float) $grades->sum('max_score');
                $percentage = $max > 0 ? round(($obtained / $max) * 100, 1) : null;

                return [
                    'student' => $student,
                    'name' => $student->fullName(),
                    'index_number' => $student->index_number,
                    'cohort' => $student->cohort?->name,
                    'assessments' => $grades->count(),
                    'obtained' => $obtained,
                    'max' => $max,
                    'percentage' => $percentage,
                    'grade' => $percentage !== null ? self::gradeLetter($percentage) : '—',
                    'status' => self::status($percentage),
                    'has_resit' => $grades->contains(fn ($grade) => (bool) $grade->is_resit),
                ];
            })
            ->sortByDesc(fn ($row) => $row['percentage'] ?? -1)
            ->values();

        return [
            'rows' => $rows,
            'summary' => self::summary($rows),
        ];
    }

    public static function gradeLetter(float $percentage): string
    {
        return match (true) {
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }

    public static function status(?float $percentage): string
    {
        if ($percentage === null) {
            return 'No grade';
        }

        return $percentage >= self::PASS_MARK ? 'Pass' : 'Fail';
    }

    private static function summary(Collection $rows): array
    {
        $graded = $rows->filter(fn ($row) => $row['percentage'] !== null);

        return [
            'students' => $rows->count(),
            'graded' => $graded->count(),
            'ungraded' => $rows->count() - $graded->count(),
            'passed' => $graded->filter(fn ($row) => $row['status'] === 'Pass')->count(),
            'failed' => $graded->filter(fn ($row) => $row['status'] === 'Fail')->count(),
            'average' => $graded->isNotEmpty() ? round($graded->avg('percentage'), 1) : null,
            'highest' => $graded->isNotEmpty() ? $graded->max('percentage') : null,
            'lowest' => $graded->isNotEmpty() ? $graded->min('percentage') : null,
            'pass_rate' => $graded->isNotEmpty()
                ? round(($graded->filter(fn ($row) => $row['status'] === 'Pass')->count() / $graded->count()) * 100)
                : null,
        ];
    }

    public static function typeLabel(string $type): string
    {
        return match ($type) {
            'all' => 'All assessments',
            'exam' => 'Examination only',
            default => ucfirst($type).'s only',
        };
    }
}
