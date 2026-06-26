<?php
/**
 * Flat Headless React + JSON CMS in one index.php
 * ------------------------------------------------
 * Upload this file as index.php. It auto-creates:
 *   data.json
 *   .htaccess
 *   assets/.htaccess
 *   assets/uploads/
 *   assets/gallery/
 *   assets/img/
 *   assets/css/
 *   assets/js/
 *   assets/fonts/
 *   assets/docs/
 *
 * IMPORTANT BEFORE PRODUCTION:
 * 1) Change CMS_SECRET.
 * 2) Add real emails to ADMIN_EMAILS.
 * 3) Configure PHP mail() or replace cms_send_mail() with SMTP.
 * 4) Set DEV_SHOW_LOGIN_LINK to false.
 */

session_start();

define('CMS_VERSION', '0.2.0');
define('DATA_FILE', __DIR__ . '/data.json');
define('HTACCESS_FILE', __DIR__ . '/.htaccess');
define('ASSETS_DIR', __DIR__ . '/assets');
define('ASSETS_HTACCESS_FILE', ASSETS_DIR . '/.htaccess');
define('UPLOAD_DIR', ASSETS_DIR . '/uploads');
define('GALLERY_DIR', ASSETS_DIR . '/gallery');
define('IMG_DIR', ASSETS_DIR . '/img');
define('CSS_DIR', ASSETS_DIR . '/css');
define('JS_DIR', ASSETS_DIR . '/js');
define('FONTS_DIR', ASSETS_DIR . '/fonts');
define('DOCS_DIR', ASSETS_DIR . '/docs');

define('CMS_SECRET', 'CHANGE_THIS_TO_A_RANDOM_64_CHARACTER_SECRET');
define('DEV_SHOW_LOGIN_LINK', false);

const ADMIN_EMAILS = [
  'admin@example.com',
];

