@extends('layouts.admin')

@section('title', 'البوم عقار')

@section('page-title')
العقارات
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"><a href="{{ route('dashboard.view') }}" class="text-muted text-hover-primary">الرئيسية</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted"><a href="{{ route('properties.view') }}" class="text-muted text-hover-primary">إدارة العقارات</a></li>
<li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">{{ $info->title }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">البوم عقار</div>
    </div>
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-12">
                <label class="form-label" for="gallery">معرض الصور</label>
                <div class="dropzone" id="gallery-dropzone"></div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-5">
            <a href="{{ route('properties.view') }}" class="btn btn-light">عودة</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<style>
    .dz-image img {
        width: 100%;
        height: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script>
Dropzone.options.galleryDropzone =
        {
            url: '{{ route('admin.properties.storeMedia',['id' => $info->id]) }}',
            maxFiles: 10,
            maxFilesize: 4,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            timeout: 50000,
            init: function () {
                // Get images
                var myDropzone = this;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.properties.viewMedia',['id' => $info->id]) }}",
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        //console.log(data);
                        $.each(data, function (key, value) {

                            var file = {name: value.name, size: value.size};
                            myDropzone.options.addedfile.call(myDropzone, file);
                            myDropzone.options.thumbnail.call(myDropzone, file, value.path);
                            myDropzone.emit("complete", file);
                        });
                    }
                });
            },
            removedfile: function (file)
            {
                if (this.options.dictRemoveFile) {
                    return Dropzone.confirm("Are You Sure to " + this.options.dictRemoveFile, function () {
                        if (file.previewElement.id != "") {
                            var name = file.previewElement.id;
                        } else {
                            var name = file.name;
                        }
                        //console.log(name);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: "{{ route('admin.properties.deleteMedia',['id' => $info->id]) }}",
                            data: {filename: name},
                            success: function (data) {
                                alert(data.success + " File has been successfully removed!");
                            },
                            error: function (e) {
                                console.log(e);
                            }});
                        var fileRef;
                        return (fileRef = file.previewElement) != null ?
                                fileRef.parentNode.removeChild(file.previewElement) : void 0;
                    });
                }
            },

            success: function (file, response)
            {
                file.previewElement.id = response.success;
                //console.log(file);
                // set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.success;
            },
            error: function (file, response)
            {
                if ($.type(response) === "string")
                    var message = response; //dropzone sends it's own error messages in string
                else
                    var message = response.message;
                file.previewElement.classList.add("dz-error");
                _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i];
                    _results.push(node.textContent = message);
                }
                return _results;
            }

        };
</script>
@endpush
