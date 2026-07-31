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

---

## النشر الآلي عبر SSH (بديل FTP)

استُبدلت ورشة FTP بورشة SSH في [.github/workflows/main.yml](.github/workflows/main.yml).

**لماذا:** أداة FTP تزامن الملفات مقابل ملف حالة على السيرفر
(`.ftp-deploy-sync-state.json`)، وتنهار كلياً عند أول عملية حذف لا تطابق الواقع —
وهو ما حدث مع `database/seeds` بعد ترقية Laravel 8 التي أعادت تسميته إلى `seeders`.
ولأن ملف الحالة لا يُكتب إلا عند النجاح الكامل، دخل النشر في حلقة فشل دائمة.

النشر عبر SSH يجعل السيرفر يسحب **نفس الـ commit** من GitHub، فتتطابق الثلاثة
(المحلي / GitHub / السيرفر) بحكم التصميم لا بالمزامنة.

### الأسرار المطلوبة في GitHub

`Settings ← Secrets and variables ← Actions ← New repository secret`

| الاسم | القيمة |
|---|---|
| `SSH_HOST` | `147.93.54.90` |
| `SSH_PORT` | `65002` |
| `SSH_USERNAME` | اسم مستخدم SSH |
| `SSH_PASSWORD` | كلمة مرور SSH |
| `DEPLOY_PATH` | `~/domains/alwattanmediagroup.com/public_html` |

يمكن حذف `FTP_USERNAME` و `FTP_PASSWORD` بعد التأكد من نجاح النشر الجديد.

### تجهيز السيرفر مرة واحدة

تأكد أن مجلد النشر مستودع git مربوط بـ origin وعلى الفرع main:

```bash
cd ~/domains/alwattanmediagroup.com/public_html
git remote -v          # يجب أن يظهر origin
git rev-parse --abbrev-ref HEAD
git fetch origin main && git reset --hard origin/main
```

إن لم يكن مستودع git، انسخ `.env` جانباً ثم استنسخ المشروع في المجلد وأعده.

### ماذا تفعل الورشة

1. `git fetch` + `git reset --hard origin/main` — مطابقة تامة.
   `.env` و `public/uploads` مستثنيان في gitignore فلا يمسّهما.
2. `composer install` — **فقط** إن تغيّر `composer.json/lock`.
3. حذف `bootstrap/cache/*.php` ثم مسح كل أنواع الكاش و `package:discover`.
4. `php artisan migrate --force` — كل الترحيلات محمية بـ `hasTable`/`hasColumn`.
   **لا تشغّل `db:seed`** (يعيد تعيين كلمة مرور المدير) — لذا هي غير مضمّنة.
5. ضبط صلاحيات `storage` و `bootstrap/cache` و `public/uploads`.
6. التحقق من أن الموقع يرد بـ 200/301/302، وإلا فشلت الورشة بوضوح.

لتشغيل نشر بلا ترحيلات: `Actions ← نشر الموقع ← Run workflow` وألغِ خيار الترحيلات.

### تحويل مجلد السيرفر إلى مستودع git (مرة واحدة)

مجلد النشر على السيرفر **لم يكن مستودع git** (`fatal: not a git repository`)،
خلافاً لما افترضته الخطوة ٢ في أعلى هذا الملف. الورشة الجديدة تعتمد عليه، فحوّله:

```bash
ssh -p 65002 u617249374@147.93.54.90
cd ~/domains/alwattanmediagroup.com/public_html

# ١) نسخة احتياطية للملفات التي لا يعرفها git
cp .env ~/env_backup_$(date +%Y%m%d)
tar -czf ~/uploads_backup_$(date +%Y%m%d).tar.gz public/uploads

# ٢) الربط بالمستودع
git init
git remote add origin https://github.com/essamo96/Alwattan_media_group.git
git fetch origin main

# ٣) المطابقة مع main
git reset --hard origin/main
```

