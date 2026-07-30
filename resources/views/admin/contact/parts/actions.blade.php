<div class="form-group" style="justify-content: center; display: flex;">
    @can('admin.contact.edit')
    <a class="btn btn-primary btn-sm" href="{{ route('contact.edit', ['id' => Crypt::encrypt($id)]) }}">
        <i class="fa fa-pencil"></i> تعديل </a>
    @endcan
    @can('admin.contact.detailes')
    <a class="btn btn-warning btn-sm" href="{{ route('contact.info', ['id' => Crypt::encrypt($id)]) }}">
        <i class="fa fa-pencil"></i> تفاصيل </a>
    @endcan
    @can('admin.contacts.delete')
    <a class="btn btn-danger btn-sm" href="#confirm" data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
        <i class="fa fa-trash-o"></i> حذف </a>
    @endcan
    @can('admin.contacts.view')
    <a class="btn btn-success btn-sm btnPrint" href="{{ url('admin/contact/print/'.Crypt::encrypt($id))}}"
       data-href="{{ Crypt::encrypt($id) }}" data-toggle="modal">
        <i class="fa fa-print"></i> طباعة </a>
    @endcan
</div>