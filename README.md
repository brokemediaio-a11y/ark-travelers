# ARK Travelers – WordPress Child Theme

Premium Scandinavian-style child theme for **Hello Elementor**, for a travel agency specializing in Umrah packages and international flights. Features parallax effects, glassmorphism, smooth animations, and scroll reveals.

## Requirements

- **WordPress** 6.0+
- **Parent theme:** Hello Elementor
- **PHP** 8.0+
- **Required plugins:** None (standalone theme)
- **Recommended plugins:** Contact Form 7 (optional; theme includes its own contact form), Yoast SEO

## Installation

1. Install and activate **Hello Elementor** (Appearance → Themes → Add New → search "Hello Elementor").
2. Zip the `ark-travelers` folder so that **style.css** and **functions.php** are at the root of the archive (zip the folder itself so the zip contains `ark-travelers/style.css`).
3. In WordPress: **Appearance → Themes → Add New → Upload Theme** → choose the zip → **Install Now** → **Activate**.
4. (Optional) Add a theme screenshot: place a **880×660 px** PNG named `screenshot.png` in the theme root for the preview in Appearance → Themes.

## Setup

### Pages and homepage

1. Create the following pages (any title; **slug** must match):
   - **umrah** – Umrah Packages
   - **fly** – FLY (flights)
   - **about** – About Us
   - **contact** – Contact
2. In **Settings → Reading**, set "Your homepage displays" to **A static page** and choose the page you want as the homepage (or leave "Your latest posts" if you prefer a blog front).
3. The theme uses **front-page.php** when a static front page is set; otherwise the blog index is used.

### Menus

1. Go to **Appearance → Menus**.
2. Create a menu with links to Home, Umrah Packages (`/umrah/`), FLY (`/fly/`), About Us (`/about/`), Contact (`/contact/`).
3. Assign it to **Primary Menu** (header) and/or **Footer Menu**.
4. If no menu is assigned to Primary, the theme shows a fallback with these links.

### Umrah packages (CPT)

The theme registers the **Umrah Package** custom post type (`umrah`). To add packages:

1. In the admin, use **Umrah Packages** in the sidebar to add new packages.
2. Each package can have title, content, excerpt, and featured image.
3. Optional custom fields (for single template): `ark_price`, `ark_duration`, `ark_stars`, `ark_badge`, `ark_departures` (array of dates).
4. The listing page (`/umrah/`) includes 8 sample packages in the template; you can replace this with a loop over the `umrah` CPT or keep the static list and link to real package posts.

## File structure

```
ark-travelers/
├── style.css
├── functions.php
├── index.php
├── front-page.php
├── page.php, page-umrah.php, page-fly.php, page-about.php, page-contact.php
├── single-umrah.php
├── header.php, footer.php
├── 404.php
├── screenshot.png   (add your own 880×660)
├── README.md
└── assets/
    ├── css/
    │   ├── base.css
    │   ├── home.css
    │   ├── umrah.css
    │   ├── fly.css
    │   ├── about.css
    │   ├── contact.css
    │   └── single-umrah.css
    ├── js/
    │   ├── main.js
    │   ├── animations.js
    │   ├── packages.js
    │   └── fly.js
    └── images/
        └── logo.svg
```

## Customization

- **Design tokens** are in `assets/css/base.css` (`:root` variables: colours, spacing, transitions). Override in a child stylesheet or via WordPress Customizer if you add support.
- **Logo:** Use Appearance → Customize → Site Identity to set a custom logo, or replace `assets/images/logo.svg`.
- **Contact form:** Submissions are sent via AJAX to the theme’s handler and emailed to the site admin. Configure the recipient in WordPress or adjust `ark_handle_contact_form()` in `functions.php`.

## Features

- **Homepage:** Full-screen hero with parallax, glassmorphic orbs, stats counters, trust marquee, service cards, featured package, why choose, testimonials carousel, final CTA.
- **Umrah page:** Hero, filter sidebar (departure, duration, price, stars, type), sortable package grid, URL params for shareable filter state.
- **Single package:** Hero, sticky booking sidebar, overview, included list, itinerary accordion, FAQ accordion, terms & conditions.
- **Fly page:** Trip type (round-trip/one-way/multi-city), search form, popular routes (click to fill form), mock results, why book, trust badges.
- **About:** Story, values, team, certifications, timeline, CTA.
- **Contact:** Form with AJAX submit, contact info, map placeholder, FAQ accordion.
- **404:** Centred layout, search form, quick links, back to homepage.
- **Header:** Fixed, transparent then solid on scroll, logo, nav, cart icon, mobile menu.
- **Footer:** Four columns (logo/social, quick links, support, newsletter), bottom bar (copyright, address).

## Credits

- **Theme:** ARK Travelers by NEXORDIS  
- **Parent theme:** [Hello Elementor](https://developers.elementor.com/docs/hello-elementor-theme/)  
- **Fonts:** [Sora](https://fonts.google.com/specimen/Sora), [Inter](https://fonts.google.com/specimen/Inter) (Google Fonts)

## License

GPL v2 or later.
