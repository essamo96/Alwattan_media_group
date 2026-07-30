@extends('admin.layout.master')
@section('css')
    <style>
        .mt-comments .mt-comment .mt-comment-img>img {
            border-radius: 50% !important;
            width: 40px;
            float: right;
        }

        .mt-comments .mt-comment .mt-comment-body .mt-comment-text {
            color: #0c0774;
            font-family: cursive;
            background-color: #e7ecf1;
            transition: font-size 0.3s ease;
            transition: background-color 1.5s ease-out;
            padding-right: 50px;
            padding-block: 7px;

        }

        .mt-comments .mt-comment .mt-comment-body .mt-comment-info .mt-comment-date {
            color: #ff670e;
        }

        .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-status.mt-comment-status-pending {
            color: #ff2f00;
        }

        .mt-comment-status-Activated {
            color: #1eb902;
        }
    </style>
@endsection
@section('title')
    إدارة الاشعارات اليومية
@stop

@section('page-breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('dashboard.view') }}">الرئيسية</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('Today.notifcation') }}">الاشعارات اليومية</a>
            <i class="fa fa-angle-right"></i>
            </i>
        </li>
        <li>
            <span>عرض الاشعارات اليومية</span>
        </li>
    </ul>
@stop

@section('page-content')
    {{-- <div class="portlet box {{ $form_class }}">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-magnifier"></i>البحث
            </div>
        </div>
        <div class="portlet-body">
            <form role="form" class="form-horizontal">
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">الاسم/رقم الجوال</label>
                        <div class="col-md-6">
                            <input type="text" name="name" id="name" class="form-control searchable"
                                placeholder="الاسم أو رقم الجوال">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="row">
                <div class="col-lg-12 col-xs-12 col-sm-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title tabbable-line">
                            <div class="caption">
                                <i class="bi bi-chat-left-dots-fill"></i>
                                <span class="caption-subject font-dark bold uppercase">جهات الاتصال المضافة خلال
                                    اليوم</span>
                            </div>
                            <ul class="nav nav-tabs">
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="portlet_comments_1">
                                    <!-- BEGIN: Comments -->
                                    @foreach ($notifications as $message)
                                        <div class="mt-comments">
                                            <div class="mt-comment">
                                                <div class="mt-comment-img">
                                                    <img src="assets/admin/Default.jpg" />
                                                </div>
                                                <div class="mt-comment-body">
                                                    <div class="mt-comment-info">
                                                        <span class="mt-comment-author"
                                                            style="color:#b303e9">{{ $message->created_by }}</span> : قام
                                                        بإضافة جهة الاتصال <strong>{{ $message->name }}</strong>

                                                        <span class="mt-comment-date">
                                                            {{-- تاريخ الارسال والوقت --}}
                                                            {{ $message->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                    <div class="mt-comment-text">
                                                        {{-- تفاصيل هنا --}}
                                                        {!! $message->notes !!}
                                                    </div>
                                                    <div class="mt-comment-details">

                                                        <span
                                                            class="mt-comment-status mt-comment-status-Activated">{{ $message->contact_type }}</span>

                                                        {{-- <a class="mt-comment-status mt-comment-status-Activated" href="#" style=" padding-inline: 10px; color: #1eb7bf; text-decoration: none;"></a> --}}
                                                        {{-- <ul class="mt-comment-actions">
                                                    <div class="mt-action-buttons ">
                                                        <div class="btn-group btn-group-circle">
                                                            <button type="button"
                                                                class="btn btn-outline green btn-sm Reply" id="Reply" data-id="{{Crypt::encrypt($message->student->id)}}">Reply</button>
                                                            <button type="button"
                                                                class="btn btn-outline red btn-sm" data-id="{{ $message->id }}">Delete</button>
                                                        </div>
                                                        
                                                    </div>
                                                        </ul> --}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')
    @include('admin.layout.ajax')
@stop
@section('css')
@stop
@section('js')
    <script>
        // $(document).on('click', '.red', function () {
        //     var id = $(this).data('id');

        //     Swal.fire({
        //         title: 'هل انت متاكد من عملية الحذف؟',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Oki'
        //     }).then(function (result) {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 type: 'POST',
        //                 url: 'admin/students/delete/messages',
        //                 data: {
        //                     id: id,
        //                     _token: '{{ csrf_token() }}'
        //                 },

        //                 success: function (response) {
        //                     Swal.fire({
        //                         title: 'Deleted!',
        //                         text: response.message,
        //                         icon: 'success',
        //                         timer: 2000,
        //                         showConfirmButton: false
        //                     });

        //                     // Reload the page after a short delay
        //                     setTimeout(function () {
        //                         location.reload();
        //                     }, 2000);
        //                 },
        //                 error: function (response) {
        //                     Swal.fire({
        //                         title: 'Oops...',
        //                         text: 'Something went wrong!',
        //                         icon: 'error',
        //                         timer: 2000,
        //                         showConfirmButton: false
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });
    </script>
@stop
