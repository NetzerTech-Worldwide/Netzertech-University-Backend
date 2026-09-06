<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Academic Transcript - {{ $student->matric_number }}</title>
    <style>
        @page { margin: 25px 35px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10pt; color: #1e293b; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid {{ $university->primary_color ?? '#1e3a8a' }}; padding-bottom: 8px; margin-bottom: 12px; }
        .uni-name { font-size: 16pt; font-weight: bold; color: {{ $university->primary_color ?? '#1e3a8a' }}; text-transform: uppercase; margin: 0; }
        .uni-sub { font-size: 9pt; color: #64748b; margin: 2px 0 0 0; }
        .title { font-size: 12pt; font-weight: bold; margin: 6px 0; text-transform: uppercase; letter-spacing: 1px; color: #0f172a; }
        
        .student-info { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        .student-info td { padding: 3px 6px; }
        .label { font-weight: bold; color: #475569; width: 18%; }
        
        .semester-block { margin-bottom: 14px; }
        .semester-header { background-color: #f1f5f9; padding: 4px 8px; font-weight: bold; font-size: 9.5pt; border-left: 3px solid {{ $university->primary_color ?? '#1e3a8a' }}; }
        
        table.results-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 8.5pt; }
        table.results-table th { background-color: #1e293b; color: #fff; padding: 4px 6px; text-align: left; font-weight: bold; }
        table.results-table td { padding: 4px 6px; border-bottom: 1px solid #e2e8f0; }
        table.results-table tr:nth-child(even) { background-color: #f8fafc; }
        
        .summary-box { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 10px; margin-top: 6px; font-size: 8.5pt; }
        .summary-box table { width: 100%; }
        
        .footer { margin-top: 25px; border-top: 1px solid #cbd5e1; padding-top: 8px; font-size: 8pt; color: #64748b; text-align: center; }
        .sig-section { margin-top: 25px; display: table; width: 100%; }
        .sig-col { display: table-cell; width: 50%; text-align: center; }
        .sig-line { border-top: 1px solid #475569; width: 60%; margin: 30px auto 4px auto; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="uni-name">{{ $university->name }}</h1>
        <p class="uni-sub">{{ $university->address ?? 'Main University Campus' }} &bull; {{ $university->contact_email ?? '' }}</p>
        <div class="title">Official Academic Transcript</div>
    </div>

    <table class="student-info">
        <tr>
            <td class="label">Student Name:</td>
            <td><strong>{{ $student->user->name }}</strong></td>
            <td class="label">Matric Number:</td>
            <td><strong>{{ $student->matric_number }}</strong></td>
        </tr>
        <tr>
            <td class="label">Faculty:</td>
            <td>{{ $student->faculty->name }}</td>
            <td class="label">Department:</td>
            <td>{{ $student->department->name }}</td>
        </tr>
        <tr>
            <td class="label">Programme:</td>
            <td>{{ $student->programme->name }}</td>
            <td class="label">Current Level:</td>
            <td>{{ $student->level }}</td>
        </tr>
        <tr>
            <td class="label">Cumulative CGPA:</td>
            <td><strong style="color: {{ $university->primary_color ?? '#1e3a8a' }}; font-size: 11pt;">{{ number_format($student->cgpa, 2) }}</strong></td>
            <td class="label">Class Standing:</td>
            <td><strong>{{ $student->standing }}</strong></td>
        </tr>
    </table>

    @foreach ($semesters as $sem)
        <div class="semester-block">
            <div class="semester-header">
                {{ $sem['session'] }} &bull; {{ $sem['semester'] }} ({{ $sem['level'] }})
            </div>

            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Course Code</th>
                        <th>Course Title</th>
                        <th style="width: 8%; text-align: center;">Units</th>
                        <th style="width: 10%; text-align: center;">Score</th>
                        <th style="width: 8%; text-align: center;">Grade</th>
                        <th style="width: 8%; text-align: center;">GP</th>
                        <th style="width: 8%; text-align: center;">CP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sem['courses'] as $res)
                        <tr>
                            <td><strong>{{ $res->course->code }}</strong></td>
                            <td>{{ $res->course->title }}</td>
                            <td style="text-align: center;">{{ $res->course->credit_units }}</td>
                            <td style="text-align: center;">{{ number_format($res->total_score, 1) }}</td>
                            <td style="text-align: center;"><strong>{{ $res->grade }}</strong></td>
                            <td style="text-align: center;">{{ number_format($res->grade_point, 1) }}</td>
                            <td style="text-align: center;">{{ number_format($res->credit_points, 1) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-box">
                <table>
                    <tr>
                        <td><strong>Credits Registered:</strong> {{ $sem['total_credits'] }}</td>
                        <td><strong>Credits Earned:</strong> {{ $sem['credits_earned'] }}</td>
                        <td><strong>Semester GPA:</strong> <strong style="color: #1e3a8a;">{{ number_format($sem['gpa'], 2) }}</strong></td>
                        <td><strong>Cumulative CGPA:</strong> <strong style="color: #047857;">{{ number_format($sem['cgpa'], 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

    <div class="sig-section">
        <div class="sig-col">
            <div class="sig-line"></div>
            <strong>Examinations Officer</strong>
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <strong>Registrar / Academic Affairs</strong>
        </div>
    </div>

    <div class="footer">
        Generated on {{ $generated_at }} &bull; Official Document Issued by {{ $university->name }} &bull; Tamper-Proof Digital ID: {{ substr($student->digital_id_token, 0, 16) }}...
    </div>

</body>
</html>
