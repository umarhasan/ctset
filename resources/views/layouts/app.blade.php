<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Faculty of Medicine</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard. Fully accessible with WCAG 2.1 AA compliance."
  />
  <link rel="preload" href="{{ asset('adminlte/css/adminlte.css') }}" as="style" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    crossorigin="anonymous"
    media="print"
    onload="this.media='all'"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous"
  />
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    crossorigin="anonymous"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
    crossorigin="anonymous"
  />
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
</head>


<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div class="app-wrapper">

    @include('layouts.header')
    @include('layouts.sidebar')
    <main class="app-main">
    <div class="app-content">
          <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    </main>
    @include('layouts.footer')
  </div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('adminlte/js/adminlte.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>




<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: "3000"
};
</script>
@stack('scripts')
<script>
$(function () {
    // Existing examTable
    $(document).ready(function () {
    $('#examTable').DataTable({
        responsive: true,
        scrollX: true,
        autoWidth: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        columnDefs: [
            { targets: -1, orderable: true } // last column (action/signature) no ordering
        ]
    });

});

    $('[data-bs-toggle="tooltip"]').tooltip();


});
</script>
<script type="text/javascript" src="https://sellfy.com/js/api_buttons.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/anypicker@latest/dist/anypicker-all.min.css" />
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/anypicker@latest/dist/anypicker.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/anypicker@latest/dist/i18n/anypicker-i18n.js"></script>
</body>
</html>
