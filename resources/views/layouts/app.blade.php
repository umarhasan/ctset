<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>AdminLTE v4</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- FontAwesome (FOR CRUD ICONS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @include('layouts.footer')
</div>

<!-- ================= JS ================= -->

<!-- jQuery (REQUIRED) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="{{ asset('adminlte/js/adminlte.js') }}"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

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
