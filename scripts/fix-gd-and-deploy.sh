#!/bin/bash
#
# يشخّص مشكلة "Some error occured during uploading" في مدير الملفات ويحاول حلها،
# ثم يكمل مزامنة الكود (composer install، مسح الكاش، الترحيلات) اذا امكن.
#
# التنفيذ عبر SSH:
#   cd ~/domains/alwattanmediagroup.com/public_html
#   bash scripts/fix-gd-and-deploy.sh
#
# تنبيه مهم: هذا السكربت لا يستطيع تفعيل اضافة GD بنفسه اذا كانت معطلة على
# مستوى معالج PHP في hPanel — لا صلاحية للمستخدم العادي لذلك عبر SSH.
# كما ان فحص GD هنا يتم عبر CLI (php الذي يشغّل هذا السكربت)، وعلى بعض
# الاستضافات المشتركة (ومنها Hostinger احيانا) يكون PHP الخاص بـ SSH/CLI
# معالجا منفصلا عن PHP الذي يخدم المتصفح فعليا (PHP-FPM). فنجاح هذا الفحص
# لا يضمن نجاح الرفع من المتصفح، وفشله لا يعني بالضرورة ان الرفع سيفشل.
# التأكد الحقيقي الوحيد: تجربة رفع صورة فعليا من مدير الملفات في المتصفح.

set -e

SITE_PATH="${1:-$HOME/domains/alwattanmediagroup.com/public_html}"
cd "$SITE_PATH"

echo "============================================"
echo "١) فحص اضافة GD (من منظور CLI فقط — راجع التنبيه اعلاه)"
echo "============================================"
echo "نسخة PHP الحالية (CLI): $(php -r 'echo PHP_VERSION;')"
echo "ملف php.ini المُحمَّل (CLI): $(php --ini | grep 'Loaded Configuration' | sed 's/.*:\s*//')"

CLI_GD_OK=0
if php -m | grep -qi '^gd$'; then
    echo "✅ GD مفعّلة على PHP الخاص بـ CLI."
    CLI_GD_OK=1
else
    echo "❌ GD غير مفعّلة على PHP الخاص بـ CLI."
fi

echo ""
echo "نجرّب حيلة .user.ini كمحاولة احتياطية لتفعيلها على PHP-FPM (المتصفح)."
echo "هذا الملف يؤثر فقط على طلبات المتصفح، ولا يمكن التحقق من نجاحه عبر"
echo "SSH — التحقق الوحيد هو تجربة الرفع الفعلي بعد تنفيذ هذا السكربت."
echo "extension=gd" > .user.ini
echo "تم انشاء: $(pwd)/.user.ini"
echo "بعض اعدادات PHP-FPM تحتاج دقائق لالتقاطه، وبعضها لا يسمح به اطلاقا —"
echo "في هذه الحالة الحل الوحيد يدوي:"
echo "  hPanel -> مواقعي -> ادارة -> PHP Configuration -> PHP Extensions"
echo "  فعّل GD (وتأكد من fileinfo و exif) -> Save"

echo ""
if [ "$CLI_GD_OK" != "1" ]; then
    echo "توقفنا هنا: composer.json الحالي يشترط ext-gd صراحة، وسيفشل"
    echo "composer install بالكامل طالما GD غير مفعّلة على CLI تحديدا."
    echo "بعد تفعيلها من hPanel (وانتظار دقيقة او اثنتين)، اعد تشغيل هذا السكربت."
    exit 1
fi

echo "============================================"
echo "٢) سحب اخر تحديث من GitHub"
echo "============================================"
if [ -d .git ]; then
    git fetch origin main
    git reset --hard origin/main
    git log -1 --oneline
else
    echo "⚠️  هذا المجلد ليس مستودع git — تخطي السحب."
    echo "   تأكد يدويا ان composer.json يحتوي \"ext-gd\": \"*\" قبل المتابعة."
fi

echo ""
echo "============================================"
echo "٣) تحديث المكتبات"
echo "============================================"
composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "============================================"
echo "٤) مسح الكاش"
echo "============================================"
rm -f bootstrap/cache/*.php
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan package:discover

echo ""
echo "============================================"
echo "٥) الترحيلات (محمية بـ hasTable/hasColumn، آمنة دائما)"
echo "============================================"
php artisan migrate --force

echo ""
echo "============================================"
echo "٦) الصلاحيات"
echo "============================================"
chmod -R 775 storage bootstrap/cache
[ -d public/uploads ] && chmod -R 775 public/uploads

echo ""
echo "✅ اكتمل تنفيذ السكربت."
echo "التأكد الحقيقي: افتح admin/file_manager وجرّب رفع صورة فعليا الان."
echo "ان فشل الرفع رغم كل هذا، فعّل GD يدويا من hPanel كما هو موضح اعلاه"
echo "ثم اعد تشغيل السكربت."
