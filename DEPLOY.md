# دليل النشر — مجموعة الوطن الإعلامية

**الحالة الحالية (2026-08-01):** الكود على السيرفر رُفع يدوياً (نسخة كاملة طابقت
المحلي وقت الرفع). GitHub (`main`) والمحلي متطابقان تماماً. آلية النشر الآلي
الفعّالة هي **FTP** عبر GitHub Actions، وتُشغَّل تلقائياً عند أي `push` إلى `main`.

---

## ١. آلية النشر: FTP عبر GitHub Actions

مُعرَّفة في [.github/workflows/main.yml](.github/workflows/main.yml)، وتُشغَّل
تلقائياً عند الدفع إلى `main`. تحتاج سرَّين فقط في
`Settings ← Secrets and variables ← Actions`:

| السر | القيمة |
|---|---|
| `FTP_USERNAME` | اسم مستخدم FTP |
| `FTP_PASSWORD` | كلمة مرور FTP |

### ما تستثنيه الورشة عمداً من كل نشر

- `vendor/` — **الورشة لا تشغّل `composer install`**. إن تغيّر `composer.json`
  أو `composer.lock`، يجب تشغيل `composer install --no-dev --optimize-autoloader`
  يدوياً عبر SSH بعد النشر، وإلا بقي السيرفر يستخدم مكتبات قديمة.
- `public/uploads/**` — محتوى يرفعه المستخدمون من لوحة التحكم؛ حمايته من
  الحذف أو الاستبدال في أي مزامنة.
- `.env`, `tests/`, `node_modules/` وملفات إعداد التطوير.

### عائق معروف — أُصلح لمرة واحدة

الأداة (`FTP-Deploy-Action`) تقارن الحالة مع ملف `.ftp-deploy-sync-state.json`
على السيرفر، وتنهار عند أول عملية حذف لا تطابق الواقع. حدث هذا مع
`database/seeds` (أعادت ترقية Laravel تسميته إلى `seeders`)، وأُصلح بإنشاء
المجلد فارغاً على السيرفر قبل النشر. إن ظهر خطأ `550` مشابه لمجلد آخر مستقبلاً،
نفس الحل: أنشئ المجلد المفقود فارغاً ثم أعد تشغيل الورشة.

---

## ٢. بعد كل نشر يغيّر الاعتماديات أو قاعدة البيانات

عبر SSH:

```bash
cd ~/domains/alwattanmediagroup.com/public_html

# فقط إن تغيّر composer.json أو composer.lock
composer install --no-dev --optimize-autoloader

# دائماً: كاش الحزم المُجمَّع القديم يعطّل الإقلاع إن بقي
rm -f bootstrap/cache/*.php
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan package:discover

# الترحيلات: كل ملفات database/migrations محمية بـ hasTable/hasColumn،
# فتشغيلها آمن دائماً ولا يمس الجداول الموجودة
php artisan migrate --force

chmod -R 775 storage bootstrap/cache public/uploads
```

**⚠️ لا تشغّل `php artisan db:seed --force` مباشرة.** `DatabaseSeeder` يستدعي
`AdminUserSeeder` الذي **يعيد تعيين كلمة مرور المستخدم `admin`** إلى قيمة
افتراضية. لمزامنة الصلاحيات فقط دون المساس بالمستخدم:

```bash
php artisan db:seed --class=SyncPermissionsSeeder --force
```

`cache:clear` ضروري تحديداً لأن `HomepageController` يخزّن الصفحة الرئيسية
بـ`Cache::rememberForever` — تعديل بلا مسح الكاش لا يظهر أثره أبداً.

---

## ٣. التحقق بعد أي نشر

```bash
php artisan migrate:status | grep -i pending   # يجب أن يكون فارغاً
```

ثم في المتصفح:

- `/` و `/ar` — تظهر بتنسيقها الكامل (CSS)، لا HTML خام.
- `/admin/login` — لوجو جريدة الوطن، وتسجيل الدخول يعمل.
- `https://www.alwattanmediagroup.com/.git/config` — **يجب أن يعطي 404**
  (محمي عبر `.htaccess`؛ ذو صلة فقط إن ربطت السيرفر بـgit مستقبلاً).

---

## ٤. في حال تعطّل الموقع بعد نشر

```bash
cd ~/domains/alwattanmediagroup.com
cp -r public_html public_html_backup_$(date +%Y%m%d)   # قبل أي محاولة إصلاح
```

راجع `storage/logs/laravel.log` أولاً — يذكر السبب الدقيق في أغلب الحالات
(جدول مفقود، كاش قديم، صلاحيات ملفات). إن تعذّر الإصلاح سريعاً، ارجع لنسخة
احتياطية سابقة إن وُجدت وأعد نسخة PHP في hPanel إلى ما كانت عليه.

---

## مرجع: قرارات تصميم يجب معرفتها

| القرار | السبب |
|---|---|
| `public/` مُتعقَّب في git بالكامل عدا `uploads/` | كان مُستثنى كلياً سابقاً، فلم تكن أي نسخة أصول قابلة للاسترجاع محلياً. حجمه بعد تنظيف الأصول غير المستخدمة 30 ميغا فقط، فأصبح تتبعه ممكناً. |
| `bootstrap/cache/*.php` غير متعقَّب | كان كاش Laravel 7 مجمَّعاً ومتعقَّباً، ويطمس كاش أي نسخة أحدث عند النشر — هذا كان سبب خطأ `Fideloper\Proxy\TrustedProxyServiceProvider not found`. |
| الترحيلات الجديدة محمية بـ `hasTable`/`hasColumn` | تُصلح قاعدة بيانات مستوردة كانت تفتقد جداولاً وأعمدة، وتبقى آمنة التشغيل على أي نسخة سيرفر حالية أو مستقبلية دون تكرار أو تعارض. |
| `AdminUserSeeder` غير مُشغَّل تلقائياً عبر `db:seed` العام | يعيد تعيين كلمة مرور `admin` في كل مرة — خطر على بيئة حية. |
