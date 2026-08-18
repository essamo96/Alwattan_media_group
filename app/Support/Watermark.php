<?php

namespace App\Support;

use App\Models\News;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

/**
 * يحرق شعار العلامة المائية العامة للموقع داخل ملفات الصور المرفوعة (خبر رئيسي +
 * معرض) مباشرة على القرص. لا تُستخدم لملفات الفيديو (مرفوعة أو خارجية): حرق علامة
 * داخل فيديو يحتاج ffmpeg غير مضمون توفره على كل استضافة، وفيديو يوتيوب/فيميو
 * الخارجي أصلاً لا يمكن التعديل عليه من طرف السيرفر — لهذين الحالتين تُعرض العلامة
 * كطبقة CSS فوق المشغل بالواجهة (انظر resources/views/frontend/news/parts/watermark-overlay.blade.php).
 *
 * قبل أول حرق لأي صورة، تُحفظ نسخة أصلية بجانبها (لاحقة __orig) — هذا ما يسمح
 * لاحقاً بـ"إزالة" العلامة المائية من خبر منشور مسبقاً (استرجاع الأصل) وإعادة
 * تطبيقها متى شاء الأدمن، بدل ما يكون الحرق عملية دائمة بلا رجعة.
 */
class Watermark
{
    public static function backupPath(string $absolutePath): string
    {
        $pathinfo = pathinfo($absolutePath);
        $dir = $pathinfo['dirname'];
        $name = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) ? '.' . $pathinfo['extension'] : '';

        return $dir . DIRECTORY_SEPARATOR . $name . '__orig' . $ext;
    }

    public static function applyToImage(string $absolutePath): bool
    {
        $settings = self::settings();
        if (!$settings || !$settings->watermark_enabled || empty($settings->watermark_logo)) {
            return false;
        }

        $logoPath = public_path($settings->watermark_logo);
        if (!is_file($logoPath) || !is_file($absolutePath)) {
            return false;
        }

        $backupPath = self::backupPath($absolutePath);
        if (!is_file($backupPath)) {
            @copy($absolutePath, $backupPath);
        }

        try {
            $image = Image::make($absolutePath);
            $logo = Image::make($logoPath);

            $sizePercent = (int) ($settings->watermark_size ?: 15);
            $targetWidth = max(1, (int) round($image->width() * $sizePercent / 100));
            $logo->resize($targetWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $logo->opacity((int) ($settings->watermark_opacity ?: 70));

            $margin = max(10, (int) round(min($image->width(), $image->height()) * 0.03));
            $position = $settings->watermark_position ?: 'bottom-right';

            $image->insert($logo, $position, $margin, $margin);
            $image->save($absolutePath);

            return true;
        } catch (\Throwable $e) {
            // فشل العلامة المائية لا يجب أن يمنع نجاح رفع الصورة أصلاً
            report($e);
            return false;
        }
    }

    /**
     * يرجّع الصورة لنسختها الأصلية (قبل الحرق) إن وُجدت نسخة محفوظة.
     */
    public static function removeFromImage(string $absolutePath): bool
    {
        $backupPath = self::backupPath($absolutePath);
        if (!is_file($backupPath)) {
            return false;
        }

        return @copy($backupPath, $absolutePath);
    }

    /**
     * يطبّق العلامة المائية على صورة الخبر الرئيسية + كل صور المعرض التابعة له.
     */
    public static function applyToNews(News $news): void
    {
        $applied = false;
        foreach (self::newsImagePaths($news) as $path) {
            if (self::applyToImage($path)) {
                $applied = true;
            }
        }
        if ($applied) {
            $news->watermark_applied = true;
            $news->save();
        }
    }

    /**
     * يشيل العلامة المائية عن صورة الخبر الرئيسية + كل صور المعرض التابعة له
     * (استرجاع النسخة الأصلية المحفوظة قبل الحرق).
     */
    public static function removeFromNews(News $news): void
    {
        foreach (self::newsImagePaths($news) as $path) {
            self::removeFromImage($path);
        }
        $news->watermark_applied = false;
        $news->save();
    }

    protected static function newsImagePaths(News $news): array
    {
        $paths = [];

        if ($news->type == 'image' && !empty($news->image)) {
            $paths[] = public_path($news->image);
        }

        foreach ($news->media as $item) {
            if ($item->type == 'image' && !empty($item->path)) {
                $paths[] = public_path($item->path);
            }
        }

        return $paths;
    }

    public static function settings(): ?Settings
    {
        return Cache::rememberForever('mysettings', function () {
            return Settings::find(1);
        });
    }
}
