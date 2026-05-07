# DR Metals Consolidated Preview

This package keeps the visual language, layout, copy, and overall feel of the static preview, then layers in the PHP/config features from the PHP preview.

## What changed

- The static version remains the visual source of truth.
- `How It Works` now uses the darker warm background from the PHP version.
- The hero footer list now reads:
  `Castings. Fabrications. Rubber. Plastics. Coating. Assembly. Stock & Release.`
- The default site header logo is now a lowercase `drmetals` wordmark in the old spirit with the burnt-orange accent.
- Both logo concepts are included:
  - `assets/img/logo-lowercase.svg`
  - `assets/img/logo-allcaps-ivy.svg`
- The top hero section has a desktop-only width fix so the headline column stops collapsing into a tall skinny stack, while the mobile view stays unchanged.

## Main files

- `index.html` : static visual preview
- `index.php` : config-driven PHP version of the same page
- `contact.php` : form handler
- `thanks.html` : static confirmation page
- `thanks.php` : PHP confirmation page
- `favicon.ico` : site favicon
- `favicon.png` : PNG favicon source
- `config.json` : contact values, brand notes, and mail settings
- `bootstrap.php` : small config helper
- `.htaccess` : prefers `index.php`

## Config

Edit `config.json` for:

- contact email
- lead recipient
- phone numbers
- address
- form messages
- mail settings

The current values are prefilled from the public DR Metals details already used in the drafts.

## Form behavior

- `index.php` reads contact details from `config.json`
- `contact.php` accepts the form post
- if `mail.enabled` is `false`, requests are saved to `storage/inquiries.log`
- if `mail.enabled` is `true`, the handler tries PHP `mail()`
- if sending fails, the request still falls back to `storage/inquiries.log`

## Mail note

The config includes an SMTP-shaped block for future reference, but this package currently sends with PHP `mail()` only. If you later want true SMTP delivery, that would be the next server-side upgrade.

## Logo note

The old lowercase wordmark appears closest to a common Helvetica / Arial style. The compromise mark in this package uses that family feel with the burnt-orange accent, and the alternate all-caps concept pairs the ivy mark with the current site’s cleaner direction.

## Deploy

1. Upload everything in this folder into your target web folder.
2. On a PHP-capable server, `index.php` will be the default page.
3. On a simple static host, `index.html` still works as a visual preview.
4. For your dev server, visit the folder URL in both desktop and mobile browsers.

## Important note

This workspace does not have PHP installed, so the PHP files could not be executed locally here. The merged PHP files are prepared for a normal LAMP-style server, but the final mail/form check should happen on your personal dev server or hosting environment.
