@props([
    'title' => 'Dashboard',
    'breadcrumbs' => []
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Select2 Tailwind Theme Customization */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb; /* bg-gray-50 */
            border-color: #e5e7eb; /* border-gray-200 */
            border-radius: 0.75rem; /* rounded-xl */
            height: 46px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827; /* text-gray-900 */
            padding-left: 1rem;
            padding-right: 1rem;
            line-height: 46px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }
        .select2-dropdown {
            background-color: #ffffff;
            border-color: #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 9999;
        }
        .select2-search--dropdown .select2-search__field {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            border-radius: 0.5rem;
            padding: 8px;
            outline: none;
        }
        .select2-results__option {
            padding: 8px 16px;
            font-size: 0.875rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb; /* bg-blue-600 */
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar-header,
        .sidebar-user-info {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar.collapsed .sidebar-header,
        .sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            visibility: hidden;
        }

        /* Additional transition for hiding/showing the entire sidebar */
        .sidebar.hidden {
            transform: translateX(-100%);
            opacity: 0;
            visibility: hidden;
        }

        .sidebar {
            transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s ease;
        }


        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.open,
        .menu-group.open .submenu {
            max-height: 500px;
        }

        .menu-group.open button i.mdi-chevron-down {
            transform: rotate(180deg);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .active-menu {
            background: linear-gradient(to right, #dbeafe, #e0e7ff) !important;
            color: #2563eb !important;
            border-left: 4px solid #2563eb;
        }

        .menu-item {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .menu-item:hover::before {
            left: 100%;
        }

        .menu-item:hover {
            background-color: #f0f9ff !important;
            color: #0369a1 !important;
        }

        .submenu-item {
            transition: all 0.3s ease;
        }

        .submenu-item:hover {
            background-color: #f0f9ff !important;
            color: #0369a1 !important;
        }

        /* Sidebar logout button styling */
        .sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar nav {
            flex: 1;
            overflow-y: auto;
        }

        /* User dropdown mobile responsiveness */
        @media (max-width: 640px) {
            #user-dropdown {
                right: 0 !important;
                left: auto !important;
                width: calc(100vw - 2rem) !important;
                max-width: calc(100vw - 2rem) !important;
                min-width: 200px !important;
            }
        }

        /* Breadcrumb mobile responsiveness */
        @media (max-width: 640px) {
            .breadcrumb-nav {
                display: none;
            }
        }


        /* Smooth dropdown animation */
        #user-dropdown {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }

        #user-dropdown.visible {
            opacity: 1 !important;
            visibility: visible !important;
        }

        #user-dropdown.animate-open {
            animation: fadeInSlideDown 0.3s ease-out forwards;
        }

        #user-dropdown.animate-close {
            animation: fadeOutSlideUp 0.3s ease-out forwards;
        }

        @keyframes fadeInSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeOutSlideUp {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            100% {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div id="main-content" class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        <!-- Header -->
        <x-header :breadcrumbs="$breadcrumbs" />

        <!-- Dashboard Content -->
        <main class="p-6">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-footer />
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swalConfig = {
                background: '#ffffff',
                color: '#111827',
                confirmButtonColor: '#3b82f6',
            };

            @if(session('success'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false,
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: "{{ session('error') }}",
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'info',
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Peringatan',
                    text: "{{ session('warning') }}",
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
