# LPCMS

Landing Page CMS — a flat-file, single-entry PHP CMS with a React-powered one-page frontend, JSON content storage, visual inline editing, optional blog/product catalogues, and quote-basket forms.

LPCMS is designed for fast landing pages, small business websites, product pages, portfolios, service websites, galleries, and blogs where you want a lightweight CMS without a database. The application logic lives in `index.php`; content, theme settings, SEO options, menus, redirects, and sections are stored in JSON.

## Main idea

LPCMS works like a compact headless CMS:

- PHP handles the server-side shell, API, admin login, file writing, SEO metadata, redirects, and `.htaccess` synchronization.
- React renders the public one-page website and admin editing interface in the browser.
- JSON stores pages, blog posts, products, menu items, language settings, gallery items, forms, SEO settings, theme options, and library toggles.
- Apache/LiteSpeed `.htaccess` rules provide clean SEO URLs, compression, cache headers, security headers, and protection for JSON/private files.

The goal is to keep deployment simple: upload the project to PHP hosting, open the site, configure admin email access, and edit content inline.

## What is new in v0.4.0

This package focuses on visual polish and a cleaner admin workflow.

- Softer Bootstrap/iLanding-style frontend design with lighter typography, smoother spacing, card animations, gradient panels, and more modern buttons.
- Cleaner Tabler-inspired admin panel with card groups, icon tabs, softer controls, and better form spacing.
- New editable homepage sections: stats, features, process/timeline, pricing cards, and FAQ accordion.
- Existing editable sections remain: hero, about, services, gallery, testimonials, products, blog, contact, CTA, and footer.
- Visual rich text editor added inside admin for HTML fields. It supports bold, italic, underline, headings, ordered/unordered lists, links, remove formatting, and direct HTML editing.
- Blog admin tab added for creating/editing posts, slugs, dates, excerpts, SEO fields, and rich post body content.
- Product admin editor now includes rich product body editing, keeping product modals, galleries, price/discount fields, buy/quote logic, and quote basket behavior.
- AOS animation library is enabled by default in fresh installs and can still be disabled from the Libraries tab.


## Current package structure

```text
lpcms/
├── index.php              # Main CMS application, API, admin, frontend shell
├── .htaccess              # SEO URLs, performance, security, cache/compression rules
├── README.md              # Project documentation
└── assets/
    ├── .htaccess          # Blocks script execution and protects uploaded files
    ├── css/               # Optional generated/custom CSS files
    ├── docs/              # Documents and downloadable files
    ├── fonts/             # Local fonts
    ├── gallery/           # Gallery images
    ├── img/               # Site images, logos, hero images, section images
    ├── js/                # Optional local JavaScript files
    └── uploads/           # General CMS uploads
```

When installed on a writable server, LPCMS can automatically create missing runtime files and directories, including `data.json`, `.htaccess`, `assets/.htaccess`, and the asset subdirectories.

## Requirements

- PHP 8.0 or newer recommended.
- Apache or LiteSpeed hosting for `.htaccess` URL rewrites.
- Writable project directory for automatic JSON, upload, and `.htaccess` generation.
- PHP `mail()` support for passwordless login links, or SMTP integration inside `cms_send_mail()`.
- HTTPS strongly recommended for admin login and secure cookies.

Nginx-only hosting can run the PHP application, but the `.htaccess` rules must be translated into an Nginx server block.

## Installation

1. Upload all package files to your hosting root or subdirectory.
2. Open `index.php` and change the secret value:

```php
define('CMS_SECRET', 'CHANGE_THIS_TO_A_RANDOM_64_CHARACTER_SECRET');
```

3. Add allowed admin emails manually in `index.php`:

```php
const ADMIN_EMAILS = [
  'admin@example.com',
];
```

4. Open the website in your browser.
5. LPCMS creates missing JSON/config directories automatically when permissions allow it.
6. Click the admin user icon in the footer.
7. Enter an approved admin email address.
8. Open the secure login link sent to that email.
9. Start editing content inline.

If the entered email is not listed in `ADMIN_EMAILS`, the login modal shows an access message asking the user to contact the administrator.

## Admin login

LPCMS uses passwordless email login.

Admin flow:

1. Visitor clicks the footer admin icon.
2. Login modal asks for an email address.
3. `index.php` checks the email against the manual whitelist.
4. If approved, a signed secure login URL is generated.
5. The login link is emailed to the admin.
6. The admin opens the link and receives an admin session.

This keeps the CMS simple while avoiding a visible password form. For production, confirm that outgoing email works reliably on your hosting. SMTP is recommended if PHP `mail()` is disabled or unreliable.

## Public website functionality

LPCMS is built for a modern one-page landing page layout with optional blog/product detail URLs.

Included public sections:

