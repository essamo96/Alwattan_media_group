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

---
---

# تحديث 2026-07-31 — إصلاح الأصول وقاعدة البيانات وتنظيف public

هذا القسم يكمّل ما سبق ويغطي تغييرات جديدة. **اقرأه قبل النشر.**

## ما تغيّر في هذه الجولة

| التغيير | يُنشر عبر git؟ |
|---|---|
| 5 ملفات ترحيل جديدة (`settings`، الجداول المفقودة، أعمدة `users`، المفاتيح الأساسية، `permissions_group`) | ✅ |
| 4 seeders جديدة (`AdminUserSeeder`، `MissingCategorySeeder`، `MissingTranslationsSeeder`، `SyncPermissionsSeeder`) | ✅ |
| إصلاح namespace حزمة الكوكيز: `cookieConsent::` ← `cookie-consent::` | ✅ |
| `2017_09_07_090729_create_permission_tables` صار idempotent (كان سيُفشل `migrate` على أي قاعدة قائمة) | ✅ |
| `bootstrap/cache/*.php` أُزيلت من تتبع git | ✅ |
| حذف 4724 ملف أصول غير مستعملة (196 ميغا) + الـPDF (99 ميغا) | ❌ **يدوياً** |
| استعادة 6506 ملف في `public/` | ❌ **يدوياً** |

## ١. مسألة `public/` — الأهم

`/public` مُستثنى في `.gitignore`، فلا يصل السيرفر عبر `git pull` ولا عبر GitHub Actions
(عدا 8 ملفات أُضيفت بالقوة: `hero-slider.css/js`، `mediagrope.png`، `wide4-6.jpg`، `login.css`، `wattan-newspaper.png`).

**لذلك:**
- أصول السيرفر لم تتأثر بما جرى محلياً — المسح كان محلياً فقط.
- **تنظيف الـ196 ميغا لن ينتقل تلقائياً.** لتطبيقه على السيرفر: احذف المجلدات التالية عبر SSH أو مدير الملفات:

```bash
cd ~/domains/alwattanmediagroup.com/public_html/public
rm -rf assets/admin/global/plugins/{amcharts,socicon,cubeportfolio,ckeditor,mapplic,codemirror,echarts,highmaps,highstock,wysihtml,jcrop,highcharts,fullcalendar,bootstrap-editable,plupload,angularjs,bootstrap-table,select2,jstree,bootstrap-summernote,jqvmap,owl-carousel,flot,morris,bootstrap-wysihtml5,fancybox,jquery-validation,jquery-file-upload,jquery-minicolors,bootstrap-datetimepicker,jquery-inputmask,countdown,ion.rangeslider,bootstrap-markdown,bootstrap-daterangepicker,gmaps,flowchart,typeahead,bootstrap-multiselect,animate,bootstrap-timepicker,jquery-gantt,nouislider,bootstrap-sweetalert}
rm -rf media assets/admin/pages/scripts assets/front/revolution assets/front/images/logo
rm -f assets/marketing_profile.pdf assets/media.pdf
find . -name ".DS_Store" -delete
```

> **لا تحذف `public/uploads`** — محتوى الموقع المرفوع.

**توصية دائمة:** أضف `public/uploads/**` إلى قائمة `exclude` في
`.github/workflows/main.yml`، حتى لا يمسّ أي نشر مستقبلي مرفوعات المستخدمين.

## ٢. قاعدة البيانات

```bash
php artisan migrate --force
```

كل الترحيلات محمية بـ `hasTable` / `hasColumn`، فهي بلا أثر على الجداول الموجودة على السيرفر.

**⚠️ لا تشغّل `php artisan db:seed --force` مباشرة على السيرفر.**
`DatabaseSeeder` صار يستدعي `AdminUserSeeder`، وهو **يعيد تعيين كلمة مرور المستخدم `admin`**
إلى القيمة الافتراضية. إن احتجت مزامنة الصلاحيات فقط:

```bash
php artisan db:seed --class=SyncPermissionsSeeder --force
```

ولإنشاء حساب مدير على السيرفر، ضع في `.env` أولاً:

```
ADMIN_USERNAME=...
ADMIN_PASSWORD=...
ADMIN_EMAIL=...
```

ثم `php artisan db:seed --class=AdminUserSeeder --force`.

## ٣. الكاش

`bootstrap/cache/*.php` لم تعد في git — وهي كانت سبب خطأ
`Class "Fideloper\Proxy\TrustedProxyServiceProvider" not found`.
بعد النشر:

```bash
rm -f bootstrap/cache/*.php
php artisan cache:clear && php artisan view:clear && php artisan config:clear
php artisan package:discover
```

`cache:clear` ضروري تحديداً لأن `HomepageController` يستخدم `Cache::rememberForever`.

## ٤. التحقق

```bash
php artisan migrate:status | grep -i pending    # يجب أن يكون فارغاً
```

ثم افتح `/` و `/ar` و `/admin/login` وتأكد من ظهور التنسيق (CSS) لا HTML خام.
