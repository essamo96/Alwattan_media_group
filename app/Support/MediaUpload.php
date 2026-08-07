<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * توحيد رفع الصور من مدير الملفات (LFM) أو من ملف مرفوع مباشرة،
 * مع انشاء المجلدات وتنظيم التخزين تحت public/uploads.
 */
class MediaUpload
{
    /**
     * يضمن وجود مجلد الوجهة تحت public/ قبل move() مباشرة — public/uploads بالكامل
     * خارج git (.gitignore)، فأي سيرفر جديد/نشر جديد لا يملك هذه المجلدات فعلياً حتى
     * أول رفع يفشل بدونها.
     *
     * @param  string  $relativeFolder  مثال: uploads/partners
     */
    public static function ensureDir(string $relativeFolder): string
    {
        $relativeFolder = trim(str_replace('\\', '/', $relativeFolder), '/');
        $absolute = public_path($relativeFolder);
        if (!File::isDirectory($absolute)) {
            File::makeDirectory($absolute, 0755, true);
        }
        return $absolute;
    }

    /**
     * يستورد صورة الى مجلد معيّن تحت public/.
     *
     * يدعم:
     * - UploadedFile من input type=file
     * - مسار نصي من Laravel File Manager (مثل photos/x.jpg أو /uploads/photos/x.jpg)
     *
     * @param  Request|UploadedFile|string|null  $source
     * @param  string  $destinationFolder  مثال: uploads/sliders
     * @param  string  $storeAs            filename = اسم الملف فقط | path = المسار النسبي الكامل
     * @param  string|null  $existing      القيمة الحالية في قاعدة البيانات
     * @return string|null  القيمة التي يجب حفظها في DB، او null ان لم يتغيّر شيء
     */
    public static function import($source, string $destinationFolder, string $storeAs = 'filename', ?string $existing = null): ?string
    {
        $destinationFolder = trim(str_replace('\\', '/', $destinationFolder), '/');
        $absoluteDest = public_path($destinationFolder);

        if (!File::isDirectory($absoluteDest)) {
            File::makeDirectory($absoluteDest, 0755, true);
        }

        $file = null;
        $extension = null;

        if ($source instanceof Request) {
            // يُمرَّر الحقل لاحقا عبر importFromRequest
            return null;
        }

        if ($source instanceof UploadedFile && $source->isValid()) {
            $file = $source;
            $extension = $source->getClientOriginalExtension() ?: $source->guessExtension() ?: 'jpg';
        } elseif (is_string($source) && trim($source) !== '') {
            $resolved = self::resolvePublicPath($source);
            if ($resolved === null || !is_file($resolved)) {
                return $existing;
            }

            // اذا كان الملف موجودا اصلا في مجلد الوجهة، لا داعي لنسخه
            $relativeResolved = self::toPublicRelative($resolved);
            if ($storeAs === 'path' && Str::startsWith($relativeResolved, $destinationFolder . '/')) {
                return $relativeResolved;
            }
            if ($storeAs === 'filename' && dirname($relativeResolved) === $destinationFolder) {
                return basename($relativeResolved);
            }

            $extension = pathinfo($resolved, PATHINFO_EXTENSION) ?: 'jpg';
            $newName = 'image_' . time() . '_' . Str::random(4) . '.' . strtolower($extension);
            $target = $absoluteDest . DIRECTORY_SEPARATOR . $newName;
            File::copy($resolved, $target);

            return $storeAs === 'path'
                ? $destinationFolder . '/' . $newName
                : $newName;
        }

        if ($file instanceof UploadedFile) {
            $newName = 'image_' . time() . '_' . Str::random(4) . '.' . strtolower($extension);
            $file->move($absoluteDest, $newName);

            return $storeAs === 'path'
                ? $destinationFolder . '/' . $newName
                : $newName;
        }

        return $existing;
    }

    /**
     * يستخرج المصدر من الطلب (ملف مرفوع او مسار نصي من LFM).
     */
    public static function importFromRequest(
        Request $request,
        string $field,
        string $destinationFolder,
        string $storeAs = 'filename',
        ?string $existing = null
    ): ?string {
        if ($request->hasFile($field)) {
            return self::import($request->file($field), $destinationFolder, $storeAs, $existing);
        }

        $path = $request->input($field);
        if (is_string($path) && trim($path) !== '') {
            // تجاهل القيمة ان كانت نفسها الموجودة مسبقا (لا اعادة نسخ)
            if ($existing !== null && self::pathsEqual($path, $existing, $destinationFolder, $storeAs)) {
                return $existing;
            }
            return self::import($path, $destinationFolder, $storeAs, $existing);
        }

        return $existing;
    }

