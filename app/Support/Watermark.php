<?php

namespace App\Support;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

/**
 * يحرق شعار العلامة المائية العامة للموقع داخل ملفات الصور المرفوعة (خبر رئيسي +
 * معرض) مباشرة على القرص. لا تُستخدم لملفات الفيديو (مرفوعة أو خارجية): حرق علامة
 * داخل فيديو يحتاج ffmpeg غير مضمون توفره على كل استضافة، وفيديو يوتيوب/فيميو
 * الخارجي أصلاً لا يمكن التعديل عليه من طرف السيرفر — لهذين الحالتين تُعرض العلامة
 * كطبقة CSS فوق المشغل بالواجهة (انظر resources/views/frontend/news/parts/watermark-overlay.blade.php).
 */
class Watermark
{
    public static function applyToImage(string $absolutePath): void
    {
        $settings = self::settings();
        if (!$settings || !$settings->watermark_enabled || empty($settings->watermark_logo)) {
            return;
        }

        $logoPath = public_path($settings->watermark_logo);
        if (!is_file($logoPath) || !is_file($absolutePath)) {
            return;
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
        } catch (\Throwable $e) {
            // فشل العلامة المائية لا يجب أن يمنع نجاح رفع الصورة أصلاً
            report($e);
        }
    }

    public static function settings(): ?Settings
    {
        return Cache::rememberForever('mysettings', function () {
            return Settings::find(1);
        });
    }
}
