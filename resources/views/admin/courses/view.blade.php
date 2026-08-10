@extends('layouts.admin')

@section('title', 'إدارة الدورات')

@section('page-title')
الدورات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">الدورات</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" name="name" id="name" class="form-control form-control-solid w-250px ps-12 searchable" placeholder="بحث باسم الدورة">
            </div>
        </div>
        @can('admin.courses.add')
        <div class="card-toolbar">
            <a href="{{ route('courses.add') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> إضافة
            </a>
        </div>
        @endcan
    </div>
    <div class="card-body pt-0">
        @include('admin.layout.error')
        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="courses_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th>#</th>
                    <th>اسم الدورة</th>
                    <th>تاريخ البدء</th>
                    <th>تاريخ الانتهاء</th>
                    <th>الأيام</th>
                    <th>الحالة</th>
                    <th>المرشحون</th>
                    <th class="text-end">تعديل</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600"></tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
@include('layouts.partials.confirm-modal')

<div class="modal fade" id="export_columns_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">اختيار وترتيب أعمدة التصدير</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted fs-7 mb-4">فعّل الأعمدة المطلوبة، واسحب <i class="ki-duotone ki-arrow-mix fs-6"></i> لإعادة ترتيبها كما تريدها بالملف. سيتم تصدير مرشحي هذه الدورة فقط.</div>
                <ul id="export_columns_list" class="list-group">
                    @foreach($export_columns as $key => $col)
                    <li class="list-group-item d-flex align-items-center px-3 py-2" draggable="true" data-key="{{ $key }}" style="cursor:move;">
                        <i class="ki-duotone ki-arrow-mix fs-3 text-muted me-3"><span class="path1"></span><span class="path2"></span></i>
                        <div class="form-check form-check-custom form-check-solid flex-grow-1">
                            <input class="form-check-input export-col-checkbox" type="checkbox" value="{{ $key }}" id="export_col_{{ $key }}" checked>
                            <label class="form-check-label w-100" for="export_col_{{ $key }}">{{ $col['label'] }}</label>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" id="export_select_all_btn" class="btn btn-light me-auto">تحديد الكل</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="export_confirm_btn" class="btn btn-success">
                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> تصدير
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset_v('assets/metronic/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#confirm').on('show.bs.modal', function (e) {
            $("#delete_id").val($(e.relatedTarget).data('href'));
        });

        var oTable = $('#courses_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "language": {
                "processing": "جاري التحميل...",
                "lengthMenu": "إظهار _MENU_ عنصر",
                "zeroRecords": "لا توجد بيانات مطابقة",
                "info": "عرض _START_ إلى _END_ من أصل _TOTAL_ عنصر",
                "infoEmpty": "لا توجد بيانات",
                "infoFiltered": "(منتقاة من أصل _MAX_ عنصر)",
                "search": "بحث:",
                "paginate": {
                    "first": "الأول",
                    "previous": "السابق",
                    "next": "التالي",
                    "last": "الأخير"
                }
            },
            "pageLength": 25,
            "ajax": {
                url: "{{ route('courses.list') }}",
                data: function (d) {
                    d.name = $('input[name="name"]').val();
                }
            },
            "order": [[0, 'desc']],
            "columnDefs": [{"targets": "_all", "defaultContent": ""}],
            "columns": [
                {"data": "", "title": "#", "orderable": false, "searchable": false},
                {"data": "name", "title": "اسم الدورة", "orderable": false, "searchable": false},
                {"data": "start_date", "title": "تاريخ البدء", "orderable": false, "searchable": false},
                {"data": "end_date", "title": "تاريخ الانتهاء", "orderable": false, "searchable": false},
                {"data": "days_of_week", "title": "الأيام", "orderable": false, "searchable": false},
                {"data": "status", "title": "الحالة", "orderable": false, "searchable": false},
                {"data": "candidates_count", "title": "المرشحون", "orderable": false, "searchable": false},
                {"data": "actions", "title": "تعديل", "orderable": false, "searchable": false}
            ],
            "fnDrawCallback": function (oSettings) {
                oTable.column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = (parseInt(oTable.page.info().start)) + i + 1;
                });
            }
        });

        // عرض/إخفاء قائمة مرشحي الدورة كصف فرعي بنفس الشاشة عند الضغط على زر عدد المرشحين
        function candidatesRowHtml(rows) {
            if (!rows.length) {
                return '<div class="text-muted p-3">لا يوجد مرشحون لهذه الدورة بعد</div>';
            }
            var html = '<div class="p-3"><table class="table table-sm table-row-dashed align-middle mb-0">' +
                    '<thead><tr class="text-muted fs-8 text-uppercase"><th>الاسم</th><th>الجنس</th><th>العمر</th><th>الجامعة</th><th>المعدل</th></tr></thead><tbody>';
            rows.forEach(function (r) {
                html += '<tr><td>' + (r.full_name || '') + '</td><td>' + (r.gender || '') + '</td><td>' + (r.age || '') + '</td><td>' + (r.university || '') + '</td><td>' + (r.gpa || '') + '</td></tr>';
            });
            html += '</tbody></table></div>';
            return html;
        }

        $(document).on('click', '.toggle-candidates-btn', function () {
            var btn = $(this);
            var tr = btn.closest('tr');
            var row = oTable.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                return;
            }

            row.child('<div class="p-3 text-muted">جاري التحميل...</div>').show();
            tr.addClass('shown');

            $.ajax({
                type: 'GET',
                url: "{{ url('admin/courses') }}/" + btn.data('id') + "/candidates/current"
            }).done(function (data) {
                row.child(candidatesRowHtml(data.data || [])).show();
            }).fail(function () {
                row.child('<div class="p-3 text-danger">تعذر تحميل قائمة المرشحين</div>').show();
            });
        });

        // ترتيب أعمدة التصدير بالسحب والافلات (HTML5 drag & drop، بدون مكتبة خارجية)
        var exportList = document.getElementById('export_columns_list');
        var dragEl = null;
        if (exportList) {
            exportList.querySelectorAll('li').forEach(function (li) {
                li.addEventListener('dragstart', function () {
                    dragEl = li;
                    li.classList.add('opacity-50');
                });
                li.addEventListener('dragend', function () {
                    li.classList.remove('opacity-50');
                });
                li.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    if (!dragEl || dragEl === li) {
                        return;
                    }
                    var rect = li.getBoundingClientRect();
                    var after = (e.clientY - rect.top) > (rect.height / 2);
                    exportList.insertBefore(dragEl, after ? li.nextSibling : li);
                });
            });
        }

        $('#export_select_all_btn').on('click', function () {
            var allChecked = $('.export-col-checkbox').length === $('.export-col-checkbox:checked').length;
            $('.export-col-checkbox').prop('checked', !allChecked);
        });

        var exportCourseId = null;
        $(document).on('click', '.export-candidates-btn', function (e) {
            e.preventDefault();
            exportCourseId = $(this).data('course-id');
        });

        $('#export_confirm_btn').on('click', function () {
            var columns = [];
            $('#export_columns_list li').each(function () {
                var $cb = $(this).find('.export-col-checkbox');
                if ($cb.prop('checked')) {
                    columns.push($cb.val());
                }
            });
            if (!columns.length) {
                toastr.error('يرجى تحديد عمود واحد على الأقل');
                return;
            }
            var params = 'course_id=' + encodeURIComponent(exportCourseId || '');
            columns.forEach(function (col) {
                params += '&columns[]=' + encodeURIComponent(col);
            });
            window.location.href = "{{ route('course_candidates.export') }}?" + params;
            $('#export_columns_modal').modal('hide');
        });

        $('.searchable').on('input', function (e) {
            e.preventDefault();
            oTable.draw();
        });

        $(document).on('click', ".status", function () {
            var id = $(this).data('href');
            var item = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('courses.status') }}",
                data: {'id': id}
            }).done(function (data) {
                if (data.type == 'yes') {
                    item.removeClass("badge-light-danger").addClass("badge-light-success");
                    item.html('<i class="ki-duotone ki-check fs-6 me-1"></i> فعالة');
                } else if (data.type == 'no') {
                    item.removeClass("badge-light-success").addClass("badge-light-danger");
                    item.html('<i class="ki-duotone ki-cross fs-6 me-1"></i> غير فعالة');
                }
                toastr[data.status](data.message);
            });
        });

        $(document).on('click', ".delete", function () {
            var id = $("#delete_id").val();
            $.ajax({
                type: "POST",
                url: "{{ route('courses.delete') }}",
                data: {'id': id}
            }).done(function (data) {
                toastr[data.status](data.message);
                oTable.draw();
            });
        });
    });
</script>
@endpush
