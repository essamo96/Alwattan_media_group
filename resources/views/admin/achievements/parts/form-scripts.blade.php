<style>
    .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f3fb;
        color: #3f4254;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 12.5px;
        font-weight: 500;
    }
    .tag-chip .tag-remove {
        cursor: pointer;
        color: #99a1b7;
        font-weight: 700;
        line-height: 1;
    }
    .tag-chip .tag-remove:hover {
        color: #f1416c;
    }
    .achievement-dropzone label:hover {
        border-color: #7239ea !important;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        // ------- معاينة حية لصور الإنجاز (عربي/انجليزي) -------
        $('.achievement-image-input').on('change', function () {
            var input = this;
            var previewId = $(this).data('preview');
            var placeholderId = $(this).data('placeholder');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + previewId).attr('src', e.target.result).removeClass('d-none');
                    $('#' + placeholderId).addClass('d-none');
                    $('#' + previewId).closest('.achievement-dropzone').find('span.position-absolute.bottom-0').removeClass('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        // ------- عدّاد أحرف الوصف المختصر -------
        function updateShortDescCount() {
            $('#short_desc_count').text($('#short_description').val().length);
        }
        updateShortDescCount();
        $('#short_description').on('input', updateShortDescCount);

        // ------- الوسوم (tags) بشكل chips، تُخزّن بحقل مخفي مفصول بفواصل -------
        var $tagInput = $('#tag_input');
        var $tagsHidden = $('#tags_hidden');
        var $tagBox = $('#tags_chip_box');

        function renderExistingTags() {
            var initial = ($tagsHidden.val() || '').split(',').map(function (t) {
                return t.trim();
            }).filter(function (t) {
                return t.length > 0;
            });
            initial.forEach(addTagChip);
        }

        function syncHiddenInput() {
            var tags = [];
            $tagBox.find('.tag-chip').each(function () {
                tags.push($(this).data('tag'));
            });
            $tagsHidden.val(tags.join(','));
        }

        function addTagChip(tag) {
            tag = tag.trim();
            if (!tag) {
                return;
            }
            var exists = false;
            $tagBox.find('.tag-chip').each(function () {
                if ($(this).data('tag') === tag) {
                    exists = true;
                }
            });
            if (exists) {
                return;
            }
            var $chip = $('<span class="tag-chip" data-tag="' + $('<div>').text(tag).html() + '"></span>');
            $chip.append($('<span></span>').text(tag));
            $chip.append('<span class="tag-remove">&times;</span>');
            $tagInput.before($chip);
            syncHiddenInput();
        }

        renderExistingTags();

        $tagInput.on('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTagChip($(this).val());
                $(this).val('');
            } else if (e.key === 'Backspace' && $(this).val() === '') {
                $tagBox.find('.tag-chip').last().remove();
                syncHiddenInput();
            }
        });

        $(document).on('click', '.tag-remove', function () {
            $(this).closest('.tag-chip').remove();
            syncHiddenInput();
        });

        // ------- تعطيل زر الحفظ مؤقتاً عند الإرسال لمنع الإرسال المزدوج -------
        $('#achievement_form').on('submit', function () {
            syncHiddenInput();
            $('#achievement_submit_btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> جاري الحفظ...');
        });
    });
</script>
