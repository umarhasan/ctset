<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cv->title }} - {{ $cv->profile->full_name ?? 'CV' }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; background: #f5f7fa; }
        .cv-container { max-width: 210mm; min-height: 297mm; margin: 20px auto; background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1); }

        /* Header */
        .cv-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; }
        .profile-section { display: flex; align-items: center; gap: 30px; }
        .profile-image { width: 120px; height: 120px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 48px; color: #667eea; overflow: hidden; }
        .profile-image img { width: 100%; height: 100%; object-fit: cover; }
        .profile-info h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 5px; }
        .specialty { font-size: 1.1rem; opacity: 0.9; margin-bottom: 10px; }
        .contact-info { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; }
        .contact-item { display: flex; align-items: center; gap: 5px; font-size: 0.9rem; }
        .contact-item i { width: 16px; }

        /* Content */
        .cv-content { padding: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 1.2rem; font-weight: 600; color: #667eea; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 2px solid #667eea; text-transform: uppercase; letter-spacing: 1px; }
        .summary { grid-column: 1 / -1; background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; }
        .entry { margin-bottom: 20px; }
        .entry-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .entry-title { font-weight: 600; color: #2d3748; }
        .entry-subtitle { color: #667eea; font-weight: 500; }
        .entry-period { color: #718096; font-size: 0.9rem; }
        .entry-details { color: #4a5568; font-size: 0.95rem; margin-top: 5px; }
        .entry-details img { max-width: 200px; max-height: 150px; border-radius: 5px; margin-top:5px; }

        /* Skills */
        .skills-list { list-style: none; padding-left: 0; }
        .skill-item { margin-bottom: 8px; display: flex; align-items: center; }
        .skill-name { width: 120px; font-weight: 500; }
        .skill-level { flex: 1; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
        .skill-fill { height: 100%; background: #667eea; border-radius: 3px; }

        /* Footer */
        .cv-footer { padding: 20px 40px; text-align: center; color: #718096; font-size: 0.9rem; border-top: 1px solid #e2e8f0; background: #f8f9fa; }

        /* Buttons top bar */
        .cv-buttons { position: sticky; top: 0; z-index: 1000; display: flex; gap: 10px; padding: 10px 20px; background: #f8f9fa; border-bottom: 1px solid #e2e8f0; }

        .cv-buttons a, .cv-buttons button { padding:10px 20px; color:white; border:none; border-radius:5px; text-decoration: none; cursor:pointer; display:flex; align-items:center; gap:5px; }

        .btn-back { background: #6c757d; }
        .btn-print { background: #667eea; }

        /* Print styles */
        @media print {
            body { background: white; }
            .cv-container { box-shadow: none; margin: 0; max-width: 100%; }
            .cv-footer, .cv-buttons { display: none; }
            @page { margin: 0; size: A4; }
        }
    </style>
</head>
<body class="{{ request()->routeIs('cv.pdf') ? 'pdf-mode' : '' }}">

    @if(!request()->routeIs('cv.pdf'))
    <div class="cv-buttons">
        <!-- Back Button -->
        <a href="{{ route('cv.dashboard') }}" 
           onclick="if(history.length > 1){history.back(); return false;}" 
           class="btn-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <!-- Print Button -->
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Print / Download
        </button>
    </div>
    @endif

    <div class="cv-container">
        <!-- Header -->
        <div class="cv-header">
            <div class="profile-section">
                <div class="profile-image">
                    @if($cv->profile && $cv->profile->profile_image)
                        <img src="{{ $cv->user->profile_image ? route('user.profile.stream', $cv->user->profile_image) : asset('adminlte/assets/img/avatar.png') }}" alt="Profile Image">
                    @else
                        {{ strtoupper(substr($cv->profile->full_name ?? 'CV', 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h1>{{ $cv->profile->full_name ?? 'Your Name' }}</h1>
                    <div class="specialty">{{ $cv->primary_speciality ?? 'Medical Professional' }}</div>
                    <div class="contact-info">
                        @if($cv->profile && $cv->profile->email)<div class="contact-item"><i class="fas fa-envelope"></i>{{ $cv->profile->email }}</div>@endif
                        @if($cv->profile && $cv->profile->phone)<div class="contact-item"><i class="fas fa-phone"></i>{{ $cv->profile->phone }}</div>@endif
                        @if($cv->profile && $cv->profile->university)<div class="contact-item"><i class="fas fa-university"></i>{{ $cv->profile->university }}</div>@endif
                        @if($cv->profile && $cv->profile->class_year)<div class="contact-item"><i class="fas fa-graduation-cap"></i>Class of {{ $cv->profile->class_year }}</div>@endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="cv-content">
            <!-- Summary -->
            @if($cv->summary)
            <div class="summary section">
                <h2 class="section-title">Professional Summary</h2>
                <p>{{ $cv->summary }}</p>
            </div>
            @endif

            <!-- Left Column -->
            <div class="left-column">
                <!-- Education -->
                @if($cv->educations->count() > 0)
                <div class="section">
                    <h2 class="section-title">Education</h2>
                    @foreach($cv->educations as $edu)
                        <div class="entry">
                            <div class="entry-header">
                                <div class="entry-title">{{ $edu->title }}</div>
                                <div class="entry-period">{{ $edu->year_from }} - {{ $edu->year_to ?? 'Present' }}</div>
                            </div>
                            <div class="entry-subtitle">{{ $edu->institute }}</div>
                            @if($edu->details)<div class="entry-details">{{ $edu->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Clinical Experience -->
                @if($cv->clinicals->count() > 0)
                <div class="section">
                    <h2 class="section-title">Clinical Experience</h2>
                    @foreach($cv->clinicals as $clinical)
                        <div class="entry">
                            <div class="entry-header">
                                <div class="entry-title">{{ $clinical->title }}</div>
                                <div class="entry-period">{{ $clinical->year_from }} - {{ $clinical->year_to ?? 'Present' }}</div>
                            </div>
                            <div class="entry-subtitle">{{ $clinical->institute }}</div>
                            @if($clinical->details)<div class="entry-details">{{ $clinical->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Research -->
                @if($cv->researches->count() > 0)
                <div class="section">
                    <h2 class="section-title">Research & Publications</h2>
                    @foreach($cv->researches as $research)
                        <div class="entry">
                            <div class="entry-header">
                                <div class="entry-title">{{ $research->title }}</div>
                                <div class="entry-period">{{ $research->year }}</div>
                            </div>
                            @if($research->details)<div class="entry-details">{{ $research->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Awards -->
                @if($cv->awards->count() > 0)
                <div class="section">
                    <h2 class="section-title">Awards & Honors</h2>
                    @foreach($cv->awards as $award)
                        <div class="entry">
                            <div class="entry-header">
                                <div class="entry-title">{{ $award->title }}</div>
                                <div class="entry-period">{{ $award->year }}</div>
                            </div>
                            @if($award->details)<div class="entry-details">{{ $award->details }}</div>@endif
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Skills -->
                <div class="section">
                    <h2 class="section-title">Professional Skills</h2>
                    <ul class="skills-list">
                        <li class="skill-item"><div class="skill-name">Clinical Skills</div><div class="skill-level"><div class="skill-fill" style="width:90%"></div></div></li>
                        <li class="skill-item"><div class="skill-name">Patient Care</div><div class="skill-level"><div class="skill-fill" style="width:95%"></div></div></li>
                        <li class="skill-item"><div class="skill-name">Medical Research</div><div class="skill-level"><div class="skill-fill" style="width:85%"></div></div></li>
                        <li class="skill-item"><div class="skill-name">Team Collaboration</div><div class="skill-level"><div class="skill-fill" style="width:92%"></div></div></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="cv-footer">
            <p>Generated by CV Builder Pro • {{ date('F j, Y') }}</p>
            @if($cv->is_public)
                <p style="font-size:0.8rem; color:#a0aec0;">Public CV • Reference ID: {{ substr($cv->share_token,0,8) }}</p>
            @endif
        </div>
    </div>

    <script>
        @if(request()->routeIs('cv.pdf'))
            window.print();
        @endif
    </script>
</body>
</html>