- Header with logo/image replacement.
- JSON-powered menu with dropdown submenu support.
- Language selector from JSON.
- Ajax-style search across one-page content.
- Responsive hamburger navigation below XL breakpoint.
- Hero slider or static hero when only one slide exists.
- About section with image and editable rich text.
- Stats counters for proof points and metrics.
- Feature blocks with icons, text, and visual panel.
- Services cards with load-more option.
- Process/timeline section.
- Pricing card section.
- FAQ accordion section.
- Gallery grid with load-more option and lightbox support.
- Testimonials cards with load-more option.
- Blog section with load-more option and modal/detail support.
- Product section with admin enable/disable option, 8 homepage items by default, 4-per-row desktop grid, load-more button, modal/detail support, prices, discounts, gallery images, buy buttons, quote buttons, and quote basket.
- Contact section with Google Maps embed and contact details box.
- CTA section with modal form.
- Footer with copyright, menu, social follow icons, and scroll-up button.


## Product catalogue and quote basket

LPCMS products can be used like blog posts, but with commerce-style fields and actions. The products section is optional and can be enabled or disabled from the admin panel.

Default product behaviour:

- Initial homepage display shows `products.homeLimit`, set to 8 by default.
- Load more adds `products.batch`, set to 4 by default.
- Desktop product grid uses Bootstrap 4-per-row layout with `col-lg-3`.
- Each product has a clean SEO URL such as `/product/product-slug`.
- Each product opens in a modal window from the homepage grid.
- Detail pages and modals can show title, description, main image, gallery images, SKU, price, old price, discount label, feature list, buy button, add-to-quote button, and social share links.

Important product JSON fields:

```json
{
  "title": "Starter Website",
  "slug": "starter-website",
  "sku": "LPCMS-STARTER",
  "price": 550,
  "oldPrice": 690,
  "discountLabel": "-20%",
  "currency": "EUR",
  "image": "/assets/img/product.jpg",
  "gallery": ["/assets/img/product-1.jpg", "/assets/img/product-2.jpg"],
  "text": "Short card text.",
  "body": "<p>Full product description.</p>",
  "features": ["Feature one", "Feature two"],
  "buyUrl": "#contact",
  "buyButton": true,
  "quoteButton": true,
  "seo": {
    "title": "Starter Website",
    "description": "Starter website product page.",
    "image": "/assets/img/product.jpg"
  }
}
```

Button rules:

- If `products.showBuyButton` is enabled and product `price` is greater than `0`, the Buy button can be shown.
- If product `price` is `0`, the Buy button is hidden and the quote flow is used.
- If `products.showQuoteButton` is enabled and product `quoteButton` is not false, the Add to quote button is shown.
- Product-level `buyButton` and `quoteButton` can override whether actions appear on individual products.

Quote basket flow:

1. Visitor clicks **Add to quote** on one or more products.
2. A floating quote basket button appears.
3. Basket modal lists all selected products with title, SKU, and price/price-on-request.
4. Visitor clicks **Send quote request**.
5. Contact form opens with all basket products inserted into the message textarea.
6. On successful form submission, the quote basket is cleared.

This is designed for service/product catalogues where some items can be sold directly and others require quotation.

## Inline editing

When logged in as admin, content can be edited directly from the page. The admin UX is intended to feel lightweight and fast, similar to inline landing page builders.

Editable areas include:

- Logo and header settings.
- Menu items and submenu items.
- Hero slides.
- Section headings and body text.
- About image/text.
- Services cards.
- Gallery items.
- Testimonials.
- Blog posts.
- Product posts/catalogue items, including prices, discounts, gallery, buy/quote buttons, and SEO fields.
- Contact details.
- CTA text/forms.
- Footer links/social links.
- SEO fields.
- Theme settings.
- Library toggles.
- Redirects.

## JSON content storage

LPCMS stores content in `data.json`. This file is created automatically if missing.

Typical data stored in JSON:

- Site settings.
- Languages.
- Header and footer configuration.
- Navigation/menu tree.
- Landing page sections.
- Blog posts.
- Products, product gallery, prices, discounts, buy URLs, quote settings, and quote basket submissions.
- Gallery items.
- Testimonials.
- Forms.
- SEO metadata.
- Theme tokens.
- Library settings.
- Redirect rules.

The `.htaccess` file blocks direct public access to JSON files where supported by Apache/LiteSpeed.

## SEO URL structure

The package supports clean URLs through `.htaccess` rewrite rules.

Supported URL patterns:

```text
/blog/post-slug
/post/post-slug
/product/product-slug
/products/product-slug
/page/page-slug
/pages/page-slug
```

Optional short page URLs can also resolve page slugs, for example:

```text
/privacy-policy
/about-company
```

Internally, all requests still route through `index.php`. The PHP file detects the route, loads the matching JSON content, and outputs the correct server-side SEO metadata before React renders the page.

## SEO features

LPCMS includes SEO settings for landing pages, blog posts, product pages, and JSON-managed pages.

Supported SEO options include:

- SEO title.
- Meta description.
- Meta robots value.
- Canonical URL.
- Open Graph title/description/image.
- Blog/product/page slug.
- Server-side metadata rendering.
- JSON-LD structured data for supported content types.
- Redirect management.
- Clean URLs with `.htaccess`.

