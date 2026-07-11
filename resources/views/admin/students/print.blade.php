<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $student->fullName() }} — Student Details</title>
    <x-website.favicon />
    <style>
        :root {
            color-scheme: light;
            --brand: #12372a;
            --gold: #d7a928;
            --slate: #475569;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        .page {
            max-width: 960px;
            margin: 32px auto;
            background: #fff;
            padding: 32px;
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .toolbar {
            max-width: 960px;
            margin: 24px auto 0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .button {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            background: var(--gold);
            color: var(--brand);
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .button.secondary {
            background: #fff;
            color: var(--brand);
            border: 1px solid #b7c7be;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 3px solid var(--gold);
            padding-bottom: 18px;
        }

        .eyebrow {
            margin: 0;
            color: var(--gold);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        h1, h2, h3, p {
            margin-top: 0;
        }

        h1 {
            margin-bottom: 4px;
            color: var(--brand);
            font-size: 28px;
        }

        h2 {
            margin: 28px 0 12px;
            color: var(--brand);
            font-size: 18px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 8px;
        }

        .muted {
            color: var(--slate);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 20px;
        }

        .box {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            background: #f8fafc;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .value {
            display: block;
            margin-top: 4px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border-bottom: 1px solid var(--border);
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #64748b;
            font-size: 11px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 3px 8px;
            background: #ecfdf5;
            color: #047857;
            font-size: 12px;
            font-weight: 700;
        }

        .badge.warning {
            background: #fffbeb;
            color: #92400e;
        }

        @media print {
            body {
                background: #fff;
            }

            .toolbar {
                display: none;
            }

            .page {
                margin: 0;
                max-width: none;
                border: 0;
                border-radius: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="button secondary" onclick="window.print()">Print</button>
        <button type="button" class="button" onclick="window.print()">Save as PDF</button>
    </div>

    <main class="page">
        <header class="header">
            <div>
                <p class="eyebrow">{{ $siteName }}</p>
                <h1>{{ $student->fullName() }}</h1>
                <p class="muted">{{ $student->index_number }} · {{ $student->user->email }}</p>
            </div>
            <div>
                <span class="badge {{ $student->user->is_active ? '' : 'warning' }}">{{ $student->user->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </header>

        <section class="stat-grid">
            <div class="box">
                <span class="label">Registered Courses</span>
                <span class="value">{{ $student->registrations->count() }}</span>
            </div>
            <div class="box">
                <span class="label">Results Recorded</span>
                <span class="value">{{ $student->grades->count() }}</span>
            </div>
            <div class="box">
                <span class="label">Fees Paid</span>
                <span class="value">{{ $student->paymentPlan ? $student->paymentPlan->currency.' '.number_format($student->paymentPlan->total_deposited, 2) : '—' }}</span>
            </div>
        </section>

        <section>
            <h2>Profile Information</h2>
            <div class="grid">
                @foreach ([
                    'Programme' => $student->programme?->name,
                    'Cohort' => $student->cohort?->name,
                    'First Specialization' => $student->firstSpecialization?->name,
                    'Second Specialization' => $student->secondSpecialization?->name,
                    'Phone' => $student->phone,
                    'Gender' => $student->gender,
                    'Date of Birth' => $student->date_of_birth?->format('M j, Y'),
                    'Country' => $student->country,
                    'Location' => $student->location,
                    'Region' => $student->region,
                    'Religion' => $student->religion,
                ] as $label => $value)
                    <div class="box">
                        <span class="label">{{ $label }}</span>
                        <span class="value">{{ $value ?: '—' }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section>
            <h2>Registered Courses</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->registrations as $registration)
                        <tr>
                            <td>{{ $registration->course?->code }} — {{ $registration->course?->title }}</td>
                            <td>{{ $registration->specialization?->name ?? 'Core course' }}</td>
                            <td>{{ ucfirst($registration->status) }}</td>
                            <td>{{ $registration->is_paid ? 'Paid' : 'Unpaid' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No registered courses yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section>
            <h2>Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Assessment</th>
                        <th>Score</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->grades as $grade)
                        <tr>
                            <td>{{ $grade->course?->code }} — {{ $grade->course?->title }}</td>
                            <td>{{ ucfirst($grade->type) }} · {{ $grade->title }}</td>
                            <td>{{ $grade->score ?? '—' }}/{{ $grade->max_score }}</td>
                            <td>{{ $grade->remarks ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No results recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        @if ($student->paymentPlan)
            <section>
                <h2>Payment Summary</h2>
                <div class="grid">
                    <div class="box">
                        <span class="label">Total Fees</span>
                        <span class="value">{{ $student->paymentPlan->currency }} {{ number_format($student->paymentPlan->total_fees, 2) }}</span>
                    </div>
                    <div class="box">
                        <span class="label">Outstanding</span>
                        <span class="value">{{ $student->paymentPlan->currency }} {{ number_format($student->paymentPlan->outstanding(), 2) }}</span>
                    </div>
                </div>
            </section>
        @endif
    </main>

    @if (in_array($mode, ['print', 'pdf'], true))
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
</body>
</html>