`git reset --hard` يستبدل الملفات المتعقَّبة فقط. أما `.env` و `public/uploads`
و `vendor/` فهي مستثناة في `.gitignore` — لا يعرفها git ولا يمسّها. تحقّق بعدها:

```bash
ls -la .env && ls public/uploads | head
```

**إن كان المستودع خاصاً (private)** فلن ينجح `git fetch` بلا مصادقة. أنشئ
Personal Access Token من GitHub (`Settings ← Developer settings ← Tokens`)
بصلاحية `repo` فقط، ثم:

```bash
git remote set-url origin https://<TOKEN>@github.com/essamo96/Alwattan_media_group.git
```

> **أمان:** بعد `git init` يصبح مجلد `.git` داخل جذر الويب. أُضيفت قاعدتا
> `RedirectMatch 404` في `.htaccess` بالجذر لمنع الوصول إليه وإلى `.env`
> عبر المتصفح. تحقّق بفتح `https://www.alwattanmediagroup.com/.git/config`
> — يجب أن يعطي 404.

### بعد التحويل

أضف أسرار GitHub الخمسة (الجدول أعلاه) ثم شغّل الورشة من
`Actions ← نشر الموقع على السيرفر ← Run workflow`.

### المصادقة بمفتاح SSH (مُوصى به)

فشلت المصادقة بكلمة المرور بالخطأ:
`ssh: handshake failed: unable to authenticate, attempted methods [none password]`
أي أن الاتصال بالمضيف والمنفذ نجح، لكن السيرفر رفض كلمة المرور.

الورشة تقبل الاثنين: تستخدم `SSH_KEY` إن وُجد، وإلا `SSH_PASSWORD`.
المفتاح أفضل — لا يتأثر بتغيير كلمة المرور، ولا برفض بعض السيرفرات
للمصادقة بكلمة مرور من عناوين خارجية.

**من جلسة SSH على السيرفر:**

```bash
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -N "" -C "github-actions"
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

# اطبع المفتاح الخاص وانسخه كاملاً
cat ~/.ssh/github_deploy
```

انسخ الناتج **كاملاً** بما فيه سطرا البداية والنهاية:

```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

ضعه في سر جديد اسمه `SSH_KEY`، ثم احذف الخاص من السيرفر:

```bash
rm ~/.ssh/github_deploy
```

بعدها يمكن حذف سر `SSH_PASSWORD` نهائياً.

**إن استمر الفشل**، تحقق من `SSH_USERNAME` (يجب أن يكون `u617249374` بلا
مسافات أو أسطر زائدة عند اللصق) ومن أن SSH مفعّل في hPanel.

---

## ملاحظة: الورشة الفعّالة هي FTP

أُعيدت [.github/workflows/main.yml](.github/workflows/main.yml) إلى نسخة FTP
بعد رفع الكود إلى السيرفر يدوياً. قسم SSH أعلاه يبقى موثّقاً كخيار بديل لكنه
**غير مفعّل** — لا حاجة لأسرار `SSH_*`، والمطلوب هو `FTP_USERNAME` و `FTP_PASSWORD`.

الاستثناء `public/uploads/**` مُبقى في قائمة `exclude` عمداً: يمنع أي نشر مستقبلي
من المساس بمرفوعات المستخدمين.

### العائق الوحيد المتبقي في نشر FTP

الأداة تنهار بالخطأ `550 /database/seeds: No such file or directory` لأن ملف
الحالة على السيرفر لا يزال يذكر مجلداً حُذف فعلاً (أعادت ترقية Laravel تسميته
إلى `seeders`). الحل **لمرة واحدة**: أنشئ المجلد فارغاً على السيرفر:

```bash
mkdir -p ~/domains/alwattanmediagroup.com/public_html/database/seeds
```

عندها تنجح عملية الحذف، ويُكتب ملف الحالة محدَّثاً، ولا تتكرر المشكلة.
