<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $course->code }} — Examination Report</title>
    <x-website.favicon />
    <style>
        :root { color-scheme: light; --brand: #12372a; --gold: #d7a928; --slate: #475569; --border: #e2e8f0; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f8fafc; color: #0f172a; font-family: Arial, sans-serif; font-size: 13px; line-height: 1.5; }
        .page { max-width: 1000px; margin: 32px auto; background: #fff; padding: 32px; border: 1px solid var(--border); border-radius: 16px; }
        .toolbar { max-width: 1000px; margin: 24px auto 0; display: flex; justify-content: flex-end; gap: 10px; }
        .button { border: 0; border-radius: 10px; padding: 10px 14px; background: var(--gold); color: var(--brand); font-weight: 700; cursor: pointer; text-decoration: none; }
        .button.secondary { background: #fff; color: var(--brand); border: 1px solid #b7c7be; }
        .header { display: flex; justify-content: space-between; gap: 24px; border-bottom: 3px solid var(--gold); padding-bottom: 18px; }
        .eyebrow { margin: 0; color: var(--gold); font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        h1, h2, p { margin-top: 0; }
        h1 { margin-bottom: 4px; color: var(--brand); font-size: 24px; }
        h2 { margin: 28px 0 12px; color: var(--brand); font-size: 16px; border-bottom: 1px solid var(--border); padding-bottom: 8px; }
        .muted { color: var(--slate); }
        .stat-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-top: 20px; }
        .box { border: 1px solid var(--border); border-radius: 12px; padding: 12px; background: #f8fafc; }
        .label { display: block; color: #64748b; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .value { display: block; margin-top: 4px; font-weight: 700; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid var(--border); padding: 9px 8px; text-align: left; }
        th { color: #64748b; font-size: 11px; letter-spacing: .06em; text-transform: uppercase; }
        td.center, th.center { text-align: center; }
        .badge { display: inline-block; border-radius: 999px; padding: 3px 8px; background: #ecfdf5; color: #047857; font-size: 12px; font-weight: 700; }
        .badge.fail { background: #fef2f2; color: #b91c1c; }
        .badge.muted { background: #f1f5f9; color: #475569; }
        .grade-f { color: #b91c1c; font-weight: 700; }
        .footnote { margin-top: 18px; color: #94a3b8; font-size: 11px; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .page { margin: 0; max-width: none; border: 0; border-radius: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="button secondary" onclick="window.print()">Print</button>
        <button type="button" class="button" onclick="window.print()">Save as PDF</button>
    </div>

    @php $summary = $report['summary']; @endphp

    <main class="page">
        <header class="header">
            <div>
                <p class="eyebrow">{{ $siteName }}</p>
                <h1>Examination Results Broadsheet</h1>
                <p class="muted">
                    {{ $course->code }} — {{ $course->title }}
                    @if ($course->programme) &middot; {{ $course->programme->name }} @endif
                </p>
                <p class="muted">
                    {{ $typeLabel }}
                    @if ($cohort) &middot; {{ $cohort->name }} @endif
                    &middot; Generated {{ now()->format('M j, Y') }}
                </p>
            </div>
        </header>

        <section class="stat-grid">
            <div class="box"><span class="label">Students</span><span class="value">{{ $summary['students'] }}</span></div>
            <div class="box"><span class="label">Class Average</span><span class="value">{{ $summary['average'] !== null ? $summary['average'].'%' : '—' }}</span></div>
            <div class="box"><span class="label">Passed</span><span class="value">{{ $summary['passed'] }}{{ $summary['pass_rate'] !== null ? ' ('.$summary['pass_rate'].'%)' : '' }}</span></div>
            <div class="box"><span class="label">Failed</span><span class="value">{{ $summary['failed'] }}</span></div>
        </section>

        <section>
            <h2>Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Index</th>
                        <th>Cohort</th>
                        <th class="center">Assessments</th>
                        <th class="center">Score</th>
                        <th class="center">%</th>
                        <th class="center">Grade</th>
                        <th class="center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['rows'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row['name'] }}@if ($row['has_resit']) <span class="badge muted">Resit</span>@endif</td>
                            <td>{{ $row['index_number'] }}</td>
                            <td>{{ $row['cohort'] ?? '—' }}</td>
                            <td class="center">{{ $row['assessments'] }}</td>
                            <td class="center">
                                @if ($row['assessments'] > 0)
                                    {{ rtrim(rtrim(number_format($row['obtained'], 2), '0'), '.') }}/{{ rtrim(rtrim(number_format($row['max'], 2), '0'), '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="center">{{ $row['percentage'] !== null ? $row['percentage'].'%' : '—' }}</td>
                            <td class="center"><span class="{{ $row['grade'] === 'F' ? 'grade-f' : '' }}">{{ $row['grade'] }}</span></td>
                            <td class="center">
                                <span class="badge {{ $row['status'] === 'Fail' ? 'fail' : ($row['status'] === 'Pass' ? '' : 'muted') }}">{{ $row['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9">No registered students for this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <p class="footnote">
            Grading scale: A (80+), B (70–79), C (60–69), D (50–59), F (below 50). Pass mark {{ (int) $passMark }}%.
            Ranked by overall percentage. This report reflects grades recorded at the time of generation.
        </p>
    </main>
</body>
</html>
