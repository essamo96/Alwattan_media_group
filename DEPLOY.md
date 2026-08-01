# دليل النشر — مجموعة الوطن الإعلامية

**الحالة الحالية (2026-08-01):** آلية النشر الآلي الفعّالة هي **SSH**
عبر GitHub Actions، وتُشغَّل تلقائياً عند أي `push` إلى `main`.

**لماذا SSH لا FTP:** منفذ FTP (21) يرد بـ `ETIMEDOUT` من عناوين GitHub Actions
تحديداً — جدار حماية الاستضافة يُسقط حزمه بصمت من عناوين مراكز البيانات
السحابية بينما يسمح باتصالك اليدوي من عنوان منزلي. الدليل: منفذ SSH (65002)
استجاب وتفاوض على المصادقة بنجاح من نفس عناوين GitHub في اختبار سابق —
فقط كلمة المرور رُفضت حينها، لا الاتصال نفسه. هذا ليس عائقاً عابراً، بل خاصية
ثابتة في هذه الاستضافة، فـFTP غير صالح كآلية نشر آلي دائم من GitHub Actions.

---

## ١. آلية النشر: SSH عبر GitHub Actions

مُعرَّفة في [.github/workflows/main.yml](.github/workflows/main.yml). السيرفر
يسحب نفس الـcommit من GitHub (`git fetch` + `git reset --hard`)، فيتطابق
المحلي والمستودع والسيرفر بحكم التصميم لا بالمزامنة.

### الأسرار المطلوبة

`Settings ← Secrets and variables ← Actions`

| السر | القيمة |
|---|---|
| `SSH_HOST` | `147.93.54.90` |
| `SSH_PORT` | `65002` |
| `SSH_USERNAME` | اسم مستخدم SSH |
| `SSH_KEY` | **مفتاح خاص** — انظر أدناه، هو طريقة المصادقة المُوصى بها |
| `SSH_PASSWORD` | بديل احتياطي إن تعذّر إعداد المفتاح (أقل موثوقية) |
| `DEPLOY_PATH` | `~/domains/alwattanmediagroup.com/public_html` |

الورشة تستخدم `SSH_KEY` إن وُجد، وإلا تعود لـ`SSH_PASSWORD`.

### توليد مفتاح SSH (مرة واحدة)

من جلسة SSH على السيرفر:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -N "" -C "github-actions"
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 700 ~/.ssh && chmod 600 ~/.ssh/authorized_keys
cat ~/.ssh/github_deploy
```

انسخ ناتج الأمر الأخير **كاملاً** (بما فيه سطرا `BEGIN`/`END`) إلى سر `SSH_KEY`،
ثم احذف النسخة من السيرفر: `rm ~/.ssh/github_deploy`.

### تجهيز مجلد النشر (مرة واحدة، إن لم يكن مستودع git بعد)

الورشة تُنشئ `git init` تلقائياً إن لم يجده، فلا حاجة لخطوة يدوية عادةً.
للتحقق يدوياً أو حل مشاكل الصلاحيات:

```bash
cd ~/domains/alwattanmediagroup.com/public_html
git remote -v
```

`git reset --hard` يستبدل الملفات المتعقَّبة في git فقط. `.env` و
`public/uploads` و `vendor/` مستثناة في `.gitignore` — لا يعرفها git ولا يمسّها.

> **أمان:** بعد `git init` يصبح مجلد `.git` داخل جذر الويب. `.htaccess`
> بالجذر يحجب الوصول إليه (`RedirectMatch 404 /\.git`). تحقّق دورياً بفتح
> `https://www.alwattanmediagroup.com/.git/config` — يجب أن يعطي 404.

### ماذا تفعل الورشة

1. `git init` عند غياب `.git`، ثم `git fetch` بتوكن GitHub قصير العمر
   (لا يُخزَّن على السيرفر) و`git reset --hard` — مطابقة تامة مع `main`.
2. `composer install` — فقط إن تغيّرت بصمة `composer.lock` أو غاب `vendor/`.
3. حذف `bootstrap/cache/*.php` ومسح كل أنواع الكاش و`package:discover`.
4. `php artisan migrate --force` — كل الترحيلات محمية بـ`hasTable`/`hasColumn`.
   **لا تشغّل `db:seed`** (يعيد تعيين كلمة مرور المدير) — غير مضمّنة عمداً.
5. ضبط صلاحيات `storage` و`bootstrap/cache` و`public/uploads`.
6. خطوة تحقق: تفشل الورشة بوضوح إن لم يستجب الموقع بـ200/301/302.

لتشغيل نشر بلا ترحيلات: `Actions ← نشر الموقع على السيرفر ← Run workflow`
وألغِ خيار الترحيلات.

---

## ٢. التحقق بعد أي نشر

في المتصفح:

- `/` و `/ar` — تظهر بتنسيقها الكامل (CSS)، لا HTML خام.
- `/admin/login` — لوجو جريدة الوطن، وتسجيل الدخول يعمل.
- `https://www.alwattanmediagroup.com/.git/config` — يجب أن يعطي 404.

---

## ٣. في حال تعطّل الموقع بعد نشر

```bash
cd ~/domains/alwattanmediagroup.com
cp -r public_html public_html_backup_$(date +%Y%m%d)   # قبل أي محاولة إصلاح
```

راجع `storage/logs/laravel.log` أولاً — يذكر السبب الدقيق في أغلب الحالات
(جدول مفقود، كاش قديم، صلاحيات ملفات). إن تعذّر الإصلاح سريعاً، ارجع لنسخة
احتياطية سابقة إن وُجدت.

---

## مرجع: قرارات تصميم يجب معرفتها

| القرار | السبب |
|---|---|
| SSH لا FTP كآلية نشر | منفذ FTP (21) محجوب بصمت من عناوين GitHub Actions تحديداً (`ETIMEDOUT`)؛ منفذ SSH يستجيب من نفس العناوين. |
| `public/` مُتعقَّب في git بالكامل عدا `uploads/` | كان مُستثنى كلياً سابقاً، فلم تكن أي نسخة أصول قابلة للاسترجاع محلياً. حجمه بعد تنظيف الأصول غير المستخدمة 30 ميغا فقط، فأصبح تتبعه ممكناً. |
| `bootstrap/cache/*.php` غير متعقَّب | كان كاش Laravel 7 مجمَّعاً ومتعقَّباً، ويطمس كاش أي نسخة أحدث عند النشر — هذا كان سبب خطأ `Fideloper\Proxy\TrustedProxyServiceProvider not found`. |
| الترحيلات الجديدة محمية بـ `hasTable`/`hasColumn` | تُصلح قاعدة بيانات مستوردة كانت تفتقد جداولاً وأعمدة، وتبقى آمنة التشغيل على أي نسخة سيرفر حالية أو مستقبلية دون تكرار أو تعارض. |
| `AdminUserSeeder` غير مُشغَّل تلقائياً عبر `db:seed` العام | يعيد تعيين كلمة مرور `admin` في كل مرة — خطر على بيئة حية. |