For best SEO results, use unique titles and descriptions for every blog post, product, and page.

## `.htaccess` sync

LPCMS can create and synchronize `.htaccess` rules from the admin/server settings while keeping the application itself in `index.php`.

The generated root `.htaccess` includes:

- Clean URL routing to `index.php`.
- `data.json` protection.
- Directory listing prevention.
- Static asset cache headers.
- Gzip/Brotli compression when supported.
- MIME type hints for modern assets.
- Basic security headers.
- Canonical index routing.

Custom rules should be placed outside the managed block:

```apache
# BEGIN FlatCMS
# generated LPCMS rules
# END FlatCMS
```

This allows LPCMS to update its own rules without deleting your custom server rules.

## Assets directory

The `assets/` directory is organized for clean media management:

- `assets/img/` for logos, hero images, section images, and general public images.
- `assets/gallery/` for gallery/lightbox images.
- `assets/uploads/` for general CMS uploads.
- `assets/docs/` for documents and downloads.
- `assets/fonts/` for local fonts.
- `assets/css/` for generated or custom CSS.
- `assets/js/` for optional local scripts.

The `assets/.htaccess` file disables PHP/script execution inside uploads and blocks dangerous file types where Apache/LiteSpeed supports the rules.

## Theme settings

Theme settings are stored in JSON and applied dynamically.

Common theme controls:

- Brand color.
- Accent color.
- Background color.
- Text color.
- Border radius.
- Grid/container class.
- Button style.
- Section spacing.
- Custom CSS.
- Optional SCSS source storage/build settings.

LPCMS is designed around Bootstrap-compatible layout classes, so landing page sections can use Bootstrap grid patterns.

## Optional frontend libraries

The CMS can connect or disconnect optional libraries from admin settings.

Typical library toggles:

- Bootstrap.
- Bootstrap Icons.
- GLightbox/lightbox gallery.
- AOS animation.
- hCaptcha.
- Google Analytics after cookie consent.
- Optional SCSS builder setting.

Disabling unused libraries can improve speed and page score.

## Forms and hCaptcha

LPCMS includes modal/contact form support. hCaptcha can be enabled from admin settings.

For production forms, configure:

- Recipient email.
- Success/error messages.
- hCaptcha site key and secret.
- Spam protection.
- Privacy/cookie text.
- SMTP or trusted mail transport.

## Cookies and Google Analytics

LPCMS supports a cookie consent banner and delayed Google Analytics loading.

Recommended setup:

1. Add your GA measurement ID in settings.
2. Enable the cookie banner.
3. Load analytics only after visitor consent.
4. Link your privacy/cookie page in the footer.

## Redirects

Redirects are stored in JSON and managed from admin settings.

Use redirects for:

- Old page URLs.
- Changed blog slugs.
- Changed product slugs.
- Campaign URLs.
- Removing `/index.php` from public links.

Where possible, use permanent `301` redirects for moved content.

## Performance notes

The generated `.htaccess` improves speed by enabling:

- Long cache lifetime for static files.
- Compression for text assets.
- Modern MIME types.
- Directory listing prevention.
- Cleaner canonical URLs.

Additional performance recommendations:

- Compress uploaded images before publishing.
- Prefer WebP or AVIF for large images.
- Disable unused frontend libraries.
- Keep gallery image dimensions reasonable.
- Use a CDN for high-traffic sites.
- Enable server-side HTTPS and HTTP/2 or HTTP/3 where available.

## Security notes

LPCMS is intentionally simple, but production sites should still be hardened.

Recommended production checklist:

- Change `CMS_SECRET` before launch.
- Use a strong random secret of at least 64 characters.
- Add only trusted emails to `ADMIN_EMAILS`.
- Serve the site over HTTPS.
- Confirm `.htaccess` protection is working.
- Keep file permissions tight.
- Back up `data.json` and `assets/` regularly.
- Use SMTP for login links and form mail.
- Consider rate limiting login requests at the server or CDN level.
- Review custom HTML embeds before publishing.

## Backup

To back up an LPCMS website, copy:

```text
data.json
assets/
index.php
.htaccess
```

The most important runtime files are `data.json` and `assets/`, because those contain content and uploaded media.

## Development naming

This project is named `lpcms` based on the repository name:

```text
raivis-kalnins/lpcms
```

Future ZIP packages should use the same naming convention, for example:

```text
lpcms.zip
lpcms-v0.1.0.zip
lpcms-YYYY-MM-DD.zip
```

## Roadmap ideas

Possible next improvements:

- First-class tabs block manager.
- Product variants and related products manager.
- Sitemap XML generator.
- Robots.txt generator.
- Better image crop/resize workflow.
- SMTP settings UI.
- Backup/export/import tools.
- More granular user roles.
- Optional local build for React/Bootstrap assets.

## License

Add your preferred license before public release.