    /**
     * تطبيع مسار مدير الملفات للحفظ المباشر (اخبار / خدمات / عقارات).
     * يعيد مسارا نسبيا يبدأ بـ uploads/ او كما جاء من LFM تحت uploads.
     */
    public static function normalizeLfmPath(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path));
        $path = preg_replace('#^https?://[^/]+/#i', '', $path);
        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'public/')) {
            $path = substr($path, 7);
        }

        // LFM يعيد غالبا photos/... نسبة لجذر القرص (public/uploads)
        if (!Str::startsWith($path, 'uploads/')) {
            if (Str::startsWith($path, 'photos/') || Str::startsWith($path, 'files/')) {
                $path = 'uploads/' . $path;
            }
        }

        return $path;
    }

    /**
     * رفع صورة من محرر CKEditor الى uploads/ckeditor/YYYY/MM.
     *
     * @return array{url:string,fileName:string,path:string}
     */
    public static function storeCkeditorUpload(UploadedFile $file): array
    {
        $subdir = 'uploads/ckeditor/' . date('Y') . '/' . date('m');
        $absolute = public_path($subdir);
        if (!File::isDirectory($absolute)) {
            File::makeDirectory($absolute, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = ($fileName !== '' ? $fileName : 'image') . '_' . time() . '.' . $extension;

        $file->move($absolute, $fileName);
        $relative = $subdir . '/' . $fileName;

        return [
            'path' => $relative,
            'fileName' => $fileName,
            'url' => asset($relative),
        ];
    }

    /**
     * رابط معاينة مناسب للعرض في لوحة التحكم.
     */
    public static function previewUrl(?string $stored, ?string $fallbackFolder = null): ?string
    {
        if ($stored === null || trim($stored) === '') {
            return null;
        }

        $stored = str_replace('\\', '/', trim($stored));

        if (preg_match('#^https?://#i', $stored)) {
            return $stored;
        }

        $relative = ltrim($stored, '/');
        if ($fallbackFolder && !Str::contains($relative, '/')) {
            $relative = trim($fallbackFolder, '/') . '/' . $relative;
        }

        if (is_file(public_path($relative))) {
            return asset($relative);
        }

        // محاولة تحت uploads/
        if (!Str::startsWith($relative, 'uploads/') && is_file(public_path('uploads/' . $relative))) {
            return asset('uploads/' . $relative);
        }

        return asset($relative);
    }

    protected static function resolvePublicPath(string $path): ?string
    {
        $path = trim(str_replace('\\', '/', $path));
        $path = preg_replace('#^https?://[^/]+/#i', '', $path);
        $path = ltrim($path, '/');

        $candidates = [
            public_path($path),
            public_path('uploads/' . $path),
        ];

        // ان بدأ بـ uploads/ مسبقا
        if (Str::startsWith($path, 'uploads/')) {
            $candidates[] = public_path($path);
        } else {
            // LFM disk root = public/uploads → photos/x.jpg
            $candidates[] = public_path('uploads/' . $path);
        }

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    protected static function toPublicRelative(string $absolute): string
    {
        $public = str_replace('\\', '/', public_path());
        $absolute = str_replace('\\', '/', $absolute);
        if (Str::startsWith($absolute, $public)) {
            return ltrim(substr($absolute, strlen($public)), '/');
        }
        return ltrim($absolute, '/');
    }

    protected static function pathsEqual(string $incoming, string $existing, string $destinationFolder, string $storeAs): bool
    {
        $a = self::normalizeLfmPath($incoming) ?? $incoming;
        $b = $existing;

        if ($storeAs === 'filename' && !Str::contains($b, '/')) {
            $b = trim($destinationFolder, '/') . '/' . $b;
        }

        $a = ltrim(str_replace('\\', '/', $a), '/');
        $b = ltrim(str_replace('\\', '/', $b), '/');

        return $a === $b || basename($a) === basename($b);
    }
}
