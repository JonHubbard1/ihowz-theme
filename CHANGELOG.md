# Changelog

All notable changes to the iHowz WordPress theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.7.8] - 2026-06-28

### Added
- **Header text-size accessibility control (A+ / A−)** — a 5-step text-size control beside the header search icon. Typography is rem-based, so scaling the root font-size cascades through the whole site. The preference is stored in an `ihowz_textsize` cookie, written only after cookie-consent acceptance; the chosen size is applied server-side before paint (no flash) and resets when consent is withdrawn.
- **Join Now form reordered** — Membership Type → Personal Details → Password → Privacy Preferences → Total To Pay → Payment Method → Promo Code → Join Now & Pay. "Total to pay" is now a standalone row above the payment-method selector.
- **Section dividers** on the Join Now form (after Membership Type, address, passwords, and Preferred Contact Method), with extra spacing above the Payment Method selector.
- **UK postcode & phone validation** on the Join Now form.

### Changed
- Join Now green background now spans the full content column (was inset ~50px each side).
- Theme version bumped to 1.7.8 to cache-bust the new header assets.

## [1.7.7] - 2026-06-27

### Fixed
- Header alignment, mobile menu, and hero background video.

### Changed
- Footer office address updated to Marlowe Innovation Centre, Ramsgate.

## [1.7.2] - 2026-06-27

### Added
- **Header site search** — search icon in the header plus a `search.php` results template.
- **Join form privacy/consent opt-ins** — marketing, newsletter and third-party sharing opt-ins plus a preferred-contact-method selector on the Join Now form.

## [1.7.1] - 2026-06-27

### Changed
- Maintenance release (version bump; no functional changes).

## [1.7.0] - 2026-06-27

### Added
- **Footer "Cookie Settings" link** that re-opens the cookie-consent banner (pairs with iHowz plugin v2.6.0).

### Fixed
- MegaMenu flyouts and panel now fit within the viewport.

## [1.6.8] - 2026-06-26

### Added
- Customizer "Social Media" section (Appearance → Customize → Social Media) with URL fields for Facebook, X (Twitter), LinkedIn and YouTube.
- Header top bar and footer social icons now render only when their URL is configured; leaving a field empty hides that icon.

### Changed
- Social icons in the header and footer are now driven by Customizer settings instead of being hard-coded into the templates.

## [1.0.0] - 2024-09-28

### Added
- Initial theme release
- Complete responsive design system
- Custom post grid layout for news articles
- Header with navigation and search functionality
- Footer with widget areas and navigation
- Custom page templates:
  - Generic page template (`page.php`)
  - News page template (`page-news.php`)
  - Single post template (`single.php`)
  - 404 error page (`404.php`)
- Comment system with custom styling
- Search form with enhanced UX
- JavaScript enhancements:
  - Mobile navigation toggle
  - Smooth scrolling
  - Back-to-top button
  - Header scroll effects
- Widget areas:
  - Main sidebar
  - Three footer columns
- Navigation menu support:
  - Primary header menu
  - Footer menu
- WordPress features:
  - Custom logo support
  - Post thumbnails
  - Automatic feed links
  - HTML5 markup support
- Accessibility features:
  - Proper focus states
  - Screen reader text
  - WCAG compliance
- SEO optimizations:
  - Semantic HTML structure
  - Proper heading hierarchy
  - Meta tag support
- iHowz plugin integration:
  - Styled advertisement blocks
  - Member dashboard compatibility
  - Event system integration
- Development files:
  - Comprehensive README
  - Proper .gitignore
  - Changelog documentation

### Technical Features
- Mobile-first responsive design
- CSS Grid and Flexbox layouts
- Modern JavaScript (ES6+)
- WordPress coding standards compliance
- Proper sanitization and escaping
- Performance optimized
- Cross-browser compatibility

### Design Features
- Modern card-based layout
- Professional typography
- Consistent color scheme
- Smooth animations and transitions
- Clean, minimal aesthetic
- Optimized for readability

[1.7.8]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.7.8
[1.7.7]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.7.7
[1.7.2]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.7.2
[1.7.1]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.7.1
[1.7.0]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.7.0
[1.6.8]: https://github.com/JonHubbard1/ihowz-theme/releases/tag/v1.6.8
[1.0.0]: https://github.com/ihowz/ihowz-theme/releases/tag/v1.0.0