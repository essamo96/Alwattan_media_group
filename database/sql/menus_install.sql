-- ============================================================================
--  تركيب "ادارة قوائم الموقع" يدوياً عبر phpMyAdmin
--  بديل عن: php artisan migrate && php artisan db:seed
--  آمن للتشغيل اكثر من مرة (لا يكرر البيانات)
-- ============================================================================

-- ---------------------------------------------------------------------------
-- 1) جدول القوائم
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `menus` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255) NULL,
  `url` VARCHAR(255) NULL,
  `icon` VARCHAR(255) NULL,
  `image` VARCHAR(255) NULL,
  `target` VARCHAR(255) NOT NULL DEFAULT '_self',
  `type` TINYINT NOT NULL DEFAULT 0,
  `sort` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- تسجيل المايجريشن حتى لا يحاول ارتيزان انشاء الجدول مرة اخرى
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_07_31_000000_create_menus_table', (SELECT IFNULL(MAX(`batch`), 0) + 1 FROM `migrations` m)
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` m2 WHERE m2.`migration` = '2026_07_31_000000_create_menus_table'
);

-- ---------------------------------------------------------------------------
-- 2) القوائم (نفس الاسماء والمسارات الحالية + جريدة الوطن)
-- ---------------------------------------------------------------------------
INSERT INTO `menus` (`name_ar`, `name_en`, `url`, `icon`, `image`, `target`, `type`, `sort`, `status`, `created_at`, `updated_at`)
SELECT * FROM (
  SELECT 'الرئيسية' AS a, 'Home' AS b, '#section-hero' AS c, 'fa fa-home' AS d, NULL AS e, '_self' AS f, 0 AS g, 1 AS h, 1 AS i, NOW() AS j, NOW() AS k
  UNION ALL SELECT 'من نحن', 'About Us', '#section-about', 'fa fa-info-circle', NULL, '_self', 0, 2, 1, NOW(), NOW()
  UNION ALL SELECT 'خدماتنا', 'Our Services', '#section-services', 'fa fa-cogs', NULL, '_self', 0, 3, 1, NOW(), NOW()
  UNION ALL SELECT 'مقالات', 'Blog', '#section-schedule', 'fa fa-pencil-square-o', NULL, '_self', 0, 4, 1, NOW(), NOW()
  UNION ALL SELECT 'شركاؤنا', 'Partners', '#section-partners', 'fa fa-handshake-o', NULL, '_self', 0, 5, 1, NOW(), NOW()
  UNION ALL SELECT 'اتصل بنا', 'Contact Us', '#section-contact', 'fa fa-envelope', NULL, '_self', 0, 6, 1, NOW(), NOW()
  UNION ALL SELECT 'جريدة الوطن', 'Al Wattan Newspaper', 'https://alwattan.ps', 'fa fa-newspaper-o', 'wattan-newspaper.png', '_blank', 0, 7, 1, NOW(), NOW()
) AS src
WHERE NOT EXISTS (SELECT 1 FROM `menus` mm WHERE mm.`name_ar` = src.a);

-- ---------------------------------------------------------------------------
-- 3) مجموعة الصلاحيات
-- ---------------------------------------------------------------------------
INSERT INTO `permissions_group` (`name`)
SELECT 'ادارة قوائم الموقع'
WHERE NOT EXISTS (SELECT 1 FROM `permissions_group` pg WHERE pg.`name` = 'ادارة قوائم الموقع');

-- ---------------------------------------------------------------------------
-- 4) الصلاحيات الست
-- ---------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `group_id`, `guard_name`, `created_at`, `updated_at`)
SELECT src.n,
       (SELECT pg.id FROM `permissions_group` pg WHERE pg.`name` = 'ادارة قوائم الموقع' LIMIT 1),
       IFNULL((SELECT p2.guard_name FROM `permissions` p2 LIMIT 1), 'admin'),
       NOW(), NOW()
FROM (
  SELECT 'admin.menus.view' AS n
  UNION ALL SELECT 'admin.menus.add'
  UNION ALL SELECT 'admin.menus.edit'
  UNION ALL SELECT 'admin.menus.delete'
  UNION ALL SELECT 'admin.menus.status'
  UNION ALL SELECT 'admin.menus.sort'
) AS src
WHERE NOT EXISTS (SELECT 1 FROM `permissions` p WHERE p.`name` = src.n);

-- ---------------------------------------------------------------------------
-- 5) منح الصلاحيات للدور صاحب اكبر عدد صلاحيات (دور مدير النظام)
-- ---------------------------------------------------------------------------
INSERT INTO `role_has_permissions` (`role_id`, `permission_id`)
SELECT top_role.role_id, p.id
FROM `permissions` p
CROSS JOIN (
  SELECT rhp.role_id
  FROM `role_has_permissions` rhp
  GROUP BY rhp.role_id
  ORDER BY COUNT(*) DESC
  LIMIT 1
) AS top_role
WHERE p.`name` LIKE 'admin.menus.%'
  AND NOT EXISTS (
    SELECT 1 FROM `role_has_permissions` r2
    WHERE r2.`role_id` = top_role.role_id AND r2.`permission_id` = p.id
  );

-- ---------------------------------------------------------------------------
-- للتحقق من النتيجة
-- ---------------------------------------------------------------------------
-- SELECT * FROM `menus` ORDER BY `sort`;
-- SELECT r.name AS role, p.name AS permission
--   FROM `role_has_permissions` rhp
--   JOIN `roles` r ON r.id = rhp.role_id
--   JOIN `permissions` p ON p.id = rhp.permission_id
--  WHERE p.name LIKE 'admin.menus.%';
