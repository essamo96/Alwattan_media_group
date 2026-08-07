{{-- Metronic v8.1.8 Demo1 (light-sidebar) toolbar partial --}}
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                @yield('page-title', '')
            </h1>
            @hasSection('breadcrumbs')
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    @yield('breadcrumbs')
                </ul>
            @endif
        </div>
    </div>
</div>
