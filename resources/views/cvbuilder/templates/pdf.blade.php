<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $cv->title }} - {{ $cv->profile->full_name ?? 'CV' }}</title>
<style>
/* ... existing styles ... */
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
    @if($cv->profile && $cv->profile->profile_image)
        @php
            // Profile image ko absolute path mein lein
            $profileImagePath = storage_path('app/public/' . $cv->profile->profile_image);
        @endphp
        @if(file_exists($profileImagePath))
            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($profileImagePath)) }}" 
                 alt="Profile Image" style="width:100px;height:100px;border-radius:50%;">
        @else
            {{ strtoupper(substr($cv->profile->full_name ?? 'CV', 0, 1)) }}
        @endif
    @else
        {{ strtoupper(substr($cv->profile->full_name ?? 'CV', 0, 1)) }}
    @endif
</div>
</td>
<td>
<div class="profile-info">
<h1>{{ $cv->profile->full_name ?? 'Your Name' }}</h1>
<div class="specialty">{{ $cv->primary_speciality ?? 'Medical Professional' }}</div>
<div class="contact-info">
@if($cv->profile && $cv->profile->email)
<div class="contact-item">
<!-- Base64 encoded icon -->
<img src="{{ $icons['email'] ?? '' }}" style="width:12px;height:12px;vertical-align:middle;"> 
{{ $cv->profile->email }}
</div>
@endif
@if($cv->profile && $cv->profile->phone)
<div class="contact-item">
<img src="{{ $icons['phone'] ?? '' }}" style="width:12px;height:12px;vertical-align:middle;"> 
{{ $cv->profile->phone }}
</div>
@endif
@if($cv->profile && $cv->profile->university)
<div class="contact-item">
<img src="{{ $icons['university'] ?? '' }}" style="width:12px;height:12px;vertical-align:middle;"> 
{{ $cv->profile->university }}
</div>
@endif
@if($cv->profile && $cv->profile->class_year)
<div class="contact-item">
<img src="{{ $icons['graduation'] ?? '' }}" style="width:12px;height:12px;vertical-align:middle;"> 
Class of {{ $cv->profile->class_year }}
</div>
@endif
</div>
</div>
</td>
</tr>
</table>
</div>

<!-- ... rest of the template ... -->

</div>
</body>
</html>