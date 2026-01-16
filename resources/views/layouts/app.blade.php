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
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
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
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        $('#examTable').DataTable({
            scrollX: true,
            autoWidth: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthChange: true,

            dom:
                "<'row mb-2'<'col-md-6'l><'col-md-6 text-end'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row mt-2'<'col-md-5'i><'col-md-7 text-end'p>>",

            columnDefs: [
                { targets: -1, orderable: false }
            ],

            language: {
                search: "",
                searchPlaceholder: "Search exams..."
            }
        });

            $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>

</body>
</html>
