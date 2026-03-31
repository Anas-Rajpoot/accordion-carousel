# HS Accordion Carousel — WordPress Plugin v2.0

  Responsive accordion carousel with desktop horizontal accordion and mobile vertical stacked behaviour.
  Full **Elementor widget** with drag-and-drop settings panel.

  ---

  ## ⬇️ Download & Install

  ### Step 1 — Download the ZIP
  👉 [**Download hs-accordion-carousel.zip**](https://github.com/Anas-Rajpoot/accordion-carousel/raw/main/hs-accordion-carousel.zip)

  ### Step 2 — Upload to WordPress
  1. Go to **WordPress Admin → Plugins → Add New → Upload Plugin**
  2. Click **Choose File** and select `hs-accordion-carousel.zip`
  3. Click **Install Now**
  4. Click **Activate Plugin**

  ### Step 3 — Use in Elementor
  1. Open any page in **Elementor**
  2. Search **"Accordion Carousel"** in the widget panel
  3. Drag it onto the page
  4. Customise content and colours in the left panel

  ---

  ## Requirements

  | | |
  |---|---|
  | WordPress | 5.5 or higher |
  | Elementor | Free version is enough |
  | PHP | 7.4 or higher |

  ---

  ## Plugin Files

  ```
  hs-accordion-carousel/
  ├── hs-accordion-carousel.php          ← Main plugin file
  ├── includes/
  │   └── class-hs-carousel-widget.php  ← Elementor widget
  └── assets/
      ├── hs-carousel.css
      └── hs-carousel.js
  ```

  ---

  ## Shortcode Fallback (without Elementor)

  ```
  [hs_carousel bg_color="#0098ED" card_color="#1DCBF1" btn_label="Learn more"]
    [hs_card title="Service One"   link="/service-1"]Body text.[/hs_card]
    [hs_card title="Service Two"   link="/service-2"]Body text.[/hs_card]
    [hs_card title="Service Three" link="/service-3"]Body text.[/hs_card]
  [/hs_carousel]
  ```
  