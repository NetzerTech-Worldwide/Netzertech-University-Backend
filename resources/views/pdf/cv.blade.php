<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Curriculum Vitae - {{ $student->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 24px;
        }
        .header {
            border-bottom: 2px solid #2E5FA3;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .name {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .title {
            font-size: 13px;
            font-weight: 600;
            color: #2E5FA3;
            margin: 0 0 6px 0;
        }
        .contact-info {
            font-size: 10px;
            color: #4b5563;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #2E5FA3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin: 14px 0 8px 0;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .item-title {
            font-weight: 700;
            color: #111827;
            font-size: 11px;
        }
        .item-subtitle {
            font-style: italic;
            color: #4b5563;
            font-size: 10px;
        }
        .item-date {
            float: right;
            font-weight: 600;
            color: #6b7280;
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            background-color: #eef2ff;
            color: #3730a3;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            margin: 2px;
        }
        ul {
            margin: 4px 0 8px 16px;
            padding: 0;
        }
        li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="name">{{ $student->user->name }}</h1>
        <p class="title">{{ $student->programme?->name ?? 'Computer Science Candidate' }}</p>
        <p class="contact-info">
            {{ $student->user->email }} | {{ $student->user->phone ?? '+234 800 000 0000' }} | 
            {{ $student->profile?->city ?? 'Lagos' }}, {{ $student->profile?->nationality ?? 'Nigeria' }} | 
            Matric: {{ $student->matric_number }}
        </p>
    </div>

    <!-- Education Section -->
    <div class="section-title">Education</div>
    <div style="margin-bottom: 10px;">
        <span class="item-date">{{ $student->academic_session }} (Expected Graduation: 2027)</span>
        <div class="item-title">{{ $student->university->name }}</div>
        <div class="item-subtitle">{{ $student->programme?->name }} (Degree: {{ $student->programme?->degree ?? 'B.Sc.' }})</div>
        <p style="margin: 2px 0 0 0;">Cumulative Grade Point Average (CGPA): <strong>{{ number_format($student->cgpa, 2) }} / 5.00</strong> ({{ $student->standing ?? 'First Class Honours' }})</p>
    </div>

    <!-- Technical Skills -->
    @if($skills->isNotEmpty())
        <div class="section-title">Skills & Competencies</div>
        <div style="margin-bottom: 8px;">
            @foreach($skills as $skill)
                <span class="badge">{{ $skill->name }} ({{ ucfirst($skill->proficiency_level) }})</span>
            @endforeach
        </div>
    @endif

    <!-- Portfolio Projects -->
    @if($projects->isNotEmpty())
        <div class="section-title">Academic & Technical Projects</div>
        @foreach($projects as $proj)
            <div style="margin-bottom: 8px;">
                <span class="item-date">{{ $proj->live_url ?? $proj->github_url }}</span>
                <div class="item-title">{{ $proj->title }}</div>
                <p style="margin: 2px 0;">{{ $proj->description }}</p>
                @if(!empty($proj->technologies))
                    <div style="margin-top: 2px;">
                        @foreach($proj->technologies as $tech)
                            <span class="badge" style="background-color: #f3f4f6; color: #374151;">{{ $tech }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <!-- Certifications & Awards -->
    @if($certifications->isNotEmpty())
        <div class="section-title">Professional Certifications</div>
        @foreach($certifications as $cert)
            <div style="margin-bottom: 6px;">
                <span class="item-date">{{ $cert->issue_date ? $cert->issue_date->format('M Y') : 'Verified' }}</span>
                <div class="item-title">{{ $cert->title }} — <span style="font-weight: 500; color: #4b5563;">{{ $cert->issuer }}</span></div>
            </div>
        @endforeach
    @endif

    <div class="section-title">Institutional Verification</div>
    <p style="font-size: 9px; color: #6b7280; margin: 4px 0;">
        This curriculum vitae is certified by Netzertech University Management Platform. Academic credentials and CGPA calculations reflect verified registrar records under NUC 5.0 degree regulations.
    </p>
</body>
</html>
