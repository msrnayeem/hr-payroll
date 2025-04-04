<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.components.head')

<!--begin::Body-->

<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">

        <!--begin::Header-->
        @include('layouts.components.navbar')
        <!--end::Header-->

        <!--begin::Sidebar-->
        @include('layouts.components.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">

            {{-- Pass the variables to the breadcrumb component --}}
            @include('layouts.components.breadcrumb', [
                'pageTitle' => $pageTitle,
                'breadcrumbs' => $breadcrumbs,
            ])


            <!--begin::App Content-->
            <div class="app-content">

                <!--begin::Container-->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->


        <!--begin::Footer-->
        @include('layouts.components.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    @include('layouts.components.scripts')
</body>
<!--end::Body-->

</html>
