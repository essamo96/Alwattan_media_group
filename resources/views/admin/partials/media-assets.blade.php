{{--
  تحميل موحّد لـ Metronic CKEditor Classic + Laravel File Manager.
  يُستدعى مرة واحدة من layouts/admin عبر stack أو مباشرة.
--}}
<script>
    window.AdminMedia = window.AdminMedia || {};
    window.AdminMedia.lfmPrefix = @json(url('admin/file_manager'));
    window.AdminMedia.ckUploadUrl = @json(route('news.upload', ['_token' => csrf_token()]));
    window.AdminMedia.blankImage = '';
</script>
<script src="{{ asset('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script src="{{ asset_v('assets/metronic/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script src="{{ asset_v('assets/admin/global/scripts/admin-media.js') }}"></script>
