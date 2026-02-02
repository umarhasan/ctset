<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cv->title }} - {{ $cv->profile->full_name ?? 'CV' }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Source Sans Pro', sans-serif; line-height: 1.6; color: #2c3e50; background: #f9f7f3; }

        .cv-container { max-width: 210mm; min-height: 297mm; margin: 0 auto; background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1); border: 1px solid #d4c5a3; position: relative; }

        /* Buttons UI */
        .cv-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .cv-buttons a, .cv-buttons button {
            padding: 10px 18px;
            background: #34495e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background 0.2s;
        }
        .cv-buttons a:hover, .cv-buttons button:hover {
            background: #27ae60;
        }

        /* Header */
        .academic-header { background: #2c3e50; color: white; padding: 30px 40px; border-bottom: 5px solid #27ae60; position: relative; }
        .academic-header h1 { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 600; margin-bottom: 5px; }
        .academic-header .title { font-size: 1.3rem; color: #27ae60; font-weight: 500; margin-bottom: 20px; }
        .contact-bar { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 15px; font-size: 0.9rem; color: #ecf0f1; }
        .contact-bar a, .contact-bar span { color: #ecf0f1; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .contact-bar a:hover { color: #27ae60; }

        /* Body */
        .cv-body { padding: 40px; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
        .main-column { border-right: 1px solid #eaeaea; padding-right: 40px; }
        .sidebar { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #27ae60; }

        /* Sections */
        .section { margin-bottom: 30px; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 600; color: #2c3e50; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #27ae60; position: relative; }
        .section-title::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 60px; height: 2px; background: #2c3e50; }

        .timeline { position: relative; padding-left: 30px; }
        .timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #27ae60; }
        .timeline-item { position: relative; margin-bottom: 25px; }
        .timeline-item::before { content: ''; position: absolute; left: -38px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: #27ae60; border: 3px solid white; box-shadow: 0 0 0 3px #27ae60; }
        .timeline-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .timeline-title { font-weight: 600; color: #2c3e50; font-size: 1.1rem; }
        .timeline-period { color: #27ae60; font-weight: 500; font-size: 0.9rem; }
        .timeline-subtitle { color: #7f8c8d; font-style: italic; margin-bottom: 8px; }
        .timeline-details { color: #34495e; font-size: 0.95rem; }

        /* Publications */
        .publication-item { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .publication-item:last-child { border-bottom: none; }
        .pub-title { font-weight: 600; color: #2c3e50; margin-bottom: 5px; }
        .pub-meta { color: #7f8c8d; font-size: 0.9rem; font-style: italic; }

        /* Skills & Awards */
        .skills-grid { display: grid; gap: 10px; }
        .skill-category { margin-bottom: 15px; }
        .skill-category h4 { font-size: 1rem; color: #2c3e50; margin-bottom: 8px; font-weight: 600; }
        .skill-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .skill-tag { background: #e8f6f3; color: #27ae60; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; border: 1px solid #a3e4d7; }
        .award-item { background: white; padding: 12px; border-radius: 6px; margin-bottom: 10px; border-left: 3px solid #27ae60; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .award-title { font-weight: 600; color: #2c3e50; margin-bottom: 3px; }
        .award-year { color: #27ae60; font-size: 0.9rem; font-weight: 500; }

        /* Summary box */
        .summary-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #eaeaea; margin-bottom: 30px; font-size: 1.05rem; line-height: 1.7; color: #2c3e50; }

        /* Document preview */
        .document-preview { margin-top: 10px; }
        .document-preview img { max-width: 180px; max-height: 120px; border-radius: 5px; margin-bottom:5px; display:block; }
        .document-preview a { display:block; color:#27ae60; margin-bottom:5px; text-decoration:none; }
        .document-preview a:hover { text-decoration:underline; }

        /* Footer */
        .cv-footer { padding: 20px 40px; text-align: center; color: #7f8c8d; font-size: 0.9rem; border-top: 1px solid #eaeaea; background: #f8f9fa; font-style: italic; }

        /* Print/PDF */
        @media print {
            body { background: white; }
            .cv-container { box-shadow: none; margin: 0; max-width: 100%; border: none; }
            .cv-footer, .cv-buttons { display: none; }
            @page { margin: 0; size: A4; }
        }
        .pdf-mode { font-size: 10pt; }
        .pdf-mode .cv-body { padding: 30px; gap: 25px; }
        .pdf-mode .section { margin-bottom: 20px; }
    </style>
</head>
<body class="{{ request()->routeIs('cv.pdf') ? 'pdf-mode' : '' }}">
    @if(!request()->routeIs('cv.pdf'))
    <div class="cv-buttons">
        <!-- Back Button -->
        <a href="{{ route('cv.dashboard') }}" 
           onclick="if(history.length > 1){history.back(); return false;}">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <!-- Print Button -->
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Print / Download
        </button>
    </div>
    @endif

    <div class="cv-container">
        <!-- Header -->
        <div class="academic-header">
            <h1>{{ $cv->profile->full_name ?? 'Your Name' }}</h1>
            <div class="title">{{ $cv->primary_speciality ?? 'Medical Researcher & Academic' }}</div>
            <div class="contact-bar">
                @if($cv->profile && $cv->profile->email)
                    <a href="mailto:{{ $cv->profile->email }}"><i class="fas fa-envelope"></i>{{ $cv->profile->email }}</a>
                @endif
                @if($cv->profile && $cv->profile->phone)
                    <a href="tel:{{ $cv->profile->phone }}"><i class="fas fa-phone"></i>{{ $cv->profile->phone }}</a>
                @endif
                @if($cv->profile && $cv->profile->class_year)
                    <span><i class="fas fa-graduation-cap"></i>Class of {{ $cv->profile->class_year }}</span>
                @endif
                @if($cv->profile && $cv->profile->university)
                    <span><i class="fas fa-university"></i>{{ $cv->profile->university }}</span>
                @endif
            </div>
        </div>

        <!-- Body -->
        <div class="cv-body">
            <!-- Main Column -->
            <div class="main-column">
                @if($cv->summary)
                    <div class="summary-box">{{ $cv->summary }}</div>
                @endif

                <!-- Research -->
                @if($cv->researches->count())
                    <div class="section">
                        <h2 class="section-title">Research Experience</h2>
                        <div class="timeline">
                            @foreach($cv->researches as $research)
                                <div class="timeline-item">
                                    <div class="timeline-header">
                                        <div class="timeline-title">{{ $research->title }}</div>
                                        <div class="timeline-period">{{ $research->year }}</div>
                                    </div>
                                    @if($research->details)
                                        <div class="timeline-details">{{ $research->details }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Education -->
                @if($cv->educations->count())
                    <div class="section">
                        <h2 class="section-title">Education</h2>
                        <div class="timeline">
                            @foreach($cv->educations as $edu)
                                <div class="timeline-item">
                                    <div class="timeline-header">
                                        <div class="timeline-title">{{ $edu->title }}</div>
                                        <div class="timeline-period">{{ $edu->year_from }} - {{ $edu->year_to ?? 'Present' }}</div>
                                    </div>
                                    <div class="timeline-subtitle">{{ $edu->institute }}</div>
                                    @if($edu->details)
                                        <div class="timeline-details">{{ $edu->details }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Clinical Experience -->
                @if($cv->clinicals->count())
                    <div class="section">
                        <h2 class="section-title">Clinical Experience</h2>
                        <div class="timeline">
                            @foreach($cv->clinicals as $clinical)
                                <div class="timeline-item">
                                    <div class="timeline-header">
                                        <div class="timeline-title">{{ $clinical->title }}</div>
                                        <div class="timeline-period">{{ $clinical->year_from }} - {{ $clinical->year_to ?? 'Present' }}</div>
                                    </div>
                                    <div class="timeline-subtitle">{{ $clinical->institute }}</div>
                                    @if($clinical->details)
                                        <div class="timeline-details">{{ $clinical->details }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Awards -->
                @if($cv->awards->count())
                    <div class="section">
                        <h2 class="section-title">Awards & Honors</h2>
                        @foreach($cv->awards as $award)
                            <div class="award-item">
                                <div class="award-title">{{ $award->title }}</div>
                                <div class="award-year">{{ $award->year }}</div>
                                @if($award->details)
                                    <div style="font-size:0.9rem;color:#7f8c8d;margin-top:5px;">{{ $award->details }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Documents -->
                @if($cv->documents->count())
                    <div class="section">
                        <h2 class="section-title">Documents</h2>
                        <div class="document-preview">
                            @foreach($cv->documents as $doc)
                                @php $ext = pathinfo($doc->file, PATHINFO_EXTENSION); @endphp
                                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                                    <img src="{{ route('cv.document.stream', $doc->file) }}" alt="{{ $doc->title }}">
                                @elseif(strtolower($ext) === 'pdf')
                                    <a href="{{ route('cv.document.stream', $doc->file) }}" target="_blank"><i class="fas fa-file-pdf"></i> {{ $doc->title }}</a>
                                @else
                                    <a href="{{ route('cv.document.stream', $doc->file) }}" target="_blank"><i class="fas fa-file"></i> {{ $doc->title }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="cv-footer">
            <p>Academic CV • {{ $cv->profile->full_name ?? 'Professional' }} • Last Updated: {{ date('F j, Y') }}</p>
            @if($cv->is_public)
                <p style="font-size:0.8rem;color:#95a5a6;">Reference: {{ substr($cv->share_token,0,8) }}</p>
            @endif
        </div>
    </div>
</body>
</html>
