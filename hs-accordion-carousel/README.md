# HS Accordion Carousel — WordPress Plugin v2.0

A responsive accordion carousel with **desktop horizontal accordion** and **mobile vertical stacked** behaviour.
Fully integrated as an **Elementor widget** with a visual drag-and-drop settings panel.

---

## Installation

1. Copy the `hs-accordion-carousel` folder to `/wp-content/plugins/`
2. Activate via **WordPress Admin → Plugins → HS Accordion Carousel**

---

## Using in Elementor (Recommended)

1. Open any page in **Elementor**
2. Search for **"Accordion Carousel"** in the widget panel
3. Drag it onto the page
4. Use the left panel to configure content and styles

### Content Panel
| Setting | Description |
|---|---|
| **Cards** | Repeater — add as many cards as needed. Each card has: Title, Body Text, Button Link |
| **Button Label** | Text shown on all card buttons |

### Style Panel — Section Background
| Setting | Description |
|---|---|
| Background Color | The outer section colour (default `#0098ED`) |

### Style Panel — Cards
| Setting | Description |
|---|---|
| Card Background | Card colour (default `#1DCBF1`) |
| Card Height | Desktop track height in px (default 400) |
| Open Card Width | Width of each open card in px (default 403) |

### Style Panel — Badge (Number)
| Setting | Description |
|---|---|
| Badge Background | Closed-card badge bg (default white) |
| Badge Number Color | Closed-card badge number colour (default cyan) |

> Open-card badge automatically uses the section background colour.

### Style Panel — Typography
| Setting | Description |
|---|---|
| Title Color | Card title colour |
| Title Font Size | Card title size in px |
| Body Text Color | Body paragraph colour |
| Body Font Size | Body paragraph size in px |
| Closed Strip Title Color | Vertical title on closed desktop strips |

### Style Panel — Button
| Setting | Description |
|---|---|
| Button Background | Button bg colour |
| Button Text Color | Button text colour |
| Button Font Size | Button label size in px |

### Style Panel — Navigation Arrows
| Setting | Description |
|---|---|
| Arrow Button Color | Background colour of the prev/next arrows |

---

## Shortcode Fallback (without Elementor)

```
[hs_carousel bg_color="#0098ED" card_color="#1DCBF1" btn_label="Learn more"]
  [hs_card title="Service One"   link="/service-1"]Body text.[/hs_card]
  [hs_card title="Service Two"   link="/service-2"]Body text.[/hs_card]
  [hs_card title="Service Three" link="/service-3"]Body text.[/hs_card]
[/hs_carousel]
```

Multiple independent instances on the same page are fully supported.