function cms_default_data(): array {
  return [
    'version' => CMS_VERSION,
    'settings' => [
      'siteName' => 'Flat React JSON CMS',
      'baseUrl' => '',
      'defaultLang' => 'en',
      'languages' => [
        ['code' => 'en', 'label' => 'EN'],
        ['code' => 'lv', 'label' => 'LV'],
        ['code' => 'ru', 'label' => 'RU'],
      ],
      'seo' => [
        'title' => 'Flat React JSON CMS - Fast one page CMS',
        'description' => 'A fast single-file PHP and JSON headless CMS rendered by React.',
        'keywords' => 'flat file cms, react cms, json cms, bootstrap cms',
        'image' => '/assets/img/og.jpg',
        'canonical' => '',
        'robots' => 'index,follow'
      ],
      'htaccess' => [
        'autoSync' => true,
        'prettyUrls' => true,
        'forceHttps' => false,
        'removeWww' => false,
        'assetCacheDays' => 365,
        'protectJson' => true
      ],
      'theme' => [
        'brand' => '#0d6efd',
        'brand2' => '#6610f2',
        'accent' => '#20c997',
        'text' => '#1f2937',
        'muted' => '#6b7280',
        'bg' => '#ffffff',
        'softBg' => '#f6f8fb',
        'radius' => '22px',
        'grid' => 'container',
        'font' => 'Inter, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif',
        'customScss' => '',
        'customCss' => ''
      ],
      'libraries' => [
        'bootstrap' => true,
        'bootstrapIcons' => true,
        'swiper' => false,
        'aos' => false,
        'glightbox' => false,
        'sassBuilder' => false,
        'hcaptcha' => false,
      ],
      'analytics' => [
        'ga4Id' => '',
        'loadAfterCookieConsent' => true
      ],
      'cookies' => [
        'enabled' => true,
        'message' => 'We use cookies for analytics and site functionality.',
        'acceptText' => 'Accept',
        'declineText' => 'Decline'
      ],
      'hcaptcha' => [
        'siteKey' => '',
        'secret' => ''
      ],
      'forms' => [
        'emailTo' => '',
        'storeSubmissions' => true,
        'submissions' => []
      ]
    ],
    'header' => [
      'logoText' => 'FlatCMS',
      'logoImage' => '',
      'sticky' => true,
      'menu' => [
        ['label' => 'Home', 'href' => '#home', 'children' => []],
        ['label' => 'About', 'href' => '#about', 'children' => []],
        ['label' => 'Services', 'href' => '#services', 'children' => []],
        ['label' => 'Gallery', 'href' => '#gallery', 'children' => []],
        ['label' => 'Blog', 'href' => '#blog', 'children' => []],
        ['label' => 'Contact', 'href' => '#contact', 'children' => []],
      ]
    ],
    'hero' => [
      'enabled' => true,
      'slides' => [
        [
          'eyebrow' => 'One file CMS',
          'title' => 'Flat Headless React CMS powered by JSON',
          'text' => 'Inline edit content, manage SEO, build sections, galleries, blogs, products, and forms without a database.',
          'buttonText' => 'Start editing',
          'buttonHref' => '#about',
          'image' => '',
          'bg' => '',
          'seoH1' => true
        ]
      ]
    ],
    'about' => [
      'enabled' => true,
      'title' => 'About us',
      'subtitle' => 'A clean Bootstrap-style landing page with inline admin UX.',
      'image' => '',
      'body' => '<p>Use the admin icon in the footer to request a secure magic login link. After login, click text directly to edit it and use the admin panel for menus, SEO, colors, uploads, forms, libraries, and redirects.</p>'
    ],
    'services' => [
      'enabled' => true,
      'title' => 'Services',
      'subtitle' => 'Cards with AJAX-style load more.',
      'batch' => 3,
      'showButton' => true,
      'buttonText' => 'Load more services',
      'items' => [
        ['title' => 'Headless JSON content', 'text' => 'All editable content is stored in data.json.', 'icon' => 'bi bi-braces', 'buttonText' => 'Read more', 'buttonHref' => '#contact'],
        ['title' => 'Inline editing', 'text' => 'Edit headings, text, cards, and HTML blocks directly on the page.', 'icon' => 'bi bi-pencil-square', 'buttonText' => 'Read more', 'buttonHref' => '#contact'],
        ['title' => 'SEO controls', 'text' => 'Set global and post-level meta title, descriptions, OG images, canonical URLs, and JSON-LD.', 'icon' => 'bi bi-search-heart', 'buttonText' => 'Read more', 'buttonHref' => '#contact'],
      ]
    ],
    'gallery' => [
      'enabled' => true,
      'title' => 'Gallery',
      'subtitle' => 'Directory-based photos from assets/gallery or uploaded files.',
      'batch' => 6,
      'showButton' => true,
      'buttonText' => 'Load more photos',
      'items' => [
        ['src' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=80', 'alt' => 'Office workspace'],
        ['src' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=80', 'alt' => 'Team meeting'],
        ['src' => 'https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?auto=format&fit=crop&w=900&q=80', 'alt' => 'Creative team'],
      ]
    ],
    'testimonials' => [
      'enabled' => true,
      'title' => 'Testimonials',
      'subtitle' => 'Customer quote cards with load more.',
      'batch' => 3,
      'showButton' => true,
      'buttonText' => 'Load more testimonials',
      'items' => [
        ['name' => 'Client Name', 'role' => 'Founder', 'text' => 'This CMS gives us a simple editing flow without a database.', 'photo' => '']
      ]
    ],
    'products' => [
      'enabled' => true,
      'title' => 'Products',
      'subtitle' => 'Product cards with SEO data and modal details.',
      'batch' => 3,
      'showButton' => true,
      'buttonText' => 'Load more products',
      'items' => [
        [
          'title' => 'Starter Website',
          'slug' => 'starter-website',
          'price' => 'EUR 550',
          'image' => '',
          'text' => 'A fast one-page website package.',
          'body' => '<p>Perfect for a small business landing page.</p>',
          'seo' => ['title' => 'Starter Website', 'description' => 'Starter website product page.', 'image' => '']
        ]
      ]
    ],
    'blog' => [
      'enabled' => true,
      'title' => 'Blog',
      'subtitle' => 'JSON posts with modal views, SEO fields, and share buttons.',
      'batch' => 3,
      'showButton' => true,
      'buttonText' => 'Load more posts',
      'items' => [
        [
          'title' => 'How this flat CMS works',
          'slug' => 'how-flat-cms-works',
          'date' => date('Y-m-d'),
          'image' => '',
          'excerpt' => 'PHP handles JSON storage and admin APIs. React renders the one-page DOM.',
          'body' => '<p>This CMS keeps content in JSON and renders it with React. The same index.php serves the frontend, APIs, admin login, uploads, forms, redirects, and SEO metadata.</p>',
          'seo' => ['title' => 'How this flat CMS works', 'description' => 'Learn how a one-file React and PHP flat CMS works.', 'image' => '']
        ]
      ]
    ],
    'pages' => [
      'enabled' => true,
      'items' => [
        [
          'title' => 'Privacy Policy',
          'slug' => 'privacy-policy',
          'excerpt' => 'Privacy policy page managed from JSON.',
          'body' => '<p>Update this privacy policy from the JSON editor or through future page block controls.</p>',
          'seo' => ['title' => 'Privacy Policy', 'description' => 'Privacy policy for this website.', 'image' => '']
        ]
      ]
    ],
    'contact' => [
      'enabled' => true,
      'title' => 'Contact us',
      'subtitle' => 'Map, details, modal form, and social share/follow.',
      'mapEmbed' => 'https://www.google.com/maps?q=Riga%2C%20Latvia&output=embed',
      'details' => [
        'address' => 'Riga, Latvia',
        'phone' => '+371 00 000 000',
        'email' => 'hello@example.com',
        'hours' => 'Mon-Fri 09:00-18:00'
      ],
      'formButtonText' => 'Send request'
    ],
    'cta' => [
      'enabled' => true,
      'title' => 'Ready to launch a fast flat-file site?',
      'text' => 'Open the admin panel, change the content, upload images, and publish.',
      'buttonText' => 'Open contact form'
    ],
    'footer' => [
      'copy' => 'Copyright {year} FlatCMS. All rights reserved.',
      'menu' => [
        ['label' => 'Privacy', 'href' => '/page/privacy-policy'],
        ['label' => 'Contact', 'href' => '#contact']
      ],
      'social' => [
        ['label' => 'Facebook', 'url' => '#', 'icon' => 'bi bi-facebook'],
        ['label' => 'Instagram', 'url' => '#', 'icon' => 'bi bi-instagram'],
        ['label' => 'LinkedIn', 'url' => '#', 'icon' => 'bi bi-linkedin'],
      ]
    ],
    'redirects' => [
      ['from' => '/old-page', 'to' => '/', 'status' => 301, 'enabled' => false]
    ],
    'embeds' => [],
  ];
}

function cms_asset_dirs(): array {
  return [ASSETS_DIR, UPLOAD_DIR, GALLERY_DIR, IMG_DIR, CSS_DIR, JS_DIR, FONTS_DIR, DOCS_DIR];
}

function cms_route_prefix(): string {
  $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  return $dir === '' ? '/' : $dir . '/';
}

function cms_htaccess_settings(array $data): array {
  return array_replace([
    'autoSync' => true,
    'prettyUrls' => true,
    'forceHttps' => false,
    'removeWww' => false,
    'assetCacheDays' => 365,
    'protectJson' => true,
  ], $data['settings']['htaccess'] ?? []);
}

function cms_root_htaccess(array $data): string {
  $s = cms_htaccess_settings($data);
  $cacheDays = max(1, min(365, (int)($s['assetCacheDays'] ?? 365)));
  $cacheSeconds = $cacheDays * 86400;
  $forceHttps = !empty($s['forceHttps']) ? "RewriteCond %{HTTPS} !=on\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n" : "# To force HTTPS from the CMS admin, enable settings.htaccess.forceHttps.\n";
  $removeWww = !empty($s['removeWww']) ? "RewriteCond %{HTTP_HOST} ^www\\.(.+)$ [NC]\nRewriteRule ^ https://%1%{REQUEST_URI} [L,R=301]\n" : "# To redirect www to non-www, enable settings.htaccess.removeWww.\n";
  $protectJson = !empty($s['protectJson']) ? <<<'TXT'
<FilesMatch "(^\.|data\.json$|.*\.tmp$|.*\.bak$|.*\.old$|composer\.(json|lock)$|package(-lock)?\.json$|yarn\.lock$|vite\.config\.|webpack\.config\.|phpunit\.)">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
  <IfModule !mod_authz_core.c>
    Deny from all
  </IfModule>
</FilesMatch>
TXT : '';

  return rtrim("# BEGIN FlatCMS\n" . <<<'HTA'
# Managed by index.php. Custom rules can be placed before BEGIN or after END.
Options -Indexes
DirectoryIndex index.php
FileETag None

<IfModule mod_negotiation.c>
  Options -MultiViews
</IfModule>

<IfModule mod_rewrite.c>
  RewriteEngine On
HTA
  . "\n" . $forceHttps . $removeWww . <<<'HTA'

  # Let real files and directories load directly.
  RewriteCond %{REQUEST_FILENAME} -f [OR]
  RewriteCond %{REQUEST_FILENAME} -d
  RewriteRule ^ - [L]

  # Pretty SEO URLs for JSON content.
  RewriteRule ^blog/([A-Za-z0-9-]+)/?$ index.php?cms_route=blog&slug=$1 [L,QSA,B]
  RewriteRule ^posts/([A-Za-z0-9-]+)/?$ index.php?cms_route=blog&slug=$1 [L,QSA,B]
  RewriteRule ^product/([A-Za-z0-9-]+)/?$ index.php?cms_route=products&slug=$1 [L,QSA,B]
  RewriteRule ^products/([A-Za-z0-9-]+)/?$ index.php?cms_route=products&slug=$1 [L,QSA,B]
  RewriteRule ^page/([A-Za-z0-9-]+)/?$ index.php?cms_route=pages&slug=$1 [L,QSA,B]
  RewriteRule ^pages/([A-Za-z0-9-]+)/?$ index.php?cms_route=pages&slug=$1 [L,QSA,B]

  # Optional short page URL: /about-us -> JSON page slug about-us.
  RewriteRule ^([A-Za-z0-9-]+)/?$ index.php?cms_route=pages&slug=$1 [L,QSA,B]

  # Fallback to the React shell.
  RewriteRule . index.php [L,QSA]
</IfModule>

HTA
  . $protectJson . "\n" . <<<'HTA'
<IfModule mod_headers.c>
  Header unset ETag
  Header always set X-Content-Type-Options "nosniff"
  Header always set X-Frame-Options "SAMEORIGIN"
  Header always set Referrer-Policy "strict-origin-when-cross-origin"
  Header always set Permissions-Policy "camera=(), microphone=(), payment=()"
</IfModule>

<IfModule mod_mime.c>
  AddType image/avif avif
  AddType image/webp webp
  AddType image/svg+xml svg svgz
  AddType font/woff2 woff2
</IfModule>

<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css text/javascript application/javascript application/json application/xml application/rss+xml image/svg+xml font/ttf font/otf font/woff font/woff2
</IfModule>

<IfModule mod_brotli.c>
  AddOutputFilterByType BROTLI_COMPRESS text/plain text/html text/xml text/css text/javascript application/javascript application/json application/xml application/rss+xml image/svg+xml font/ttf font/otf font/woff font/woff2
</IfModule>

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresDefault "access plus 1 month"
  ExpiresByType text/html "access plus 0 seconds"
  ExpiresByType application/json "access plus 0 seconds"
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"
  ExpiresByType image/avif "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
  ExpiresByType font/woff "access plus 1 year"
  ExpiresByType application/pdf "access plus 1 month"
</IfModule>

<IfModule mod_headers.c>
  <FilesMatch "\.(?:css|js|mjs|jpg|jpeg|png|gif|webp|avif|svg|ico|woff|woff2|ttf|otf)$">
HTA
  . "\n" . '    Header set Cache-Control "public, max-age=' . $cacheSeconds . ', immutable"' . "\n" . <<<'HTA'
  </FilesMatch>
  <FilesMatch "\.(?:html|php|json)$">
    Header set Cache-Control "no-cache, no-store, must-revalidate"
  </FilesMatch>
</IfModule>
HTA
  . "\n# END FlatCMS\n");
}

function cms_assets_htaccess(array $data): string {
  $s = cms_htaccess_settings($data);
  $cacheDays = max(1, min(365, (int)($s['assetCacheDays'] ?? 365)));
  $cacheSeconds = $cacheDays * 86400;
  return "# BEGIN FlatCMS\n" . <<<'HTA'
# Managed by index.php. Protects uploads while allowing static assets.
Options -Indexes

<FilesMatch "(^\.|\.(?:php|phtml|php[0-9]?|phar|cgi|pl|py|sh|asp|aspx|jsp)$)">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
  <IfModule !mod_authz_core.c>
    Deny from all
  </IfModule>
</FilesMatch>

<IfModule mod_php.c>
  php_flag engine off
</IfModule>

<IfModule mod_mime.c>
  AddType image/avif avif
  AddType image/webp webp
  AddType image/svg+xml svg svgz
  AddType font/woff2 woff2
</IfModule>

<IfModule mod_headers.c>
  Header always set X-Content-Type-Options "nosniff"
  <FilesMatch "\.(?:css|js|mjs|jpg|jpeg|png|gif|webp|avif|svg|ico|woff|woff2|ttf|otf)$">
HTA
  . "\n" . '    Header set Cache-Control "public, max-age=' . $cacheSeconds . ', immutable"' . "\n" . <<<'HTA'
  </FilesMatch>
</IfModule>

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresDefault "access plus 1 month"
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"
  ExpiresByType image/avif "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
  ExpiresByType font/woff "access plus 1 year"
</IfModule>
HTA
  . "\n# END FlatCMS\n";
}

function cms_upsert_managed_block(string $file, string $block): array {
  $start = '# BEGIN FlatCMS';
  $end = '# END FlatCMS';
  $existing = file_exists($file) ? (string)file_get_contents($file) : '';
  $new = $existing;
  if (str_contains($existing, $start) && str_contains($existing, $end)) {
    $new = preg_replace_callback('/# BEGIN FlatCMS.*?# END FlatCMS\s*/s', fn() => rtrim($block) . "\n", $existing, 1);
  } else {
    $new = rtrim($existing) . ($existing !== '' ? "\n\n" : '') . rtrim($block) . "\n";
  }
  if ($new === $existing) return ['file' => basename($file), 'changed' => false, 'ok' => true, 'message' => 'Already synced'];
  $ok = @file_put_contents($file, $new, LOCK_EX) !== false;
  return ['file' => basename($file), 'changed' => $ok, 'ok' => $ok, 'message' => $ok ? 'Synced' : 'Could not write file'];
}

function cms_sync_htaccess_files(array $data): array {
  $settings = cms_htaccess_settings($data);
  if (empty($settings['autoSync'])) {
    return ['ok' => true, 'skipped' => true, 'message' => 'Auto sync disabled'];
  }
  if (!is_dir(ASSETS_DIR)) @mkdir(ASSETS_DIR, 0755, true);
  $root = cms_upsert_managed_block(HTACCESS_FILE, cms_root_htaccess($data));
  $assets = cms_upsert_managed_block(ASSETS_HTACCESS_FILE, cms_assets_htaccess($data));
  return ['ok' => !empty($root['ok']) && !empty($assets['ok']), 'root' => $root, 'assets' => $assets];
}

function cms_htaccess_status(array $data): array {
  return [
    'rootExists' => file_exists(HTACCESS_FILE),
    'assetsExists' => file_exists(ASSETS_HTACCESS_FILE),
    'rootWritable' => is_writable(__DIR__) || is_writable(HTACCESS_FILE),
    'assetsWritable' => is_writable(ASSETS_DIR) || is_writable(ASSETS_HTACCESS_FILE),
    'settings' => cms_htaccess_settings($data),
  ];
}

function cms_ensure_files(): void {
  foreach (cms_asset_dirs() as $dir) {
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }
  }
  if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode(cms_default_data(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }
}

function cms_load_data(): array {
  cms_ensure_files();
  $raw = file_get_contents(DATA_FILE);
  $data = json_decode($raw ?: '', true);
  if (!is_array($data)) {
    $data = cms_default_data();
  }
  return array_replace_recursive(cms_default_data(), $data);
}

function cms_save_data(array $data): bool {
  $data['version'] = CMS_VERSION;
  $tmp = DATA_FILE . '.tmp';
  $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  if ($json === false) {
    return false;
  }
  if (file_put_contents($tmp, $json, LOCK_EX) === false) {
    return false;
  }
  return rename($tmp, DATA_FILE);
}

function cms_json($payload, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  exit;
}

function cms_b64u(string $s): string {
  return rtrim(strtr(base64_encode($s), '+/', '-_'), '=');
}

function cms_b64u_dec(string $s): string|false {
  $pad = strlen($s) % 4;
  if ($pad) $s .= str_repeat('=', 4 - $pad);
  return base64_decode(strtr($s, '-_', '+/'));
}

function cms_make_token(string $email): string {
  $payload = cms_b64u(json_encode([
    'email' => strtolower(trim($email)),
    'exp' => time() + 15 * 60,
    'nonce' => bin2hex(random_bytes(12))
  ]));
  $sig = cms_b64u(hash_hmac('sha256', $payload, CMS_SECRET, true));
  return $payload . '.' . $sig;
}

function cms_verify_token(string $token): ?string {
  $parts = explode('.', $token, 2);
  if (count($parts) !== 2) return null;
  [$payload, $sig] = $parts;
  $expected = cms_b64u(hash_hmac('sha256', $payload, CMS_SECRET, true));
  if (!hash_equals($expected, $sig)) return null;
  $json = cms_b64u_dec($payload);
  if (!$json) return null;
  $data = json_decode($json, true);
  if (!is_array($data) || empty($data['email']) || empty($data['exp'])) return null;
  if ((int)$data['exp'] < time()) return null;
  $email = strtolower(trim($data['email']));
  if (!in_array($email, array_map('strtolower', ADMIN_EMAILS), true)) return null;
  return $email;
}

function cms_base_url(array $data): string {
  $configured = trim((string)($data['settings']['baseUrl'] ?? ''));
  if ($configured !== '') return rtrim($configured, '/');
  $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
  $scheme = $https ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  return $scheme . '://' . $host . ($path === '' ? '' : $path);
}

function cms_send_mail(string $to, string $subject, string $body): bool {
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  $headers .= "From: no-reply@" . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n";
  return @mail($to, $subject, $body, $headers);
}

function cms_is_admin(): bool {
  return !empty($_SESSION['cms_admin_email']) && in_array(strtolower($_SESSION['cms_admin_email']), array_map('strtolower', ADMIN_EMAILS), true);
}

function cms_require_admin(): void {
  if (!cms_is_admin()) cms_json(['ok' => false, 'error' => 'Unauthorized'], 401);
  $csrf = $_SERVER['HTTP_X_CMS_CSRF'] ?? '';
  if ($_SERVER['REQUEST_METHOD'] !== 'GET' && (!isset($_SESSION['cms_csrf']) || !hash_equals($_SESSION['cms_csrf'], $csrf))) {
    cms_json(['ok' => false, 'error' => 'Bad CSRF token'], 419);
  }
}

function cms_public_data(array $data): array {
  if (!cms_is_admin()) {
    unset($data['settings']['hcaptcha']['secret']);
    unset($data['settings']['forms']['submissions']);
  }
  return $data;
}

function cms_slug(string $s): string {
  $s = strtolower(trim($s));
  $s = preg_replace('/[^a-z0-9]+/', '-', $s);
  return trim($s ?: 'item', '-');
}

function cms_e(?string $s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function cms_pretty_path(string $group, string $slug): string {
  $slug = cms_slug($slug);
  if ($group === 'blog') return '/blog/' . $slug;
  if ($group === 'products') return '/product/' . $slug;
  if ($group === 'pages') return '/page/' . $slug;
  return '/?slug=' . rawurlencode($slug);
}

function cms_request_route(): array {
  $map = ['blog' => 'blog', 'posts' => 'blog', 'post' => 'blog', 'products' => 'products', 'product' => 'products', 'pages' => 'pages', 'page' => 'pages'];
  $route = strtolower(trim((string)($_GET['cms_route'] ?? ''), '/'));
  $slug = trim((string)($_GET['slug'] ?? ''), '/');
  $path = trim((string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? ''), '/');
  $script = trim(basename($_SERVER['SCRIPT_NAME'] ?? 'index.php'), '/');
  if ($path === $script) $path = '';
  $parts = $path === '' ? [] : array_values(array_filter(explode('/', $path), 'strlen'));
  if ($route !== '' && isset($map[$route])) $route = $map[$route];
  if ($slug === '' && count($parts) >= 2 && isset($map[strtolower($parts[0])])) {
    $route = $map[strtolower($parts[0])];
    $slug = $parts[1];
  } elseif ($slug === '' && count($parts) === 1 && !str_contains($parts[0], '.')) {
    $route = 'pages';
    $slug = $parts[0];
  }
  return ['group' => $route, 'slug' => $slug === '' ? '' : cms_slug($slug), 'path' => $path];
}

function cms_find_route_item(array $data): array {
  $r = cms_request_route();
  $groups = $r['group'] ? [$r['group']] : ['pages', 'blog', 'products'];
  $candidates = array_filter([$r['slug'], basename($r['path'] ?? '')]);
  foreach ($groups as $group) {
    foreach (($data[$group]['items'] ?? []) as $i => $item) {
      $itemSlug = cms_slug((string)($item['slug'] ?? ($item['title'] ?? 'item')));
      if (in_array($itemSlug, $candidates, true)) {
        return ['group' => $group, 'index' => $i, 'slug' => $itemSlug, 'item' => $item, 'path' => cms_pretty_path($group, $itemSlug)];
      }
    }
  }
  return ['group' => $r['group'], 'slug' => $r['slug'], 'item' => null, 'path' => $r['slug'] ? cms_pretty_path($r['group'] ?: 'pages', $r['slug']) : '/'];
}

function cms_find_meta(array $data): array {
  $default = $data['settings']['seo'] ?? [];
  $route = cms_find_route_item($data);
  if (!empty($route['item']) && is_array($route['item'])) {
    $item = $route['item'];
    $group = $route['group'];
    $seo = $item['seo'] ?? [];
    $type = $group === 'products' ? 'product' : ($group === 'blog' ? 'article' : 'website');
    return array_replace($default, [
      'title' => $seo['title'] ?? $item['title'] ?? ($default['title'] ?? ''),
      'description' => $seo['description'] ?? $item['excerpt'] ?? $item['text'] ?? ($default['description'] ?? ''),
      'image' => $seo['image'] ?? $item['image'] ?? ($default['image'] ?? ''),
      'type' => $type,
      'item' => $item,
      'group' => $group,
      'slug' => $route['slug'],
      'path' => $route['path'],
    ]);
  }
  return array_replace(['type' => 'website'], $default);
}

function cms_apply_redirects(array $data): void {
  if (isset($_GET['api']) || isset($_GET['login'])) return;
  $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
  foreach (($data['redirects'] ?? []) as $r) {
    if (empty($r['enabled'])) continue;
    $from = $r['from'] ?? '';
    $to = $r['to'] ?? '';
    if ($from && $to && rtrim($from, '/') === rtrim($path, '/')) {
      $status = in_array((int)($r['status'] ?? 301), [301,302,307,308], true) ? (int)$r['status'] : 301;
      header('Location: ' . $to, true, $status);
      exit;
    }
  }
}

function cms_verify_hcaptcha(array $data, string $token): bool {
  $enabled = !empty($data['settings']['libraries']['hcaptcha']);
  $secret = (string)($data['settings']['hcaptcha']['secret'] ?? '');
  if (!$enabled) return true;
  if ($secret === '' || $token === '') return false;
  $post = http_build_query(['secret' => $secret, 'response' => $token, 'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '']);
  $opts = ['http' => ['method' => 'POST', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => $post, 'timeout' => 8]];
  $res = @file_get_contents('https://hcaptcha.com/siteverify', false, stream_context_create($opts));
  $json = json_decode($res ?: '', true);
  return !empty($json['success']);
}

$data = cms_load_data();
cms_sync_htaccess_files($data);
cms_apply_redirects($data);

if (isset($_GET['login'])) {
  $email = cms_verify_token((string)$_GET['login']);
  if (!$email) {
    http_response_code(403);
    echo 'Invalid or expired login link.';
    exit;
  }
  session_regenerate_id(true);
  $_SESSION['cms_admin_email'] = $email;
  $_SESSION['cms_csrf'] = bin2hex(random_bytes(20));
  header('Location: ' . strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
  exit;
}

if (isset($_GET['api'])) {
  $api = (string)$_GET['api'];
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  if ($api === 'site') {
    cms_json([
      'ok' => true,
      'admin' => cms_is_admin(),
      'email' => $_SESSION['cms_admin_email'] ?? '',
      'csrf' => $_SESSION['cms_csrf'] ?? '',
      'route' => cms_find_route_item(cms_load_data()),
      'server' => cms_htaccess_status(cms_load_data()),
      'data' => cms_public_data(cms_load_data())
    ]);
  }

  if ($api === 'login' && $method === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '', true) ?: [];
    $email = strtolower(trim((string)($payload['email'] ?? '')));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) cms_json(['ok' => false, 'error' => 'Please enter a valid email.'], 422);
    if (!in_array($email, array_map('strtolower', ADMIN_EMAILS), true)) {
      cms_json(['ok' => false, 'error' => 'Please ask administrator access for your email.'], 403);
    }
    $token = cms_make_token($email);
    $link = cms_base_url($data) . '/index.php?login=' . rawurlencode($token);
    $sent = cms_send_mail($email, 'Your secure CMS login link', "Open this secure login link within 15 minutes:\n\n" . $link . "\n\nIf you did not request this link, ignore this email.");
    cms_json(['ok' => true, 'sent' => $sent, 'message' => $sent ? 'Secure login link sent. Please check your email.' : 'Mail was not sent. Configure PHP mail or SMTP.', 'devLink' => DEV_SHOW_LOGIN_LINK ? $link : null]);
  }

  if ($api === 'logout' && $method === 'POST') {
    cms_require_admin();
    $_SESSION = [];
    session_destroy();
    cms_json(['ok' => true]);
  }

  if ($api === 'save' && $method === 'POST') {
    cms_require_admin();
    $payload = json_decode(file_get_contents('php://input') ?: '', true);
    if (!is_array($payload)) cms_json(['ok' => false, 'error' => 'Invalid JSON'], 422);
    if (strlen(json_encode($payload)) > 5 * 1024 * 1024) cms_json(['ok' => false, 'error' => 'JSON too large'], 413);
    $current = cms_load_data();
    if (!empty($current['settings']['forms']['submissions']) && empty($payload['settings']['forms']['submissions'])) {
      $payload['settings']['forms']['submissions'] = $current['settings']['forms']['submissions'];
    }
    if (!cms_save_data($payload)) cms_json(['ok' => false, 'error' => 'Could not save data.json'], 500);
    cms_sync_htaccess_files($payload);
    cms_json(['ok' => true, 'data' => cms_public_data(cms_load_data()), 'server' => cms_htaccess_status($payload)]);
  }

  if ($api === 'sync-htaccess' && $method === 'POST') {
    cms_require_admin();
    $fresh = cms_load_data();
    $result = cms_sync_htaccess_files($fresh);
    cms_json(['ok' => !empty($result['ok']), 'message' => !empty($result['ok']) ? 'Root and assets .htaccess files are synced.' : 'Could not sync one or more .htaccess files.', 'result' => $result, 'server' => cms_htaccess_status($fresh)]);
  }

  if ($api === 'upload' && $method === 'POST') {
    cms_require_admin();
    $targets = ['uploads' => UPLOAD_DIR, 'gallery' => GALLERY_DIR, 'img' => IMG_DIR, 'docs' => DOCS_DIR];
    $targetKey = (string)($_POST['target'] ?? 'uploads');
    $target = $targets[$targetKey] ?? UPLOAD_DIR;
    if (empty($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) cms_json(['ok' => false, 'error' => 'No file uploaded'], 422);
    $f = $_FILES['file'];
    if (($f['size'] ?? 0) > 8 * 1024 * 1024) cms_json(['ok' => false, 'error' => 'Max upload is 8MB'], 413);
    $ext = strtolower(pathinfo($f['name'] ?? '', PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp','svg','pdf'];
    if (!in_array($ext, $allowed, true)) cms_json(['ok' => false, 'error' => 'File type not allowed'], 422);
    $name = date('Ymd-His') . '-' . cms_slug(pathinfo($f['name'], PATHINFO_FILENAME)) . '.' . $ext;
    $dest = $target . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) cms_json(['ok' => false, 'error' => 'Upload failed'], 500);
    $url = 'assets/' . basename($target) . '/' . rawurlencode($name);
    cms_json(['ok' => true, 'url' => $url, 'name' => $name]);
  }

  if ($api === 'form' && $method === 'POST') {
    $payload = json_decode(file_get_contents('php://input') ?: '', true) ?: [];
    if (!cms_verify_hcaptcha($data, (string)($payload['hcaptcha'] ?? ''))) cms_json(['ok' => false, 'error' => 'Captcha verification failed.'], 422);
    $entry = [
      'time' => date('c'),
      'type' => substr((string)($payload['type'] ?? 'contact'), 0, 40),
      'name' => substr(strip_tags((string)($payload['name'] ?? '')), 0, 120),
      'email' => substr(strip_tags((string)($payload['email'] ?? '')), 0, 160),
      'phone' => substr(strip_tags((string)($payload['phone'] ?? '')), 0, 80),
      'message' => substr(strip_tags((string)($payload['message'] ?? '')), 0, 3000),
    ];
    if (!filter_var($entry['email'], FILTER_VALIDATE_EMAIL)) cms_json(['ok' => false, 'error' => 'Valid email required.'], 422);
    if (!empty($data['settings']['forms']['storeSubmissions'])) {
      $data['settings']['forms']['submissions'][] = $entry;
      cms_save_data($data);
    }
    $to = trim((string)($data['settings']['forms']['emailTo'] ?? ''));
    if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
      cms_send_mail($to, 'New website form submission', "Name: {$entry['name']}\nEmail: {$entry['email']}\nPhone: {$entry['phone']}\n\n{$entry['message']}");
    }
    cms_json(['ok' => true, 'message' => 'Thank you. Your request was sent.']);
  }

  if ($api === 'search') {
    $q = strtolower(trim((string)($_GET['q'] ?? '')));
    $out = [];
    if ($q !== '') {
      foreach (['services','products','blog','gallery'] as $group) {
        foreach (($data[$group]['items'] ?? []) as $i => $item) {
          $text = strtolower(json_encode($item, JSON_UNESCAPED_UNICODE));
          if (str_contains($text, $q)) {
            $out[] = [
              'group' => $group,
              'title' => $item['title'] ?? $item['alt'] ?? ucfirst($group) . ' item',
              'text' => $item['excerpt'] ?? $item['text'] ?? $item['alt'] ?? '',
              'href' => '#' . $group,
              'index' => $i
            ];
          }
        }
      }
    }
    cms_json(['ok' => true, 'results' => $out]);
  }

  cms_json(['ok' => false, 'error' => 'Unknown API'], 404);
}

$meta = cms_find_meta($data);
$base = cms_base_url($data);
$metaImage = (string)($meta['image'] ?? '');
if ($metaImage && !preg_match('/^https?:\/\//', $metaImage)) $metaImage = rtrim($base, '/') . '/' . ltrim($metaImage, '/');
$canonical = (string)($meta['canonical'] ?? '') ?: (!empty($meta['path']) ? $base . $meta['path'] : $base . strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
$theme = $data['settings']['theme'] ?? [];
$libs = $data['settings']['libraries'] ?? [];
?>
<!doctype html>
<html lang="<?= cms_e($data['settings']['defaultLang'] ?? 'en') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="<?= cms_e($meta['robots'] ?? 'index,follow') ?>">
  <title><?= cms_e($meta['title'] ?? ($data['settings']['siteName'] ?? 'Flat CMS')) ?></title>
  <meta name="description" content="<?= cms_e($meta['description'] ?? '') ?>">
  <meta name="keywords" content="<?= cms_e($meta['keywords'] ?? '') ?>">
  <link rel="canonical" href="<?= cms_e($canonical) ?>">
  <meta property="og:title" content="<?= cms_e($meta['title'] ?? '') ?>">
  <meta property="og:description" content="<?= cms_e($meta['description'] ?? '') ?>">
  <meta property="og:type" content="<?= cms_e($meta['type'] ?? 'website') ?>">
  <meta property="og:url" content="<?= cms_e($canonical) ?>">
  <?php if ($metaImage): ?><meta property="og:image" content="<?= cms_e($metaImage) ?>"><?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <?php if (!empty($libs['bootstrap'])): ?><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><?php endif; ?>
  <?php if (!empty($libs['bootstrapIcons'])): ?><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet"><?php endif; ?>
  <?php if (!empty($libs['aos'])): ?><link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"><?php endif; ?>
  <?php if (!empty($libs['glightbox'])): ?><link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet"><?php endif; ?>
  <style>
    :root{
      --cms-brand: <?= cms_e($theme['brand'] ?? '#0d6efd') ?>;
      --cms-brand2: <?= cms_e($theme['brand2'] ?? '#6610f2') ?>;
      --cms-accent: <?= cms_e($theme['accent'] ?? '#20c997') ?>;
      --cms-text: <?= cms_e($theme['text'] ?? '#1f2937') ?>;
      --cms-muted: <?= cms_e($theme['muted'] ?? '#6b7280') ?>;
      --cms-bg: <?= cms_e($theme['bg'] ?? '#fff') ?>;
      --cms-soft: <?= cms_e($theme['softBg'] ?? '#f6f8fb') ?>;
      --cms-radius: <?= cms_e($theme['radius'] ?? '22px') ?>;
      --cms-font: <?= cms_e($theme['font'] ?? 'system-ui') ?>;
    }
    *{box-sizing:border-box} html{scroll-behavior:smooth} body{margin:0;background:var(--cms-bg);color:var(--cms-text);font-family:var(--cms-font);overflow-x:hidden} a{color:inherit;text-decoration:none}.cms-soft{background:var(--cms-soft)}
    .site-header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.86);backdrop-filter:blur(18px);border-bottom:1px solid rgba(17,24,39,.08)}
    .brand-mark{display:flex;gap:.7rem;align-items:center;font-weight:800;font-size:1.25rem}.brand-mark img{max-height:42px;width:auto}.brand-dot{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--cms-brand),var(--cms-brand2));display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:900}
    .main-nav{display:flex;align-items:center;gap:1.1rem}.main-nav ul{display:flex;align-items:center;gap:.4rem;list-style:none;margin:0;padding:0}.main-nav li{position:relative}.nav-linkx{display:inline-flex;align-items:center;padding:.75rem .85rem;border-radius:999px;color:#334155;font-weight:600}.nav-linkx:hover{background:rgba(13,110,253,.08);color:var(--cms-brand)}.submenu{display:none!important;position:absolute;left:0;top:100%;background:#fff;box-shadow:0 20px 60px rgba(15,23,42,.12);border:1px solid rgba(15,23,42,.08);min-width:210px;border-radius:18px;padding:.5rem!important}.has-sub:hover>.submenu{display:block!important}.submenu li,.submenu a{width:100%}.nav-actions{display:flex;align-items:center;gap:.5rem}.icon-btn{border:0;background:var(--cms-soft);border-radius:999px;width:42px;height:42px;display:inline-flex;align-items:center;justify-content:center}.hamburger{display:none}.mobile-panel{display:none}
    @media(max-width:1199px){.main-nav{display:none}.hamburger{display:inline-flex}.mobile-panel{display:block;position:fixed;top:0;right:0;width:min(390px,92vw);height:100vh;background:#fff;z-index:80;box-shadow:-20px 0 70px rgba(15,23,42,.16);transform:translateX(110%);transition:.25s ease;padding:1.25rem;overflow:auto}.mobile-panel.open{transform:translateX(0)}.mobile-panel ul{list-style:none;padding:0;margin:1rem 0}.mobile-panel a{display:block;padding:.9rem;border-bottom:1px solid rgba(15,23,42,.07)}}
    .btn-cms{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;border:0;border-radius:999px;padding:.85rem 1.2rem;background:linear-gradient(135deg,var(--cms-brand),var(--cms-brand2));color:#fff;font-weight:750;box-shadow:0 16px 35px rgba(13,110,253,.2)}.btn-cms:hover{color:#fff;transform:translateY(-1px)}.btn-ghost{border:1px solid rgba(15,23,42,.12);background:#fff;color:var(--cms-text);border-radius:999px;padding:.75rem 1.1rem;font-weight:700}.section{padding:90px 0}.section-title{max-width:760px;margin:0 auto 2.5rem;text-align:center}.section-title .eyebrow{color:var(--cms-brand);font-weight:800;letter-spacing:.08em;text-transform:uppercase;font-size:.82rem}.section-title h2{font-size:clamp(2rem,4vw,3.2rem);font-weight:900;line-height:1.05;margin:.4rem 0}.section-title p{color:var(--cms-muted);font-size:1.08rem}
    .hero{min-height:88vh;display:flex;align-items:center;position:relative;overflow:hidden;background:radial-gradient(circle at 10% 10%,rgba(13,110,253,.18),transparent 35%),radial-gradient(circle at 90% 20%,rgba(102,16,242,.16),transparent 33%),var(--cms-soft)}.hero-card{border:1px solid rgba(15,23,42,.08);background:rgba(255,255,255,.8);box-shadow:0 30px 90px rgba(15,23,42,.12);border-radius:calc(var(--cms-radius) + 12px);overflow:hidden}.hero h1,.hero .h1{font-size:clamp(2.7rem,7vw,5.8rem);font-weight:950;letter-spacing:-.055em;line-height:.94}.hero p{font-size:1.2rem;color:var(--cms-muted);max-width:640px}.hero-img{min-height:430px;background-size:cover;background-position:center;background-image:linear-gradient(135deg,var(--cms-brand),var(--cms-brand2));border-radius:var(--cms-radius)}.hero-dots{display:flex;gap:.45rem;margin-top:1.25rem}.hero-dots button{width:12px;height:12px;border:0;border-radius:999px;background:#cbd5e1}.hero-dots button.active{background:var(--cms-brand);width:32px}
    .cms-card{height:100%;border:1px solid rgba(15,23,42,.08);border-radius:var(--cms-radius);background:#fff;padding:1.4rem;box-shadow:0 18px 45px rgba(15,23,42,.06);transition:.2s ease}.cms-card:hover{transform:translateY(-4px);box-shadow:0 24px 70px rgba(15,23,42,.1)}.cms-icon{width:52px;height:52px;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;background:rgba(13,110,253,.1);color:var(--cms-brand);font-size:1.5rem;margin-bottom:1rem}.gallery-img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:var(--cms-radius);cursor:zoom-in}.avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;background:var(--cms-soft)}.map-frame{width:100%;min-height:380px;border:0;border-radius:var(--cms-radius)}.contact-box{border-radius:var(--cms-radius);background:#fff;border:1px solid rgba(15,23,42,.08);box-shadow:0 18px 45px rgba(15,23,42,.06);padding:1.5rem}.cta{border-radius:calc(var(--cms-radius) + 14px);background:linear-gradient(135deg,var(--cms-brand),var(--cms-brand2));color:#fff;padding:3rem}.cta p{color:rgba(255,255,255,.82)}.site-footer{padding:40px 0;background:#0f172a;color:#cbd5e1}.site-footer a:hover{color:#fff}.scroll-up{position:fixed;right:18px;bottom:18px;z-index:60}.admin-login{position:fixed;left:18px;bottom:18px;z-index:60}.cms-modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.62);z-index:100;display:flex;align-items:center;justify-content:center;padding:1rem}.cms-modal{background:#fff;border-radius:24px;max-width:820px;width:100%;max-height:90vh;overflow:auto;box-shadow:0 30px 100px rgba(0,0,0,.25);padding:1.5rem}.cms-modal.small{max-width:460px}.cms-editable.admin{outline:2px dashed rgba(13,110,253,.32);outline-offset:4px;border-radius:8px;min-height:1.2em}.cms-editable.admin:focus{outline-color:var(--cms-brand);background:rgba(13,110,253,.05)}.admin-panel{position:fixed;top:0;left:0;height:100vh;width:min(460px,94vw);background:#fff;z-index:110;box-shadow:20px 0 80px rgba(15,23,42,.2);transform:translateX(-110%);transition:.25s ease;overflow:auto}.admin-panel.open{transform:translateX(0)}.admin-panel header{position:sticky;top:0;background:#fff;z-index:1;padding:1rem;border-bottom:1px solid rgba(15,23,42,.08)}.admin-panel .body{padding:1rem}.admin-tab{border:0;background:var(--cms-soft);padding:.55rem .8rem;border-radius:999px;margin:.2rem;font-weight:700}.admin-tab.active{background:var(--cms-brand);color:#fff}.form-control-color{width:100%}.cookie-box{position:fixed;left:50%;bottom:16px;transform:translateX(-50%);z-index:90;background:#fff;border-radius:18px;box-shadow:0 20px 70px rgba(15,23,42,.18);border:1px solid rgba(15,23,42,.08);padding:1rem;max-width:720px;width:calc(100% - 32px)}.search-result{display:block;padding:.85rem;border-bottom:1px solid rgba(15,23,42,.08)}.share-row{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:1rem}.share-row a{border:1px solid rgba(15,23,42,.1);border-radius:999px;padding:.5rem .8rem;font-weight:700}.load-more-wrap{text-align:center;margin-top:2rem}.muted{color:var(--cms-muted)}
    <?= $theme['customCss'] ?? '' ?>
  </style>
  <?php
    $ld = [
      '@context' => 'https://schema.org',
      '@type' => 'WebSite',
      'name' => $data['settings']['siteName'] ?? 'Flat CMS',
      'url' => $base,
      'potentialAction' => ['@type' => 'SearchAction', 'target' => $base . '/?search={search_term_string}', 'query-input' => 'required name=search_term_string']
    ];
    if (($meta['type'] ?? '') === 'article' && !empty($meta['item'])) {
      $ld = ['@context'=>'https://schema.org','@type'=>'BlogPosting','headline'=>$meta['item']['title'] ?? $meta['title'],'description'=>$meta['description'] ?? '','datePublished'=>$meta['item']['date'] ?? date('c'),'image'=>$metaImage ?: null,'mainEntityOfPage'=>$canonical];
    } elseif (($meta['type'] ?? '') === 'product' && !empty($meta['item'])) {
      $ld = ['@context'=>'https://schema.org','@type'=>'Product','name'=>$meta['item']['title'] ?? $meta['title'],'description'=>$meta['description'] ?? '','image'=>$metaImage ?: null,'offers'=>['@type'=>'Offer','price'=>(string)($meta['item']['price'] ?? ''),'priceCurrency'=>'EUR','availability'=>'https://schema.org/InStock']];
    }
  ?>
  <script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
  <div id="root"></div>
  <script id="__CMS_SEED__" type="application/json"><?= json_encode(cms_public_data($data), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
  <script src="https://cdn.jsdelivr.net/npm/react@18/umd/react.production.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/react-dom@18/umd/react-dom.production.min.js"></script>
  <?php if (!empty($libs['bootstrap'])): ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script><?php endif; ?>
  <?php if (!empty($libs['aos'])): ?><script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script><?php endif; ?>
  <?php if (!empty($libs['glightbox'])): ?><script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script><?php endif; ?>
  <?php if (!empty($libs['hcaptcha'])): ?><script src="https://js.hcaptcha.com/1/api.js" async defer></script><?php endif; ?>
  <?php if (!empty($libs['sassBuilder'])): ?><script src="https://cdn.jsdelivr.net/npm/sass.js@0.11.1/dist/sass.sync.js"></script><?php endif; ?>
  <script>
  (() => {
    const h = React.createElement;
    const {useEffect, useMemo, useState} = React;
    const rootEl = document.getElementById('root');
    const seed = JSON.parse(document.getElementById('__CMS_SEED__').textContent);
    const initialBoot = { ok:true, admin:false, email:'', csrf:'', route:null, server:null, data: seed };

    function cls(...xs){ return xs.filter(Boolean).join(' '); }
    function get(obj, path){ return String(path).split('.').reduce((o,k)=> o && o[k] !== undefined ? o[k] : undefined, obj); }
    function set(obj, path, val){ const keys=String(path).split('.'); const copy=Array.isArray(obj)?[...obj]:{...obj}; let cur=copy; keys.forEach((k,i)=>{ if(i===keys.length-1){cur[k]=val;return;} cur[k]=Array.isArray(cur[k])?[...cur[k]]:{...cur[k]}; cur=cur[k]; }); return copy; }
    function absolute(url){ if(!url) return location.href; try{return new URL(url, location.href).href}catch(e){return location.href} }
    function slug(s){ return String(s||'item').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,''); }
    function pretty(group,item){ const s=slug(item?.slug||item?.title||'item'); if(group==='blog') return '/blog/'+s; if(group==='products') return '/product/'+s; if(group==='pages') return '/page/'+s; return '?slug='+encodeURIComponent(s); }
    function icon(name, fallback){ return h('i',{className:name||fallback||'bi bi-circle'}); }

    function App(){
      const [boot,setBoot] = useState(initialBoot);
      const [data,setData] = useState(seed);
      const [lang,setLang] = useState(seed.settings.defaultLang || 'en');
      const [adminOpen,setAdminOpen] = useState(false);
      const [modal,setModal] = useState(null);
      const [notice,setNotice] = useState('');
      const [dirty,setDirty] = useState(false);
      const admin = boot.admin;
      const csrf = boot.csrf;
      const theme = data.settings.theme || {};

      useEffect(()=>{ fetch('?api=site').then(r=>r.json()).then(j=>{ if(j.ok){ setBoot(j); setData(j.data); } }); }, []);
      useEffect(()=>{ document.documentElement.style.setProperty('--cms-brand', theme.brand || '#0d6efd'); document.documentElement.style.setProperty('--cms-brand2', theme.brand2 || '#6610f2'); document.documentElement.style.setProperty('--cms-accent', theme.accent || '#20c997'); document.documentElement.style.setProperty('--cms-text', theme.text || '#1f2937'); document.documentElement.style.setProperty('--cms-muted', theme.muted || '#6b7280'); document.documentElement.style.setProperty('--cms-bg', theme.bg || '#fff'); document.documentElement.style.setProperty('--cms-soft', theme.softBg || '#f6f8fb'); document.documentElement.style.setProperty('--cms-radius', theme.radius || '22px'); }, [theme]);
      useEffect(()=>{ if(window.AOS) window.AOS.init({once:true,duration:700}); }, []);
      useEffect(()=>{ const styleId='cms-custom-live'; let st=document.getElementById(styleId); if(!st){ st=document.createElement('style'); st.id=styleId; document.head.appendChild(st);} st.textContent=data.settings.theme.customCss||''; }, [data.settings.theme.customCss]);
      useEffect(()=>{ maybeLoadGA(data); }, [data.settings.analytics, data.settings.cookies]);

      function update(path,val){ setData(d=>set(d,path,val)); setDirty(true); }
      function replace(next){ setData(next); setDirty(true); }
      async function save(){ const r=await fetch('?api=save',{method:'POST',headers:{'Content-Type':'application/json','X-CMS-CSRF':csrf},body:JSON.stringify(data)}); const j=await r.json(); if(j.ok){setData(j.data);setDirty(false);setNotice('Saved to data.json');} else setNotice(j.error||'Save failed'); }
      async function logout(){ await fetch('?api=logout',{method:'POST',headers:{'X-CMS-CSRF':csrf}}); location.reload(); }
      function Editable({path,tag='span',className='',html=false,placeholder=''}){ const Tag=tag; const val=get(data,path) ?? ''; const props={className:cls('cms-editable',admin&&'admin',className), suppressContentEditableWarning:true}; if(admin){ props.contentEditable=true; props.onBlur=e=>update(path, html?e.currentTarget.innerHTML:e.currentTarget.textContent); props.title='Click to edit'; }
        if(html) return h(Tag,{...props,dangerouslySetInnerHTML:{__html: val || placeholder}}); return h(Tag,props,val || placeholder); }
      const api = {data,setData,update,replace,Editable,admin,setModal,lang,setLang,save,logout,dirty,notice,setNotice,adminOpen,setAdminOpen,csrf,boot,setBoot};
      return h(React.Fragment,null,
        h(Header,{api}),
        boot.route?.item && h(RoutePage,{api,route:boot.route}),
        data.hero?.enabled && h(Hero,{api}),
        data.about?.enabled && h(About,{api}),
        data.services?.enabled && h(Services,{api}),
        data.gallery?.enabled && h(Gallery,{api}),
        data.testimonials?.enabled && h(Testimonials,{api}),
        data.products?.enabled && h(Products,{api}),
        data.blog?.enabled && h(Blog,{api}),
        data.contact?.enabled && h(Contact,{api}),
        data.cta?.enabled && h(CTA,{api}),
        h(Footer,{api}),
        h('button',{className:'btn-cms scroll-up',onClick:()=>scrollTo({top:0,behavior:'smooth'}),'↑'),
        admin && h('button',{className:'btn-cms admin-login',onClick:()=>setAdminOpen(true)}, icon('bi bi-sliders'), ' Admin'),
        !admin && h(LoginButton),
        h(AdminPanel,{api,open:adminOpen,onClose:()=>setAdminOpen(false)}),
        h(CookieBanner,{data}),
        modal && h(Modal,{modal,setModal,api}),
        notice && h('div',{className:'cms-modal-backdrop',onClick:()=>setNotice('')}, h('div',{className:'cms-modal small'}, h('p',{className:'mb-3'},notice), h('button',{className:'btn-cms',onClick:()=>setNotice('')},'OK')))
      );
    }

    function maybeLoadGA(data){
      const id=data.settings?.analytics?.ga4Id; if(!id || window.__cmsGaLoaded) return;
      const consentNeeded=data.settings?.analytics?.loadAfterCookieConsent && data.settings?.cookies?.enabled;
      if(consentNeeded && localStorage.getItem('cms_cookie_ok')!=='yes') return;
      window.__cmsGaLoaded=true;
      const s=document.createElement('script'); s.async=true; s.src='https://www.googletagmanager.com/gtag/js?id='+encodeURIComponent(id); document.head.appendChild(s);
      window.dataLayer=window.dataLayer||[]; function gtag(){dataLayer.push(arguments)} window.gtag=gtag; gtag('js',new Date()); gtag('config',id);
    }

    function translate(api,obj,key){ return obj?.i18n?.[api.lang]?.[key] || obj?.[key] || ''; }
    function RoutePage({api,route}){ const item=route.item||{}; const group=route.group||'pages'; const path=`${group}.items.${route.index}`; const E=api.Editable; return h('section',{className:'section cms-soft'}, h(Container,{api}, h('article',{className:'cms-card mx-auto',style:{maxWidth:920}}, h('div',{className:'eyebrow text-primary fw-bold mb-2'}, group==='products'?'Product':group==='blog'?'Blog':'Page'), item.image&&h('img',{src:item.image,alt:item.title,className:'img-fluid rounded-4 mb-4'}), h(E,{path:path+'.title',tag:'h1',className:'display-5 fw-bold'}), item.date&&h('p',{className:'muted'},item.date), item.price&&h(E,{path:path+'.price',tag:'strong',className:'text-primary d-block mb-3'}), h(E,{path:path+'.body',tag:'div',html:true,className:'fs-5'}), h('div',{className:'share-row'}, shareLinks(location.href,item.title).map(s=>h('a',{key:s.label,href:s.url,target:'_blank',rel:'noopener'},s.label)))))); }
    function Container({api,children,className=''}){ const grid=api.data.settings.theme.grid || 'container'; return h('div',{className:cls(grid,className)},children); }
    function SectionTitle({api,base}){ const E=api.Editable; return h('div',{className:'section-title'}, h('div',{className:'eyebrow'}, base), h(E,{path:base+'.title',tag:'h2'}), h(E,{path:base+'.subtitle',tag:'p'})); }

    function Header({api}){ const {data,setModal,lang,setLang,admin,update}=api; const [open,setOpen]=useState(false); const E=api.Editable; const menu=data.header.menu||[]; function renderMenu(items,mobile=false){ return h('ul',null,items.map((it,i)=>h('li',{key:i,className:it.children?.length?'has-sub':''}, h('a',{className:mobile?'':'nav-linkx',href:it.href||'#',onClick:()=>setOpen(false)}, translate(api,it,'label'), it.children?.length? h('span',{className:'ms-1'},'▾'):null), it.children?.length?h('ul',{className:'submenu'},it.children.map((c,j)=>h('li',{key:j},h('a',{className:'nav-linkx',href:c.href||'#'},translate(api,c,'label'))))):null))); }
      return h('header',{className:'site-header'}, h(Container,{api,className:'py-3 d-flex align-items-center justify-content-between'},
        h('a',{href:'#home',className:'brand-mark'}, data.header.logoImage ? h('img',{src:data.header.logoImage,alt:data.settings.siteName}) : h('span',{className:'brand-dot'}, (data.header.logoText||'F').slice(0,1)), h(E,{path:'header.logoText'})),
        h('nav',{className:'main-nav'}, renderMenu(menu)),
        h('div',{className:'nav-actions'}, h('button',{className:'icon-btn',onClick:()=>setModal({type:'search'})},icon('bi bi-search')), h('select',{className:'form-select form-select-sm',value:lang,onChange:e=>setLang(e.target.value),style:{width:76}}, (data.settings.languages||[]).map(l=>h('option',{key:l.code,value:l.code},l.label))), h('button',{className:'icon-btn hamburger',onClick:()=>setOpen(true), 'aria-label':'Open menu'},icon('bi bi-list')))
      ), h('aside',{className:cls('mobile-panel',open&&'open')}, h('div',{className:'d-flex justify-content-between align-items-center'}, h('strong',null,data.settings.siteName), h('button',{className:'icon-btn',onClick:()=>setOpen(false)},icon('bi bi-x-lg'))), renderMenu(menu,true), admin && h('button',{className:'btn-ghost w-100 mt-3',onClick:()=>{api.setAdminOpen(true);setOpen(false)}},'Admin settings')));
    }

    function Hero({api}){ const {data,update}=api; const slides=data.hero.slides||[]; const [idx,setIdx]=useState(0); const E=api.Editable; const slide=slides[idx]||slides[0]||{}; useEffect(()=>{ if(slides.length<2) return; const id=setInterval(()=>setIdx(i=>(i+1)%slides.length),5500); return()=>clearInterval(id); }, [slides.length]); const TitleTag=idx===0?'h1':'div'; const bg=slide.bg ? {backgroundImage:`linear-gradient(90deg,rgba(255,255,255,.9),rgba(255,255,255,.62)),url(${slide.bg})`} : {};
      return h('section',{id:'home',className:'hero section',style:bg}, h(Container,{api}, h('div',{className:'row align-items-center g-4'}, h('div',{className:'col-lg-7'}, h('div',{className:'p-3 p-lg-5'}, h('div',{className:'eyebrow text-primary fw-bold mb-3'}, h(E,{path:`hero.slides.${idx}.eyebrow`})), h(E,{path:`hero.slides.${idx}.title`,tag:TitleTag,className:idx===0?'':'h1'}), h(E,{path:`hero.slides.${idx}.text`,tag:'p',className:'my-4'}), h('a',{className:'btn-cms',href:slide.buttonHref||'#about'}, h(E,{path:`hero.slides.${idx}.buttonText`})), slides.length>1 && h('div',{className:'hero-dots'}, slides.map((_,i)=>h('button',{key:i,className:i===idx?'active':'',onClick:()=>setIdx(i), 'aria-label':'Slide '+(i+1)}))))), h('div',{className:'col-lg-5'}, h('div',{className:'hero-card p-2'}, h('div',{className:'hero-img',style:{backgroundImage: slide.image?`url(${slide.image})`:'linear-gradient(135deg,var(--cms-brand),var(--cms-brand2))'}}))))));
    }

    function About({api}){ const d=api.data.about, E=api.Editable; return h('section',{id:'about',className:'section'}, h(Container,{api}, h('div',{className:'row align-items-center g-5'}, h('div',{className:'col-lg-6'}, d.image?h('img',{className:'img-fluid rounded-4 shadow-sm',src:d.image,alt:d.title}):h('div',{className:'hero-img'})), h('div',{className:'col-lg-6'}, h('div',{className:'eyebrow text-primary fw-bold mb-2'},'About'), h(E,{path:'about.title',tag:'h2',className:'display-5 fw-bold'}), h(E,{path:'about.subtitle',tag:'p',className:'lead muted'}), h(E,{path:'about.body',tag:'div',html:true,className:'fs-5'}))))); }

    function LoadMoreGrid({api,base,render}){ const section=api.data[base]||{}; const [count,setCount]=useState(section.batch||3); const items=section.items||[]; useEffect(()=>setCount(section.batch||3),[base,section.batch]); return h(React.Fragment,null, h('div',{className:'row g-4'}, items.slice(0,count).map((item,i)=>render(item,i))), section.showButton && count<items.length && h('div',{className:'load-more-wrap'}, h('button',{className:'btn-cms',onClick:()=>setCount(c=>c+(section.batch||3))}, section.buttonText||'Load more'))); }

    function Services({api}){ const E=api.Editable; return h('section',{id:'services',className:'section cms-soft'}, h(Container,{api}, h(SectionTitle,{api,base:'services'}), h(LoadMoreGrid,{api,base:'services',render:(it,i)=>h('div',{className:'col-md-6 col-lg-4',key:i,'data-aos':'fade-up'}, h('article',{className:'cms-card'}, h('div',{className:'cms-icon'}, icon(it.icon,'bi bi-stars')), h(E,{path:`services.items.${i}.title`,tag:'h3',className:'h4 fw-bold'}), h(E,{path:`services.items.${i}.text`,tag:'p',className:'muted'}), it.buttonText && h('a',{className:'btn-ghost mt-2',href:it.buttonHref||'#contact'}, h(E,{path:`services.items.${i}.buttonText`})) ))}))); }

    function Gallery({api}){ const [light,setLight]=useState(null); const E=api.Editable; return h('section',{id:'gallery',className:'section'}, h(Container,{api}, h(SectionTitle,{api,base:'gallery'}), h(LoadMoreGrid,{api,base:'gallery',render:(it,i)=>h('div',{className:'col-sm-6 col-lg-4',key:i}, h('img',{className:'gallery-img',src:it.src,alt:it.alt||'',onClick:()=>setLight(it)}), api.admin&&h(E,{path:`gallery.items.${i}.alt`,tag:'small',className:'d-block mt-2 muted'}))})), light&&h('div',{className:'cms-modal-backdrop',onClick:()=>setLight(null)}, h('div',{className:'cms-modal',onClick:e=>e.stopPropagation()}, h('button',{className:'icon-btn float-end',onClick:()=>setLight(null)},icon('bi bi-x-lg')), h('img',{src:light.src,alt:light.alt||'',className:'img-fluid rounded-4'}), h('p',{className:'mt-2 muted'},light.alt||'')))); }

    function Testimonials({api}){ const E=api.Editable; return h('section',{id:'testimonials',className:'section cms-soft'}, h(Container,{api}, h(SectionTitle,{api,base:'testimonials'}), h(LoadMoreGrid,{api,base:'testimonials',render:(it,i)=>h('div',{className:'col-md-6 col-lg-4',key:i}, h('article',{className:'cms-card'}, h('div',{className:'d-flex gap-3 align-items-center mb-3'}, it.photo?h('img',{className:'avatar',src:it.photo,alt:it.name}):h('div',{className:'avatar'}), h('div',null,h(E,{path:`testimonials.items.${i}.name`,tag:'strong',className:'d-block'}), h(E,{path:`testimonials.items.${i}.role`,tag:'small',className:'muted'}))), h(E,{path:`testimonials.items.${i}.text`,tag:'p',className:'mb-0'})))}))); }

    function Products({api}){ const E=api.Editable; return h('section',{id:'products',className:'section'}, h(Container,{api}, h(SectionTitle,{api,base:'products'}), h(LoadMoreGrid,{api,base:'products',render:(it,i)=>h('div',{className:'col-md-6 col-lg-4',key:i}, h('article',{className:'cms-card'}, it.image?h('img',{src:it.image,alt:it.title,className:'img-fluid rounded-4 mb-3'}):h('div',{className:'hero-img mb-3',style:{minHeight:180}}), h(E,{path:`products.items.${i}.title`,tag:'h3',className:'h4 fw-bold'}), h(E,{path:`products.items.${i}.price`,tag:'strong',className:'text-primary d-block mb-2'}), h(E,{path:`products.items.${i}.text`,tag:'p',className:'muted'}), h('a',{className:'btn-ghost',href:pretty('products',it),onClick:e=>{ if(!e.metaKey&&!e.ctrlKey){ e.preventDefault(); api.setModal({type:'item',group:'products',index:i}); }}},'Details')))}))); }

    function Blog({api}){ const E=api.Editable; return h('section',{id:'blog',className:'section cms-soft'}, h(Container,{api}, h(SectionTitle,{api,base:'blog'}), h(LoadMoreGrid,{api,base:'blog',render:(it,i)=>h('div',{className:'col-md-6 col-lg-4',key:i}, h('article',{className:'cms-card'}, it.image?h('img',{src:it.image,alt:it.title,className:'img-fluid rounded-4 mb-3'}):null, h('small',{className:'muted'},it.date), h(E,{path:`blog.items.${i}.title`,tag:'h3',className:'h4 fw-bold mt-2'}), h(E,{path:`blog.items.${i}.excerpt`,tag:'p',className:'muted'}), h('a',{className:'btn-ghost',href:pretty('blog',it),onClick:e=>{ if(!e.metaKey&&!e.ctrlKey){ e.preventDefault(); api.setModal({type:'item',group:'blog',index:i}); }}},'Read post')))}))); }

    function Contact({api}){ const d=api.data.contact, E=api.Editable; return h('section',{id:'contact',className:'section'}, h(Container,{api}, h(SectionTitle,{api,base:'contact'}), h('div',{className:'row g-4'}, h('div',{className:'col-lg-7'}, h('iframe',{className:'map-frame',loading:'lazy',src:d.mapEmbed||''})), h('div',{className:'col-lg-5'}, h('div',{className:'contact-box'}, ['address','phone','email','hours'].map(k=>h('p',{key:k,className:'mb-2'}, h('strong',null,k.charAt(0).toUpperCase()+k.slice(1)+': '), h(E,{path:`contact.details.${k}`}))), h('button',{className:'btn-cms mt-3',onClick:()=>api.setModal({type:'form'})}, d.formButtonText||'Send request'), h('div',{className:'share-row'}, shareLinks(location.href, api.data.settings.siteName).map(s=>h('a',{key:s.label,href:s.url,target:'_blank',rel:'noopener'},s.label)))))))); }

    function CTA({api}){ const E=api.Editable; return h('section',{className:'section'}, h(Container,{api}, h('div',{className:'cta text-center'}, h(E,{path:'cta.title',tag:'h2',className:'display-6 fw-bold'}), h(E,{path:'cta.text',tag:'p',className:'lead'}), h('button',{className:'btn-ghost',onClick:()=>api.setModal({type:'form'})}, h(E,{path:'cta.buttonText'}))))); }

    function Footer({api}){ const d=api.data.footer||{}; const year=new Date().getFullYear(); return h('footer',{className:'site-footer'}, h(Container,{api}, h('div',{className:'row g-4 align-items-center'}, h('div',{className:'col-lg-4'}, (d.copy||'').replace('{year}',year)), h('div',{className:'col-lg-4 text-lg-center'}, (d.menu||[]).map((m,i)=>h('a',{key:i,href:m.href,className:'me-3'},m.label))), h('div',{className:'col-lg-4 text-lg-end'}, (d.social||[]).map((s,i)=>h('a',{key:i,href:s.url,target:'_blank',rel:'noopener',className:'ms-3'}, icon(s.icon), ' ', s.label)))))); }

    function LoginButton(){ const [open,setOpen]=useState(false); const [email,setEmail]=useState(''); const [msg,setMsg]=useState(''); async function submit(e){ e.preventDefault(); setMsg('Sending...'); const r=await fetch('?api=login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email})}); const j=await r.json(); setMsg(j.error||j.message||'Check your email.'); }
      return h(React.Fragment,null, h('button',{className:'btn-cms admin-login',onClick:()=>setOpen(true),'aria-label':'Admin login'}, icon('bi bi-person-lock')), open&&h('div',{className:'cms-modal-backdrop',onClick:()=>setOpen(false)}, h('form',{className:'cms-modal small',onClick:e=>e.stopPropagation(),onSubmit:submit}, h('div',{className:'d-flex justify-content-between align-items-center mb-3'}, h('h3',null,'Admin login'), h('button',{type:'button',className:'icon-btn',onClick:()=>setOpen(false)},icon('bi bi-x-lg'))), h('p',{className:'muted'},'Enter an administrator email. A secure login URL will be sent to that address.'), h('input',{className:'form-control mb-3',type:'email',value:email,onChange:e=>setEmail(e.target.value),placeholder:'admin@example.com',required:true}), h('button',{className:'btn-cms w-100'},'Send login link'), msg&&h('p',{className:'mt-3 mb-0'},msg)))); }

    function Modal({modal,setModal,api}){ if(modal.type==='search') return h(SearchModal,{api,onClose:()=>setModal(null)}); if(modal.type==='form') return h(FormModal,{api,onClose:()=>setModal(null)}); if(modal.type==='item') return h(ItemModal,{api,modal,onClose:()=>setModal(null)}); return null; }
    function SearchModal({api,onClose}){ const [q,setQ]=useState(''); const results=useMemo(()=>{ if(!q.trim()) return []; const low=q.toLowerCase(); const out=[]; ['services','products','blog','gallery','pages'].forEach(group=>(api.data[group]?.items||[]).forEach((it,i)=>{ if(JSON.stringify(it).toLowerCase().includes(low)) out.push({group,i,title:it.title||it.alt||group,text:it.excerpt||it.text||it.alt||'',href:['blog','products','pages'].includes(group)?pretty(group,it):'#'+group}); })); return out.slice(0,20); },[q,api.data]); return h('div',{className:'cms-modal-backdrop',onClick:onClose}, h('div',{className:'cms-modal',onClick:e=>e.stopPropagation()}, h('div',{className:'d-flex justify-content-between align-items-center mb-3'}, h('h3',null,'Ajax search'), h('button',{className:'icon-btn',onClick:onClose},icon('bi bi-x-lg'))), h('input',{autoFocus:true,className:'form-control form-control-lg',placeholder:'Search this page...',value:q,onChange:e=>setQ(e.target.value)}), h('div',{className:'mt-3'}, results.map((r,i)=>h('a',{key:i,className:'search-result',href:r.href,onClick:onClose}, h('strong',null,r.title), h('span',{className:'badge text-bg-light ms-2'},r.group), h('p',{className:'muted mb-0'},r.text)))))); }

    function ItemModal({api,modal,onClose}){ const item=api.data[modal.group]?.items?.[modal.index]||{}; const path=`${modal.group}.items.${modal.index}`; const E=api.Editable; const url=absolute(pretty(modal.group,item)); return h('div',{className:'cms-modal-backdrop',onClick:onClose}, h('article',{className:'cms-modal',onClick:e=>e.stopPropagation()}, h('button',{className:'icon-btn float-end',onClick:onClose},icon('bi bi-x-lg')), item.image&&h('img',{src:item.image,alt:item.title,className:'img-fluid rounded-4 mb-3'}), h(E,{path:path+'.title',tag:'h2',className:'fw-bold'}), item.date&&h('p',{className:'muted'},item.date), item.price&&h(E,{path:path+'.price',tag:'strong',className:'text-primary d-block mb-3'}), h(E,{path:path+'.body',tag:'div',html:true,className:'fs-5'}), h('div',{className:'share-row'}, shareLinks(url,item.title).map(s=>h('a',{key:s.label,href:s.url,target:'_blank',rel:'noopener'},s.label))))); }

    function shareLinks(url,title){ const u=encodeURIComponent(url), t=encodeURIComponent(title||document.title); return [ {label:'Facebook',url:`https://www.facebook.com/sharer/sharer.php?u=${u}`}, {label:'X',url:`https://twitter.com/intent/tweet?url=${u}&text=${t}`}, {label:'LinkedIn',url:`https://www.linkedin.com/sharing/share-offsite/?url=${u}`}, {label:'WhatsApp',url:`https://wa.me/?text=${t}%20${u}`} ]; }

    function FormModal({api,onClose}){ const [form,setForm]=useState({name:'',email:'',phone:'',message:''}); const [msg,setMsg]=useState(''); const hc=api.data.settings.hcaptcha||{}; async function submit(e){ e.preventDefault(); setMsg('Sending...'); const token=document.querySelector('[name="h-captcha-response"]')?.value||''; const r=await fetch('?api=form',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...form,hcaptcha:token,type:'contact'})}); const j=await r.json(); setMsg(j.error||j.message||'Sent'); if(j.ok) setForm({name:'',email:'',phone:'',message:''}); }
      return h('div',{className:'cms-modal-backdrop',onClick:onClose}, h('form',{className:'cms-modal small',onClick:e=>e.stopPropagation(),onSubmit:submit}, h('div',{className:'d-flex justify-content-between align-items-center mb-3'}, h('h3',null,'Send request'), h('button',{type:'button',className:'icon-btn',onClick:onClose},icon('bi bi-x-lg'))), ['name','email','phone'].map(k=>h('input',{key:k,className:'form-control mb-3',placeholder:k.charAt(0).toUpperCase()+k.slice(1),type:k==='email'?'email':'text',value:form[k],onChange:e=>setForm({...form,[k]:e.target.value}),required:k!=='phone'})), h('textarea',{className:'form-control mb-3',rows:5,placeholder:'Message',value:form.message,onChange:e=>setForm({...form,message:e.target.value}),required:true}), api.data.settings.libraries.hcaptcha && hc.siteKey && h('div',{className:'h-captcha mb-3','data-sitekey':hc.siteKey}), h('button',{className:'btn-cms w-100'},'Send'), msg&&h('p',{className:'mt-3 mb-0'},msg)) ); }

    function CookieBanner({data}){ const cfg=data.settings.cookies||{}; const [show,setShow]=useState(()=>cfg.enabled && !localStorage.getItem('cms_cookie_ok')); if(!show) return null; return h('div',{className:'cookie-box'}, h('div',{className:'d-flex flex-column flex-lg-row align-items-lg-center gap-3 justify-content-between'}, h('p',{className:'mb-0'},cfg.message), h('div',{className:'d-flex gap-2'}, h('button',{className:'btn-ghost',onClick:()=>{localStorage.setItem('cms_cookie_ok','no');setShow(false)}},cfg.declineText||'Decline'), h('button',{className:'btn-cms',onClick:()=>{localStorage.setItem('cms_cookie_ok','yes');setShow(false);maybeLoadGA(data)}},cfg.acceptText||'Accept')))); }

    function AdminPanel({api,open,onClose}){ const [tab,setTab]=useState('site'); const data=api.data; if(!api.admin) return null; return h('aside',{className:cls('admin-panel',open&&'open')}, h('header',null, h('div',{className:'d-flex justify-content-between align-items-center'}, h('div',null,h('strong',null,'CMS Admin'), h('div',{className:'small muted'},api.email || '')), h('button',{className:'icon-btn',onClick:onClose},icon('bi bi-x-lg'))), h('div',{className:'mt-3'}, ['site','theme','menu','content','media','seo','forms','libraries','redirects','server','json'].map(t=>h('button',{key:t,className:cls('admin-tab',tab===t&&'active'),onClick:()=>setTab(t)},t)))), h('div',{className:'body'}, api.dirty&&h('div',{className:'alert alert-warning py-2'},'Unsaved changes'), h('div',{className:'d-flex gap-2 mb-3'}, h('button',{className:'btn-cms flex-fill',onClick:api.save},'Save JSON'), h('button',{className:'btn-ghost',onClick:api.logout},'Logout')), tab==='site'&&h(SiteTab,{api}), tab==='theme'&&h(ThemeTab,{api}), tab==='menu'&&h(MenuTab,{api}), tab==='content'&&h(ContentTab,{api}), tab==='media'&&h(MediaTab,{api}), tab==='seo'&&h(SeoTab,{api}), tab==='forms'&&h(FormsTab,{api}), tab==='libraries'&&h(LibrariesTab,{api}), tab==='redirects'&&h(RedirectsTab,{api}), tab==='server'&&h(ServerTab,{api}), tab==='json'&&h(JsonTab,{api}) )); }
    function Field({label,children}){ return h('label',{className:'form-label w-100 mb-3'}, h('span',{className:'small fw-bold d-block mb-1'},label), children); }
    function Txt({api,path,label,type='text'}){ return h(Field,{label}, h('input',{className:'form-control',type,value:get(api.data,path)||'',onChange:e=>api.update(path,e.target.value)})); }
    function Bool({api,path,label}){ return h('div',{className:'form-check form-switch mb-2'}, h('input',{className:'form-check-input',type:'checkbox',checked:!!get(api.data,path),onChange:e=>api.update(path,e.target.checked),id:path}), h('label',{className:'form-check-label',htmlFor:path},label)); }
    function SiteTab({api}){ return h(React.Fragment,null, h(Txt,{api,path:'settings.siteName',label:'Site name'}), h(Txt,{api,path:'settings.baseUrl',label:'Base URL'}), h(Txt,{api,path:'header.logoText',label:'Logo text'}), h(Txt,{api,path:'header.logoImage',label:'Logo image URL'}), h('p',{className:'small muted'},'Tip: upload a logo in Media, copy the URL, and paste it here.')) }
    function ThemeTab({api}){ const colors=['brand','brand2','accent','text','muted','bg','softBg']; return h(React.Fragment,null, colors.map(k=>h(Field,{key:k,label:k}, h('input',{className:'form-control form-control-color',type:'color',value:get(api.data,'settings.theme.'+k)||'#000000',onChange:e=>api.update('settings.theme.'+k,e.target.value)}))), h(Txt,{api,path:'settings.theme.radius',label:'Radius'}), h(Txt,{api,path:'settings.theme.grid',label:'Bootstrap grid class: container / container-fluid'}), h(Field,{label:'Custom CSS'}, h('textarea',{className:'form-control',rows:6,value:get(api.data,'settings.theme.customCss')||'',onChange:e=>api.update('settings.theme.customCss',e.target.value)})), h(Field,{label:'Custom SCSS source (stored only unless Sass library is enabled)'}, h('textarea',{className:'form-control',rows:6,value:get(api.data,'settings.theme.customScss')||'',onChange:e=>api.update('settings.theme.customScss',e.target.value)})), h('button',{className:'btn-ghost',onClick:()=>compileScss(api)},'Compile SCSS to CSS')) }
    function compileScss(api){ if(!window.Sass){ api.setNotice('Enable Sass Builder in Libraries, save, and reload.'); return; } Sass.compile(api.data.settings.theme.customScss||'', res=>{ if(res.status===0) api.update('settings.theme.customCss',res.text); else api.setNotice(res.formatted||'SCSS error'); }); }
    function MenuTab({api}){ const menu=api.data.header.menu||[]; function upd(i,k,v){ const next=[...menu]; next[i]={...next[i],[k]:v}; api.update('header.menu',next); } function add(){ api.update('header.menu',[...menu,{label:'New item',href:'#section',children:[]}]); } function remove(i){ api.update('header.menu',menu.filter((_,x)=>x!==i)); } return h(React.Fragment,null, menu.map((m,i)=>h('div',{key:i,className:'border rounded-4 p-3 mb-3'}, h('input',{className:'form-control mb-2',value:m.label||'',onChange:e=>upd(i,'label',e.target.value),placeholder:'Label'}), h('input',{className:'form-control mb-2',value:m.href||'',onChange:e=>upd(i,'href',e.target.value),placeholder:'#section or URL'}), h(Field,{label:'Submenu JSON'}, h('textarea',{className:'form-control',rows:3,value:JSON.stringify(m.children||[],null,2),onChange:e=>{try{upd(i,'children',JSON.parse(e.target.value))}catch(err){}}})), h('button',{className:'btn-ghost',onClick:()=>remove(i)},'Remove'))), h('button',{className:'btn-cms',onClick:add},'Add menu item')); }
    function ContentTab({api}){ const secs=['hero','about','services','gallery','testimonials','products','blog','contact','cta']; return h(React.Fragment,null, secs.map(s=>h(Bool,{key:s,api,path:s+'.enabled',label:'Show '+s})), h('p',{className:'small muted mt-3'},'Detailed text is edited inline on the page. Use JSON tab for deep structure changes.')) }
    function MediaTab({api}){ const [target,setTarget]=useState('uploads'); const [out,setOut]=useState(''); async function upload(e){ const file=e.target.files[0]; if(!file) return; const fd=new FormData(); fd.append('file',file); fd.append('target',target); const r=await fetch('?api=upload',{method:'POST',headers:{'X-CMS-CSRF':api.csrf},body:fd}); const j=await r.json(); if(j.ok){setOut(j.url); if(target==='gallery'){ const items=api.data.gallery.items||[]; api.update('gallery.items',[...items,{src:j.url,alt:file.name}]); }} else setOut(j.error); }
      return h(React.Fragment,null, h(Field,{label:'Upload target'}, h('select',{className:'form-select',value:target,onChange:e=>setTarget(e.target.value)}, h('option',{value:'uploads'},'assets/uploads'), h('option',{value:'img'},'assets/img'), h('option',{value:'gallery'},'assets/gallery'), h('option',{value:'docs'},'assets/docs'))), h('input',{className:'form-control',type:'file',onChange:upload}), out&&h('div',{className:'alert alert-info mt-3'},'URL: ',h('code',null,out))); }
    function SeoTab({api}){ return h(React.Fragment,null, h(Txt,{api,path:'settings.seo.title',label:'Default meta title'}), h(Field,{label:'Default meta description'}, h('textarea',{className:'form-control',rows:3,value:get(api.data,'settings.seo.description')||'',onChange:e=>api.update('settings.seo.description',e.target.value)})), h(Txt,{api,path:'settings.seo.keywords',label:'Keywords'}), h(Txt,{api,path:'settings.seo.image',label:'OG image'}), h(Txt,{api,path:'settings.seo.canonical',label:'Canonical override'}), h(Txt,{api,path:'settings.seo.robots',label:'Robots'}), h('p',{className:'small muted'},'Blog and product SEO fields live inside each item in JSON. Pretty routes: /blog/post-slug, /product/product-slug, and /page/page-slug. Fallback query route: ?slug=slug.')) }
    function FormsTab({api}){ return h(React.Fragment,null, h(Txt,{api,path:'settings.forms.emailTo',label:'Send form submissions to email'}), h(Bool,{api,path:'settings.forms.storeSubmissions',label:'Store submissions in data.json'}), h(Txt,{api,path:'settings.hcaptcha.siteKey',label:'hCaptcha site key'}), h(Txt,{api,path:'settings.hcaptcha.secret',label:'hCaptcha secret'}), h('p',{className:'small muted'},'The public API never exposes the hCaptcha secret.')) }
    function LibrariesTab({api}){ return h(React.Fragment,null, Object.keys(api.data.settings.libraries||{}).map(k=>h(Bool,{key:k,api,path:'settings.libraries.'+k,label:k})), h(Txt,{api,path:'settings.analytics.ga4Id',label:'Google Analytics GA4 ID'}), h(Bool,{api,path:'settings.analytics.loadAfterCookieConsent',label:'Load GA only after cookie consent'}), h(Bool,{api,path:'settings.cookies.enabled',label:'Enable cookie banner'})); }
    function RedirectsTab({api}){ const arr=api.data.redirects||[]; function upd(i,k,v){ const next=[...arr]; next[i]={...next[i],[k]:v}; api.update('redirects',next); } return h(React.Fragment,null, arr.map((r,i)=>h('div',{key:i,className:'border rounded-4 p-3 mb-3'}, h(Bool,{api,path:`redirects.${i}.enabled`,label:'Enabled'}), h('input',{className:'form-control mb-2',value:r.from||'',onChange:e=>upd(i,'from',e.target.value),placeholder:'/old'}), h('input',{className:'form-control mb-2',value:r.to||'',onChange:e=>upd(i,'to',e.target.value),placeholder:'/new'}), h('input',{className:'form-control mb-2',value:r.status||301,onChange:e=>upd(i,'status',parseInt(e.target.value||301,10)),placeholder:'301'}), h('button',{className:'btn-ghost',onClick:()=>api.update('redirects',arr.filter((_,x)=>x!==i))},'Remove'))), h('button',{className:'btn-cms',onClick:()=>api.update('redirects',[...arr,{from:'/old-page',to:'/',status:301,enabled:true}])},'Add redirect')); }
    function ServerTab({api}){ const status=api.boot.server||{}; async function sync(){ const r=await fetch('?api=sync-htaccess',{method:'POST',headers:{'X-CMS-CSRF':api.csrf}}); const j=await r.json(); if(j.server) api.setBoot({...api.boot,server:j.server}); api.setNotice(j.message||'Server config checked.'); } return h(React.Fragment,null, h('div',{className:'alert alert-info'},'index.php auto-generates root .htaccess and assets/.htaccess. Custom rules outside the FlatCMS marker block are preserved.'), h(Bool,{api,path:'settings.htaccess.autoSync',label:'Auto-sync .htaccess files'}), h(Bool,{api,path:'settings.htaccess.prettyUrls',label:'Pretty SEO URLs enabled'}), h(Bool,{api,path:'settings.htaccess.forceHttps',label:'Force HTTPS redirect'}), h(Bool,{api,path:'settings.htaccess.removeWww',label:'Redirect www to non-www'}), h(Txt,{api,path:'settings.htaccess.assetCacheDays',label:'Asset cache days',type:'number'}), h(Bool,{api,path:'settings.htaccess.protectJson',label:'Block direct access to data.json'}), h('button',{className:'btn-cms mt-2',onClick:sync},'Sync .htaccess now'), h('div',{className:'small muted mt-3'}, 'Root .htaccess: ', String(!!status.rootExists), ' / Assets .htaccess: ', String(!!status.assetsExists), ' / Root writable: ', String(!!status.rootWritable), ' / Assets writable: ', String(!!status.assetsWritable))); }
    function JsonTab({api}){ const [txt,setTxt]=useState(()=>JSON.stringify(api.data,null,2)); useEffect(()=>setTxt(JSON.stringify(api.data,null,2)),[api.data.version]); function apply(){ try{ api.replace(JSON.parse(txt)); }catch(e){ api.setNotice('JSON error: '+e.message); } } return h(React.Fragment,null, h(Field,{label:'Full data.json editor'}, h('textarea',{className:'form-control',rows:18,value:txt,onChange:e=>setTxt(e.target.value),spellCheck:false})), h('button',{className:'btn-cms me-2',onClick:apply},'Apply JSON'), h('button',{className:'btn-ghost',onClick:()=>{const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(api.data,null,2)],{type:'application/json'}));a.download='data.json';a.click();}},'Download JSON')); }

    ReactDOM.createRoot(rootEl).render(h(App));
  })();
  </script>
</body>
</html>
