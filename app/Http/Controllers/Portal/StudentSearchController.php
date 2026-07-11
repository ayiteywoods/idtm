<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentSearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless(
            in_array($request->user()->role, [UserRole::Admin, UserRole::Faculty], true),
            403
        );

        $query = trim((string) $request->query('q', ''));
        $students = collect();

        if ($query !== '') {
            $students = StudentProfile::query()
                ->with([
                    'user',
                    'programme',
                    'cohort',
                    'firstSpecialization',
                    'secondSpecialization',
                    'registrations.course',
                    'grades.course',
                    'paymentPlan.installments',
                ])
                ->when($request->user()->role === UserRole::Faculty, function ($builder) use ($request) {
                    $courseIds = $request->user()
                        ->facultyProfile()
                        ->firstOrFail()
                        ->courses()
                        ->pluck('courses.id');

                    $builder->whereHas('registrations', fn ($registration) => $registration->whereIn('course_id', $courseIds));
                })
                ->where(function ($builder) use ($query) {
                    $builder
                        ->where('index_number', 'like', "%{$query}%")
                        ->orWhere('first_name', 'like', "%{$query}%")
                        ->orWhere('other_names', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhereHas('user', fn ($user) => $user
                            ->where('email', 'like', "%{$query}%")
                            ->orWhere('name', 'like', "%{$query}%"));
                })
                ->orderBy('last_name')
                ->limit(10)
                ->get();
        }

        return view('portal.student-search', [
            'query' => $query,
            'students' => $students,
        ]);
    }
}
