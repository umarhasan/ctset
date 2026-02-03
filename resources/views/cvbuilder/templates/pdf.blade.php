<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $cv->title }} - {{ $cv->profile->full_name ?? 'CV' }}</title>

<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: Arial, sans-serif; font-size:12px; line-height:1.4; color:#333; background:#fff; }

    .cv-container { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 0; }

    /* Header */
    .cv-header { background-color: #667eea; color:#fff; padding: 20px; }
    .profile-table { width:100%; }
    .profile-image { width:100px; height:100px; border-radius:50%; background:#fff; text-align:center; vertical-align:middle; font-size:36px; color:#667eea; overflow:hidden; }
    .profile-image img { width:100%; height:100%; object-fit:cover; }
    .profile-info h1 { font-size:22px; margin-bottom:3px; }
    .profile-info .specialty { font-size:14px; opacity:0.9; margin-bottom:5px; }
    .contact-item { font-size:10px; margin-bottom:2px; }

    /* Content Table */
    .cv-content { width:100%; padding:20px; }
    .cv-column { vertical-align: top; padding-right:10px; }
    .section { margin-bottom:15px; page-break-inside: avoid; }
    .section-title { font-size:13px; font-weight:bold; color:#667eea; border-bottom:1px solid #667eea; padding-bottom:3px; margin-bottom:5px; text-transform:uppercase; }
    .summary-box { background:#f8f9fa; padding:8px; border-left:4px solid #667eea; margin-bottom:10px; }
    .entry { margin-bottom:5px; }
    .entry-title { font-weight:bold; color:#2d3748; }
    .entry-subtitle { font-weight:bold; color:#667eea; }
    .entry-period { font-size:10px; color:#718096; }
    .entry-details { font-size:10px; color:#4a5568; margin-top:2px; }
    .entry-details img { max-width:150px; max-height:100px; margin-top:2px; }

    /* Skills */
    .skills-list { list-style:none; padding:0; }
    .skill-item { margin-bottom:3px; }
    .skill-name { display:inline-block; width:90px; }
    .skill-bar { display:inline-block; width:60%; height:6px; background:#e2e8f0; vertical-align:middle; }
    .skill-fill { height:100%; background:#667eea; }

    /* Footer */
    .cv-footer { text-align:center; font-size:10px; color:#718096; margin-top:10px; }

    /* Page Breaks */
    .page-break { page-break-after: always; }
</style>
</head>
<body>

<div class="cv-container">
    <!-- Header -->
    <div class="cv-header">
        <table class="profile-table">
            <tr>
                <td width="110">
                    <div class="profile-image">
                        @if(isset($cv->profile) && isset($cv->profile->profile_image) && $cv->profile->profile_image)
                            <img src="{{ public_path('storage/' . $cv->profile->profile_image) }}" alt="Profile Image">
                        @else
                            {{ strtoupper(substr($cv->profile->full_name ?? 'CV',0,1)) }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="profile-info">
                        <h1>{{ $cv->profile->full_name ?? 'Your Name' }}</h1>
                        <div class="specialty">{{ $cv->primary_speciality ?? 'Medical Professional' }}</div>
                        @if(isset($cv->profile))
                        <div>
                            @if($cv->profile->email)<div class="contact-item">✉ {{ $cv->profile->email }}</div>@endif
                            @if($cv->profile->phone)<div class="contact-item">☎ {{ $cv->profile->phone }}</div>@endif
                            @if($cv->profile->university)<div class="contact-item">🏛 {{ $cv->profile->university }}</div>@endif
                            @if($cv->profile->class_year)<div class="contact-item">🎓 Class of {{ $cv->profile->class_year }}</div>@endif
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Content -->
    <table class="cv-content">
        <tr>
            <!-- Left Column -->
            <td class="cv-column" width="50%">
                @if($cv->summary)
                    <div class="section summary-box">
                        <strong>Professional Summary:</strong>
                        <p>{{ $cv->summary }}</p>
                    </div>
                @endif

                @if($cv->educations->count())
                <div class="section">
                    <div class="section-title">Education</div>
                    @foreach($cv->educations as $edu)
                        <div class="entry">
                            <div class="entry-title">{{ $edu->title }}</div>
                            <div class="entry-subtitle">{{ $edu->institute }}</div>
                            <div class="entry-period">{{ $edu->year_from }} - {{ $edu->year_to ?? 'Present' }}</div>
                            @if($edu->details)<div class="entry-details">{{ $edu->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                @if($cv->clinicals->count())
                <div class="section">
                    <div class="section-title">Clinical Experience</div>
                    @foreach($cv->clinicals as $clinical)
                        <div class="entry">
                            <div class="entry-title">{{ $clinical->title }}</div>
                            <div class="entry-subtitle">{{ $clinical->institute }}</div>
                            <div class="entry-period">{{ $clinical->year_from }} - {{ $clinical->year_to ?? 'Present' }}</div>
                            @if($clinical->details)<div class="entry-details">{{ $clinical->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif
            </td>

            <!-- Right Column -->
            <td class="cv-column" width="50%">
                @if($cv->researches->count())
                <div class="section">
                    <div class="section-title">Research & Publications</div>
                    @foreach($cv->researches as $research)
                        <div class="entry">
                            <div class="entry-title">{{ $research->title }}</div>
                            <div class="entry-period">{{ $research->year }}</div>
                            @if($research->details)<div class="entry-details">{{ $research->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                @if($cv->awards->count())
                <div class="section">
                    <div class="section-title">Awards & Honors</div>
                    @foreach($cv->awards as $award)
                        <div class="entry">
                            <div class="entry-title">{{ $award->title }}</div>
                            <div class="entry-period">{{ $award->year }}</div>
                            @if($award->details)<div class="entry-details">{{ $award->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="section">
                    <div class="section-title">Professional Skills</div>
                    <ul class="skills-list">
                        <li class="skill-item"><span class="skill-name">Clinical Skills</span><span class="skill-bar"><span class="skill-fill" style="width:90%"></span></span></li>
                        <li class="skill-item"><span class="skill-name">Patient Care</span><span class="skill-bar"><span class="skill-fill" style="width:95%"></span></span></li>
                        <li class="skill-item"><span class="skill-name">Medical Research</span><span class="skill-bar"><span class="skill-fill" style="width:85%"></span></span></li>
                        <li class="skill-item"><span class="skill-name">Team Collaboration</span><span class="skill-bar"><span class="skill-fill" style="width:92%"></span></span></li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="cv-footer">
        <p>Generated by CV Builder Pro • {{ date('F j, Y') }}</p>
        @if($cv->is_public)
            <p>Public CV • Reference ID: {{ substr($cv->share_token,0,8) }}</p>
        @endif
    </div>

</div>
</body>
</html>
