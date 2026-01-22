@extends('layouts.app')

@section('title', 'Public Profile')

<style>
* {box-sizing: border-box;}
ul {list-style-type: none;}
body {font-family: Verdana, sans-serif;}
/* Bio Profile*/
.profile-star-rating ul li {
    display: inline-block;
    margin-right: 4px;
}
.profile-star-rating ul {
    padding: 0;
}
.bio-profile-pg-tab {
  overflow: hidden;
  border: none;
  background-color: transparent;
  position: relative;
  top: 2px;
}
.bio-profile-pg-tab button {
    background-color: #f9fbfc;
    float: left;
    border: solid 2px;
    outline: none;
    border-bottom: none;
    font-family: 'Fontfabric---Mont-SemiBold';
    cursor: pointer;
    padding: 10px 35px;
    transition: 0.3s;
    font-size: 18px;
}
.bio-profile-pg-tab button.active {
    border-bottom: none;
    color: #223433 !important;
}
.bio-profile-pg-tab-2 {
   margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ed8f15 !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe3bc !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-3 {
    margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ef2d2d !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe1e1 !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-4 {
    margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ef2d2d !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe1e1 !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-5 {
    margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ef2d2d !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe1e1 !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-6 {
    margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ef2d2d !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe1e1 !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-7 {
    margin-left: 2% !important;
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #ef2d2d !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #ffe1e1 !important;
    color: #809594 !important;
}
.bio-profile-pg-tab-1 {
    margin-top: 3px;
    border-top-left-radius: 12px;
    border-color: #53babc !important;
    border-top-right-radius: 12px;
    padding: 9px 40px;
    background-color: #f9fbfc !important;
    color: #809594 !important;
    
}
.bio-profile-pg-profile-left {
    width: 37%;
    float: left;
}
.bio-profile-pg-profile-right {
    width: 63%;
    float: right;
    padding: 25px 10px;
}
.bio-profile-pg-percent-left {
    width: 37%;
    float: left;
}
.bio-profile-pg-percent-right {
    width: 58%;
    float: right;
}
.bio-profile-pg-profile-bio img {
    padding-right: 10px;
    margin-top: -4px;
}
.bio-profile-pg-tab-inner-content {
  padding: 25px 20px 20px 20px;
}
.bio-profile-pg-col-right {
    width: 40%;
    float: right;
    padding: 30px 10px;
    background-color: #3db1b3;
    border-radius: 10px;
}
.bio-profile-pg-col-left {
    width: 60%;
    float: left;
}
.bio-profile-pg-profile-main {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 30px 30px;
    margin: 20px 25px;
}
.bio-profile-pg-profile-main h2 {
    font-family: 'Fontfabric---Mont-SemiBold';
    text-transform: uppercase;
    color: #212121;
    font-size: 28px;
    font-weight: 200;
}
.bio-profile-pg-profile-right h1 {
  font-size: 19px;
    margin-top: 26px;
    color: #212121;
    font-family: 'Fontfabric---Mont-Bold';
}
.bio-profile-pg-profile-right h6 {
    font-family: 'Fontfabric---Mont-Regular';
    color: #909191;
    font-size: 15px;
    margin-bottom: 20px;
}
.bio-profile-pg-percent-left img {
    width: 100%;
}
.bio-profile-pg-profile-left img {
    width: 100%;
}
.bio-profile-pg-profile-bio {
    background-color: #dfeaf0;
    display: inline-block;
    padding: 0px 25px 25px 30px;
    border-radius: 12px;
    border: solid 2px #b2cddc;
    margin-top: 30px;
}
.bio-profile-pg-profile-bio p {
    color: #677b7a;
    font-family: 'Fontfabric---Mont-Light';
    font-size: 13px;
    line-height: 24px;
    margin-bottom: 15px;
}
.bio-profile-pg-profile-bio h4 {
    color: #677b7a;
    font-family: 'Fontfabric---Mont-Regular';
    font-size: 14px;
    line-height: 22px;
}
.bio-profile-pg-profile-bio h3 {
    color: #132625;
    font-family: 'Fontfabric---Mont-SemiBold';
    text-transform: uppercase;
    font-size: 20px;
}
.bio-profile-pg-percent-right h1 {
    color: #ffffff;
    font-family: 'Fontfabric---Mont-SemiBold';
    font-size: 20px;
    margin-top: 0;
}
.bio-profile-pg-percent-right h6 {
    color: #ffffff;
    font-size: 14px;
    font-family: 'Fontfabric---Mont-Regular';
}
.bio-profile-pg-percent-right a {
    color: #3db1b3;
    background-color: #ffffff;
    font-size: 14px;
    font-family: 'Fontfabric---Mont-Regular';
    border-radius: 12px;
    padding: 10px 20px;
    display: inline-block;
    text-decoration: none;
}

.bio-profile-pg-content-tab-2 {
  margin-top: 0px;
    background-color: #ffe3bc !important;
    margin-top: 0px;
  display: none;
    padding: 6px 12px;
    border-radius: 12px;
    border-top-left-radius: 0px !important;
    border: 2px solid  #ed8f15;
}
.bio-profile-pg-content-tab-3 {
  margin-top: 0px;
    background-color: #ffe1e1 !important;
    margin-top: 0px;
  display: none;
    padding: 6px 12px;
    border-radius: 12px;
    border-top-left-radius: 0px !important;
    border: 2px solid  #ef2d2d;
}
.bio-profile-pg-content-tab-1 {
  margin-top: 0px;
  display: none;
    padding: 6px 12px;
    border-radius: 12px;
    background-color: #f9fbfc;
    border-top-left-radius: 0px !important;
    border: 2px solid #3db1b3;
}
.bio-profile-pg-tab-1.active {
    border-bottom: 1px solid #f9fbfc !important;
}
.bio-profile-pg-tab-2.active {
    border-bottom: 1px solid #ffe3bc!important;
}
.bio-profile-pg-tab-3.active {
    border-bottom: 1px solid #ffe1e1 !important;
}
@media screen and (max-width: 768px) {
  .bio-profile-pg-profile-main {
      padding: 10px 20px;
      margin: 0;
  }
  .bio-profile-pg-tab button {
      width: 100%;
      margin: 0 !important;
      margin-bottom: 5px !important;
      border-radius: 12px;
  }
  .bio-profile-pg-tab-1.active {
      border-bottom: 2px solid #53babc !important;
  }
  .bio-profile-pg-tab-1 {
      border-bottom: 2px solid #53babc !important;
  }
  .bio-profile-pg-tab-2.active {
      border-bottom: 2px solid #ed8f15 !important;
  }
  .bio-profile-pg-tab-2 {
      border-bottom: 2px solid #ed8f15 !important;
  }
  .bio-profile-pg-tab-3.active {
      border-bottom: 2px solid #ef2d2d !important;
  }
  .bio-profile-pg-tab-3 {
      border-bottom: 2px solid #ef2d2d !important;
  }
  .bio-profile-pg-profile-main {
      padding: 10px 20px;
  }
  .bio-profile-pg-profile-main .tabcontent {
      border-top-left-radius: 12px !important;
      margin-top: 10px;
  }
  .bio-profile-pg-col-left, .bio-profile-pg-col-right{
      width: 100%;
  }
  .bio-profile-pg-tab-inner-content {
      padding: 25px 10px 25px 10px;
  }
  .bio-profile-pg-profile-right h1 {
      margin-top: 0 !important;
  }
  .bio-profile-pg-profile-main .tabcontent {
      padding: 0;
  }
  .bio-profile-pg-profile-right {
      padding: 10px 10px;
  }
  .bio-profile-pg-profile-main h2 {
      margin-top: 0;
  }
}
/* Bio Profile*/
</style>


@section('content')
<div class="container-fluid">

    {{-- Profile Search Dropdown --}}
    <div class="bio-profile-pg-profile-main mb-4">
        <h2>Search Profile</h2>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Profile :</label>
            <div class="col-sm-10">
                <select class="form-control" onchange="goToProfile(this)">
                    <option value="">Please Select</option>
                    @foreach(\App\Models\User::all() as $u)
                        <option value="{{ $u->id }}" {{ request('uid') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($user)
        {{-- Profile Tabs --}}
        <div class="bio-profile-pg-profile-main">
            <h2>Profile</h2>

            <div class="bio-profile-pg-tab tab mb-3">
                <button class="tablinks bio-profile-pg-tab-1 active" onclick="openCity(event, 'General-1')">General</button>
                @php $i = 2; @endphp
                @foreach($tabs as $tab)
                    <button class="tablinks bio-profile-pg-tab-{{ $i }}" onclick="openCity(event, 'General-{{ $i }}')">
                        {{ $tab->tabname }}
                    </button>
                    @php $i++; @endphp
                @endforeach
            </div>

            {{-- General Tab --}}
            <div id="General-1" class="tabcontent bio-profile-pg-content-tab-1" style="display:block;">
                <div class="bio-profile-pg-tab-inner-content">
                    <div class="bio-profile-pg-col-left">
                        <div class="bio-profile-pg-profile-left">
                            <img src="{{ $user->profile_image ? route('file',$user->profile_image) : asset('adminlte/assets/img/avatar.png') }}" alt="Profile Image">
                        </div>
                        <div class="bio-profile-pg-profile-right">
                            <h1>{{ $user->name ?? 'No Name' }}</h1>
                            <span style="color:red;font-weight:600;">
                                @if($user->hasRole('Admin'))
                                    (Admin)
                                @elseif($user->hasRole('Trainee'))
                                    (Trainee)
                                @elseif($user->hasRole('Assessor'))
                                    (Assessor)
                                @elseif($user->hasRole('Consultant'))
                                    (Consultant)
                                @endif
                            </span>
                            <div class="profile-star-rating">
                                <ul>
                                    @for($s=0;$s<5;$s++)
                                        <li><img src="{{ asset('adminlte/assets/img/profile-star-img.png') }}" alt="Star"></li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bio-profile-pg-col-right">
                        <div class="bio-profile-pg-percent-left">
                            <img src="{{ asset('adminlte/assets/img/bio-profile-percentage-circle.png') }}" alt="Profile Completion">
                        </div>
                        <div class="bio-profile-pg-percent-right">
                            <h1>Profile Completion</h1>
                            <h6>Please complete profile</h6>
                            <a href="{{ route('profile.index') }}">Complete</a>
                        </div>
                    </div>

                    <div class="bio-profile-pg-profile-bio">
                        {!! $user->bio ?? '' !!}
                    </div>
                </div>
            </div>

            {{-- Dynamic Tabs --}}
            @php $k = 2; @endphp
            @foreach($tabs as $tab)
                <div id="General-{{ $k }}" class="tabcontent bio-profile-pg-content-tab-{{ $k }}" style="display:none;">
                    <div class="bio-profile-pg-tab-inner-content">
                        <div class="bio-profile-pg-profile-bio">
                            {!! $tab->tabsdesc !!}
                        </div>
                    </div>
                </div>
                @php $k++; @endphp
            @endforeach
        </div>
    @else
        <div class="bio-profile-pg-profile-main text-center">
            <h3>No profile selected. Please choose a profile from the dropdown above.</h3>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function goToProfile(select) {
    var uid = select.value;
    if(uid) {
        window.location.href = "{{ url('public-profile') }}?uid=" + uid;
    } else {
        window.location.href = "{{ url('public-profile') }}";
    }
}

function openCity(evt, cityName) {
    var tabcontent = document.getElementsByClassName("tabcontent");
    for(var i=0;i<tabcontent.length;i++){ tabcontent[i].style.display="none"; }
    var tablinks = document.getElementsByClassName("tablinks");
    for(var i=0;i<tablinks.length;i++){ tablinks[i].className = tablinks[i].className.replace(" active",""); }
    document.getElementById(cityName).style.display="block";
    evt.currentTarget.className += " active";
}
</script>
@endpush
