# دليل النشر على السيرفر — بعد الترقية إلى Laravel 9 / PHP 8.2

## قبل البدء: نسخة احتياطية

```bash
cd ~/domains/alwattanmediagroup.com
cp -r public_html public_html_backup_$(date +%Y%m%d)
```

وخذ نسخة من قاعدة البيانات من phpMyAdmin (تصدير → SQL).

---

## ١. ضبط نسخة PHP

من hPanel ← تكوين PHP ← اختر **PHP 8.2** (وليس 8.3) واضغط تحديث.
Laravel 9 مدعوم رسمياً على 8.0–8.2.

---

## ٢. سحب الكود

```bash
cd ~/domains/alwattanmediagroup.com/public_html
git fetch origin
git checkout main
git pull origin main
```

---

## ٣. بناء المكتبات على السيرفر

**لا ترفع مجلد `vendor/` يدوياً** — composer متوفر على السيرفر (2.9.8):

```bash
rm -rf vendor
composer install --no-dev --optimize-autoloader
```

يستغرق دقيقتين إلى خمس. إن ظهر خطأ ذاكرة:

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader
```

---

## ٤. مسح الكاش القديم

ملفات الكاش المُجمَّعة بنسخة Laravel 7 **يجب حذفها** وإلا سيتعطل الموقع:

```bash
rm -f bootstrap/cache/*.php
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/data/*
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## ٥. قاعدة البيانات

```bash
php artisan migrate --force
php artisan db:seed --force
```

يجب أن تظهر أسطر `تم منح صلاحيات القوائم للدور: ...`

للتحقق:
```bash
php artisan tinker --execute="echo App\Models\Menus::count();"
```
يجب أن يطبع **7**.

---

## ٦. الصلاحيات على المجلدات

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads
```

---

## ٧. تنظيف

```bash
rm -f diagnose.php clear-cache.php diagnose-error.log composer.json.laravel7.bak
> storage/logs/laravel.log
```

---

## ٨. التحقق النهائي

- افتح `https://www.alwattanmediagroup.com/` — يجب أن تظهر القوائم السبعة مع الأيقونات، وسلايدر الصور في الأعلى.
- افتح `/admin/login` — لوجو جريدة الوطن.
- سجّل الدخول — بند **«قوائم الموقع»** في السايدبار.
- بدّل اللغة إلى الإنجليزية — أسماء القوائم بالإنجليزي.

---

## في حال تعطّل الموقع

الرجوع للنسخة السابقة فوراً:

```bash
cd ~/domains/alwattanmediagroup.com
rm -rf public_html
mv public_html_backup_YYYYMMDD public_html
```

ثم أعد نسخة PHP في hPanel إلى ما كانت عليه.
