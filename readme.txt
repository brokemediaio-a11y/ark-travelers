=== ARK Travelers ===

Contributors: arktravelers
Requires at least: 5.9
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Premium child theme for Hello Elementor. Scandinavian minimalism for Umrah packages and international flights.

== Description ==

ARK Travelers is a production-ready WordPress child theme for the Hello Elementor parent theme. It is designed for a premium travel agency specializing in Umrah packages and international flights.

**Design:**
* Scandinavian minimalism with premium modern aesthetics
* Color palette: Whisper white (#F8F9FA), Steel teal (#4A90A4), Deep navy (#0a0e14), Charcoal (#2C3338), Light grey border (#E8EAED)
* Typography: Sora (headings), Inter (body) via Google Fonts
* Glassmorphism, parallax-ready sections, smooth animations, scroll-reveal effects

**Features:**
* Full design system in CSS custom properties
* Utility classes: .ark-hero, .ark-dark-section, .ark-glass, .ark-card-glass, .ark-btn-accent, .ark-reveal, .ark-parallax
* JavaScript: scroll reveal (IntersectionObserver), parallax, smooth scroll
* Respects prefers-reduced-motion

== Installation ==

1. Install and activate the parent theme "Hello Elementor".
2. Zip the `ark-travelers` folder (so that style.css and functions.php are at the root of the zip).
3. In WordPress: Appearance → Themes → Add New → Upload Theme → Choose the zip file → Install Now → Activate.
4. Add a screenshot: place a 1200×900 px image named `screenshot.png` in the theme root (optional).

== File structure ==

ark-travelers/
  style.css
  functions.php
  readme.txt
  screenshot.png (add your own 1200×900)
  assets/
    css/
      theme.css
    js/
      theme.js
    images/

== Using with Elementor ==

* Use Elementor sections/containers as usual. Add CSS classes in the Advanced tab:
  * Section: `ark-hero` or `ark-dark-section` for dark navy hero
  * Section: `ark-parallax` and inner column/widget: `ark-parallax-layer` for parallax
  * Any widget/column: `ark-reveal` or `ark-reveal-stagger` for scroll reveal
  * Container: `ark-glass` or `ark-card-glass` for glassmorphism
* Buttons: add class `ark-btn-accent` to Elementor button for steel teal style.

== Changelog ==

= 1.0.0 =
* Initial release. Design system, glassmorphism, parallax, scroll reveal, animations.
