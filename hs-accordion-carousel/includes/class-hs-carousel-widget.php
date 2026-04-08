<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HS Accordion Carousel — Elementor Widget
 *
 * Multiple independent instances per page are fully supported.
 * Assets load only on pages that contain this widget (via get_style/script_depends).
 * All CSS is scoped under .hs-section to prevent conflicts.
 *
 * Adding more widgets to this plugin in future:
 *   1. Create a new class file in includes/ (e.g. class-hs-other-widget.php)
 *   2. Register it in hs-accordion-carousel.php via the same elementor/widgets/register hook
 */
class HS_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name()        { return 'hs_accordion_carousel'; }
    public function get_title()       { return __( 'Accordion Carousel', 'hs-accordion-carousel' ); }
    public function get_icon()        { return 'eicon-media-carousel'; }
    public function get_categories()  { return [ 'general' ]; }
    public function get_keywords()    { return [ 'carousel', 'accordion', 'cards', 'services' ]; }

    public function get_style_depends()  { return [ 'hs-carousel-css' ]; }
    public function get_script_depends() { return [ 'hs-carousel-js' ]; }

    /* ══════════════════════════════════════════════════════
       CONTROLS
    ══════════════════════════════════════════════════════ */
    protected function register_controls() {

        /* ────────────────────────────────────────────────
           CONTENT TAB — Cards
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'section_cards', [
            'label' => __( 'Cards', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        /* Header type: Text or Image */
        $repeater->add_control( 'header_type', [
            'label'   => __( 'Header Type', 'hs-accordion-carousel' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'text'  => [ 'title' => __( 'Title Text', 'hs-accordion-carousel' ), 'icon' => 'eicon-t-letter' ],
                'image' => [ 'title' => __( 'Image',      'hs-accordion-carousel' ), 'icon' => 'eicon-image'    ],
            ],
            'default' => 'text',
            'toggle'  => false,
        ] );

        /* Title — shown when header_type = text */
        $repeater->add_control( 'card_title', [
            'label'       => __( 'Title', 'hs-accordion-carousel' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => __( 'Card Title', 'hs-accordion-carousel' ),
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
            'condition'   => [ 'header_type' => 'text' ],
        ] );

        /* Image — shown when header_type = image */
        $repeater->add_control( 'card_image', [
            'label'     => __( 'Header Image', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::MEDIA,
            'default'   => [ 'url' => '' ],
            'dynamic'   => [ 'active' => true ],
            'condition' => [ 'header_type' => 'image' ],
        ] );

        /* Alt text for image (accessibility) */
        $repeater->add_control( 'card_image_alt', [
            'label'     => __( 'Image Alt Text', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => '',
            'dynamic'   => [ 'active' => true ],
            'condition' => [ 'header_type' => 'image' ],
        ] );

        /* Closed-strip fallback title — always used for the vertical strip on desktop */
        $repeater->add_control( 'card_strip_title', [
            'label'       => __( 'Closed Strip Label', 'hs-accordion-carousel' ),
            'description' => __( 'Text shown on the narrow closed strip (desktop). Required when Header Type is Image.', 'hs-accordion-carousel' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => __( 'Card Title', 'hs-accordion-carousel' ),
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
            'condition'   => [ 'header_type' => 'image' ],
        ] );

        /* Body */
        $repeater->add_control( 'card_body', [
            'label'   => __( 'Body Text', 'hs-accordion-carousel' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __( 'Describe your service or feature here.', 'hs-accordion-carousel' ),
            'rows'    => 4,
            'dynamic' => [ 'active' => true ],
        ] );

        /* Link */
        $repeater->add_control( 'card_link', [
            'label'         => __( 'Button Link', 'hs-accordion-carousel' ),
            'type'          => \Elementor\Controls_Manager::URL,
            'placeholder'   => 'https://example.com',
            'show_external' => true,
            'default'       => [ 'url' => '' ],
            'dynamic'       => [ 'active' => true ],
        ] );

        /* Per-card button label override */
        $repeater->add_control( 'card_btn_label', [
            'label'       => __( 'Button Label (override)', 'hs-accordion-carousel' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __( 'Leave empty to use global label', 'hs-accordion-carousel' ),
            'dynamic'     => [ 'active' => true ],
        ] );

        $this->add_control( 'cards', [
            'label'       => __( 'Cards', 'hs-accordion-carousel' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'header_type' => 'text', 'card_title' => 'Service One',   'card_strip_title' => 'Service One',   'card_body' => 'Describe your first service here.'  ],
                [ 'header_type' => 'text', 'card_title' => 'Service Two',   'card_strip_title' => 'Service Two',   'card_body' => 'Describe your second service here.' ],
                [ 'header_type' => 'text', 'card_title' => 'Service Three', 'card_strip_title' => 'Service Three', 'card_body' => 'Describe your third service here.'  ],
                [ 'header_type' => 'text', 'card_title' => 'Service Four',  'card_strip_title' => 'Service Four',  'card_body' => 'Describe your fourth service here.' ],
            ],
            'title_field' => '<# var t = header_type==="image" ? card_strip_title : card_title; #>{{{ t }}}',
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           CONTENT TAB — Button
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'section_button', [
            'label' => __( 'Button', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'btn_label', [
            'label'   => __( 'Default Button Label', 'hs-accordion-carousel' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Discover more', 'hs-accordion-carousel' ),
            'dynamic' => [ 'active' => true ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Section Background
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_section', [
            'label' => __( 'Section Background', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'bg_color', [
            'label'     => __( 'Background Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0098ED',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-bg: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Cards
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_cards', [
            'label' => __( 'Cards', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'card_color', [
            'label'     => __( 'Open Card Background', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#1DCBF1',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-card: {{VALUE}};' ],
        ] );

        $this->add_control( 'closed_card_color', [
            'label'       => __( 'Closed Card Background', 'hs-accordion-carousel' ),
            'description' => __( 'Leave empty to use the same color as Open Card.', 'hs-accordion-carousel' ),
            'type'        => \Elementor\Controls_Manager::COLOR,
            'selectors'   => [ '{{WRAPPER}} .hs-section' => '--hs-card-closed: {{VALUE}};' ],
        ] );

        $this->add_control( 'card_height', [
            'label'      => __( 'Card Height (px)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 280, 'max' => 700, 'step' => 10 ] ],
            'default'    => [ 'size' => 400, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-card-height: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'card_width', [
            'label'      => __( 'Open Card Width (px)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 280, 'max' => 700, 'step' => 10 ] ],
            'default'    => [ 'size' => 403, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-card-width: {{SIZE}}px;' ],
        ] );

        /* ── Open Card Padding ── */
        $this->add_control( 'open_card_padding_heading', [
            'label'     => __( 'Open Card Inner Padding', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'open_card_padding', [
            'label'      => __( 'Padding (T / R / B / L)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default'    => [ 'top' => '30', 'right' => '88', 'bottom' => '28', 'left' => '26', 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [
                '{{WRAPPER}} .hs-section' => '--hs-open-pad-top: {{TOP}}{{UNIT}}; --hs-open-pad-right: {{RIGHT}}{{UNIT}}; --hs-open-pad-bottom: {{BOTTOM}}{{UNIT}}; --hs-open-pad-left: {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Badge (Number)
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_badge', [
            'label' => __( 'Badge (Number)', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'badge_heading_open', [
            'label' => __( 'Open Card Badge', 'hs-accordion-carousel' ),
            'type'  => \Elementor\Controls_Manager::HEADING,
        ] );

        $this->start_controls_tabs( 'badge_open_tabs' );

            $this->start_controls_tab( 'badge_open_normal', [ 'label' => __( 'Normal', 'hs-accordion-carousel' ) ] );

                $this->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name'     => 'badge_open_bg',
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-num-badge, {{WRAPPER}} .hs-item:not(.hs-closed) .hs-card-badge',
                        'fields_options' => [
                            'background' => [ 'default' => 'classic' ],
                            'color'      => [ 'default' => '#0098ED' ],
                        ],
                    ]
                );

                $this->add_control( 'badge_open_text_color', [
                    'label'     => __( 'Number Color', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-num-badge'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-card-badge' => 'color: {{VALUE}};',
                    ],
                ] );

            $this->end_controls_tab();

            $this->start_controls_tab( 'badge_open_hover', [ 'label' => __( 'Hover', 'hs-accordion-carousel' ) ] );

                $this->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name'     => 'badge_open_hover_bg',
                        'types'    => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .hs-item:not(.hs-closed):hover .hs-num-badge, {{WRAPPER}} .hs-item:not(.hs-closed):hover .hs-card-badge',
                        'fields_options' => [
                            'background' => [ 'default' => 'gradient' ],
                            'color'      => [ 'default' => '#00dfa5' ],
                            'color_b'    => [ 'default' => '#0098ED' ],
                        ],
                    ]
                );

                $this->add_control( 'badge_open_hover_text_color', [
                    'label'     => __( 'Number Color on Hover', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .hs-item:not(.hs-closed):hover .hs-num-badge'  => 'color: {{VALUE}};',
                        '{{WRAPPER}} .hs-item:not(.hs-closed):hover .hs-card-badge' => 'color: {{VALUE}};',
                    ],
                ] );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control( 'badge_open_separator', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );

        $this->add_control( 'badge_open_width', [
            'label'      => __( 'Open Badge Width', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 32, 'max' => 120 ] ],
            'default'    => [ 'size' => 54, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-num-badge'  => 'width: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-card-badge' => 'width: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'badge_open_height', [
            'label'      => __( 'Open Badge Height', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 32, 'max' => 120 ] ],
            'default'    => [ 'size' => 54, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-num-badge'  => 'height: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-card-badge' => 'height: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'badge_open_font_size', [
            'label'      => __( 'Open Badge Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 48 ] ],
            'default'    => [ 'size' => 24, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-num-badge'  => 'font-size: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item:not(.hs-closed) .hs-card-badge' => 'font-size: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'badge_heading_closed', [
            'label'     => __( 'Closed Card Badge', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'badge_bg', [
            'label'     => __( 'Badge Background', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-badge-bg: {{VALUE}};' ],
        ] );

        $this->add_control( 'badge_text_color', [
            'label'     => __( 'Badge Number Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#1DCBF1',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-badge-text: {{VALUE}};' ],
        ] );

        $this->add_control( 'badge_closed_separator', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ] );

        $this->add_control( 'badge_closed_width', [
            'label'      => __( 'Closed Badge Width', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 24, 'max' => 100 ] ],
            'default'    => [ 'size' => 46, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item.hs-closed .hs-num-badge'  => 'width: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item.hs-closed .hs-card-badge' => 'width: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'badge_closed_height', [
            'label'      => __( 'Closed Badge Height', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 24, 'max' => 100 ] ],
            'default'    => [ 'size' => 52, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item.hs-closed .hs-num-badge'  => 'height: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item.hs-closed .hs-card-badge' => 'height: {{SIZE}}px;',
            ],
        ] );

        $this->add_control( 'badge_closed_font_size', [
            'label'      => __( 'Closed Badge Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 40 ] ],
            'default'    => [ 'size' => 19, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .hs-item.hs-closed .hs-num-badge'  => 'font-size: {{SIZE}}px;',
                '{{WRAPPER}} .hs-item.hs-closed .hs-card-badge' => 'font-size: {{SIZE}}px;',
            ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Typography
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_typography', [
            'label' => __( 'Typography', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        /* Font family — full Google Fonts picker, default Poppins */
        $this->add_control( 'font_family', [
            'label'     => __( 'Font Family', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::FONT,
            'default'   => 'Poppins',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-font: {{VALUE}}, sans-serif;' ],
        ] );

        $this->add_control( 'title_color', [
            'label'     => __( 'Title Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#09202e',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-title: {{VALUE}};' ],
            'separator' => 'before',
        ] );

        $this->add_control( 'title_size', [
            'label'      => __( 'Title Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 12, 'max' => 40 ] ],
            'default'    => [ 'size' => 18, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-open-title, {{WRAPPER}} .hs-card-title' => 'font-size: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'body_color', [
            'label'     => __( 'Body Text Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.94)',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-body: {{VALUE}};' ],
        ] );

        $this->add_control( 'body_size', [
            'label'      => __( 'Body Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 24 ] ],
            'default'    => [ 'size' => 14, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-body' => 'font-size: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'divider_color', [
            'label'     => __( 'Divider Line Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.55)',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-divider: {{VALUE}};' ],
        ] );

        $this->add_control( 'divider_height', [
            'label'      => __( 'Divider Thickness', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 1, 'max' => 6 ] ],
            'default'    => [ 'size' => 1.5, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-divider' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Closed Strip
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_closed_strip', [
            'label' => __( '📌 Closed Strip', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        /* ── Strip Width ── */
        $this->add_control( 'closed_strip_width', [
            'label'      => __( 'Strip Width', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 40, 'max' => 200, 'step' => 2 ] ],
            'default'    => [ 'size' => 72, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-strip-w: {{SIZE}}px;' ],
        ] );

        /* ── Strip Padding ── */
        $this->add_control( 'closed_strip_padding', [
            'label'      => __( 'Strip Padding', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default'    => [ 'top' => '16', 'right' => '0', 'bottom' => '28', 'left' => '0', 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [
                '{{WRAPPER}} .hs-section' => '--hs-closed-pad-top: {{TOP}}{{UNIT}}; --hs-closed-pad-right: {{RIGHT}}{{UNIT}}; --hs-closed-pad-bottom: {{BOTTOM}}{{UNIT}}; --hs-closed-pad-left: {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'closed_strip_type', [
            'label'   => __( 'Strip Content', 'hs-accordion-carousel' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'image' => __( 'Image  (image-type cards)', 'hs-accordion-carousel' ),
                'text'  => __( 'Text   (strip title)', 'hs-accordion-carousel' ),
            ],
            'default' => 'image',
            'description' => __( 'Choose what shows on the narrow closed strip for image-type cards.', 'hs-accordion-carousel' ),
        ] );

        /* ── Image controls ── */
        $this->add_control( 'closed_img_heading', [
            'label'     => __( 'Image Settings', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'closed_strip_type' => 'image' ],
        ] );

        $this->add_control( 'closed_img_w', [
            'label'      => __( 'Image Width (pre-rotation)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 40, 'max' => 300 ] ],
            'default'    => [ 'size' => 120, 'unit' => 'px' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-w: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'closed_img_h', [
            'label'      => __( 'Image Height (pre-rotation)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
            'default'    => [ 'size' => 44, 'unit' => 'px' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-h: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'closed_img_fit', [
            'label'     => __( 'Object Fit', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => [
                'contain' => __( 'Contain', 'hs-accordion-carousel' ),
                'cover'   => __( 'Cover',   'hs-accordion-carousel' ),
                'fill'    => __( 'Fill',    'hs-accordion-carousel' ),
            ],
            'default'   => 'contain',
            'condition' => [ 'closed_strip_type' => 'image' ],
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-fit: {{VALUE}};' ],
        ] );

        $this->add_control( 'closed_img_radius', [
            'label'      => __( 'Border Radius', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
            'default'    => [ 'size' => 8, 'unit' => 'px' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'closed_img_bg', [
            'label'     => __( 'Image Background', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.12)',
            'condition' => [ 'closed_strip_type' => 'image' ],
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-bg: {{VALUE}};' ],
        ] );

        $this->add_control( 'closed_img_transform_heading', [
            'label'     => __( 'Image Transform', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'closed_strip_type' => 'image' ],
        ] );

        $this->add_control( 'closed_img_rotate', [
            'label'      => __( 'Rotation (deg)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'deg' ],
            'range'      => [ 'deg' => [ 'min' => -180, 'max' => 180 ] ],
            'default'    => [ 'size' => -90, 'unit' => 'deg' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-rotate: {{SIZE}}deg;' ],
        ] );

        $this->add_control( 'closed_img_tx', [
            'label'      => __( 'Translate X (px)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => -100, 'max' => 100 ] ],
            'default'    => [ 'size' => 0, 'unit' => 'px' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-tx: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'closed_img_ty', [
            'label'      => __( 'Translate Y (px)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => -100, 'max' => 100 ] ],
            'default'    => [ 'size' => 0, 'unit' => 'px' ],
            'condition'  => [ 'closed_strip_type' => 'image' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-closed-img-ty: {{SIZE}}px;' ],
        ] );

        /* ── Text controls (strip title — used in both Text mode and as fallback) ── */
        $this->add_control( 'closed_text_heading', [
            'label'     => __( 'Strip Text Settings', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'closed_title_color', [
            'label'     => __( 'Strip Title Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#09202e',
            'selectors' => [ '{{WRAPPER}} .hs-closed-title' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'closed_title_size', [
            'label'      => __( 'Strip Title Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 9, 'max' => 30 ] ],
            'default'    => [ 'size' => 13, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-closed-title' => 'font-size: {{SIZE}}px;' ],
        ] );

        $this->add_control( 'closed_title_weight', [
            'label'   => __( 'Font Weight', 'hs-accordion-carousel' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '700',
            'options' => [
                '400' => __( 'Normal (400)', 'hs-accordion-carousel' ),
                '500' => __( 'Medium (500)',  'hs-accordion-carousel' ),
                '600' => __( 'Semi Bold (600)', 'hs-accordion-carousel' ),
                '700' => __( 'Bold (700)',    'hs-accordion-carousel' ),
            ],
            'selectors' => [ '{{WRAPPER}} .hs-closed-title' => 'font-weight: {{VALUE}};' ],
        ] );

        $this->add_control( 'closed_title_spacing', [
            'label'      => __( 'Letter Spacing', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 5 ] ],
            'default'    => [ 'size' => 0.3, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-closed-title' => 'letter-spacing: {{SIZE}}px;' ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Button
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_button', [
            'label' => __( 'Button', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        /* Position */
        $this->add_control( 'btn_position', [
            'label'     => __( 'Button Position', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [ 'title' => __( 'Left',   'hs-accordion-carousel' ), 'icon' => 'eicon-text-align-left'   ],
                'center'     => [ 'title' => __( 'Center', 'hs-accordion-carousel' ), 'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => __( 'Right',  'hs-accordion-carousel' ), 'icon' => 'eicon-text-align-right'  ],
            ],
            'default'   => 'flex-start',
            'selectors' => [ '{{WRAPPER}} .hs-btn-wrap' => 'justify-content: {{VALUE}};' ],
        ] );

        $this->start_controls_tabs( 'btn_tabs' );

            $this->start_controls_tab( 'btn_normal', [ 'label' => __( 'Normal', 'hs-accordion-carousel' ) ] );

                $this->add_control( 'btn_bg', [
                    'label'     => __( 'Background', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-btn-bg: {{VALUE}};' ],
                ] );

                $this->add_control( 'btn_color', [
                    'label'     => __( 'Text Color', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'default'   => '#1DCBF1',
                    'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-btn-text: {{VALUE}};' ],
                ] );

                /* Box shadow — Normal state */
                $this->add_group_control(
                    \Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name'     => 'btn_box_shadow',
                        'label'    => __( 'Box Shadow', 'hs-accordion-carousel' ),
                        'selector' => '{{WRAPPER}} .hs-btn',
                    ]
                );

            $this->end_controls_tab();

            $this->start_controls_tab( 'btn_hover', [ 'label' => __( 'Hover', 'hs-accordion-carousel' ) ] );

                $this->add_control( 'btn_hover_bg', [
                    'label'     => __( 'Background on Hover', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .hs-btn:hover' => 'background: {{VALUE}} !important;' ],
                ] );

                $this->add_control( 'btn_hover_color', [
                    'label'     => __( 'Text Color on Hover', 'hs-accordion-carousel' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .hs-btn:hover' => 'color: {{VALUE}} !important;' ],
                ] );

                /* Box shadow — Hover state */
                $this->add_group_control(
                    \Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name'     => 'btn_hover_box_shadow',
                        'label'    => __( 'Box Shadow on Hover', 'hs-accordion-carousel' ),
                        'selector' => '{{WRAPPER}} .hs-btn:hover',
                    ]
                );

            $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control( 'btn_size', [
            'label'      => __( 'Font Size', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'separator'  => 'before',
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 22 ] ],
            'default'    => [ 'size' => 14, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-btn' => 'font-size: {{SIZE}}px;' ],
        ] );

        $this->end_controls_section();

        /* ────────────────────────────────────────────────
           STYLE TAB — Navigation Arrows
        ──────────────────────────────────────────────── */
        $this->start_controls_section( 'style_arrows', [
            'label' => __( 'Navigation Arrows', 'hs-accordion-carousel' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'arrow_color', [
            'label'     => __( 'Button Background Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#00E2A1',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-arrow: {{VALUE}};' ],
        ] );

        /* ── Button (circle) size ── */
        $this->add_control( 'arrow_size', [
            'label'      => __( 'Button Size (circle)', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 24, 'max' => 100, 'step' => 2 ] ],
            'default'    => [ 'size' => 42, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-arrow-size: {{SIZE}}px;' ],
        ] );

        /* ── Icon (chevron) size ── */
        $this->add_control( 'arrow_icon_size', [
            'label'      => __( 'Icon Size ( ‹ › )', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 12, 'max' => 80, 'step' => 2 ] ],
            'default'    => [ 'size' => 36, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-arrow-icon-size: {{SIZE}}px;' ],
        ] );

        /* ── Icon color ── */
        $this->add_control( 'arrow_icon_color', [
            'label'     => __( 'Icon Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-arrow-icon-color: {{VALUE}};' ],
        ] );

        /* ── Stroke width ── */
        $this->add_control( 'arrow_stroke', [
            'label'      => __( 'Icon Stroke Width', 'hs-accordion-carousel' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 1, 'max' => 5, 'step' => 0.5 ] ],
            'default'    => [ 'size' => 2.5, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hs-section' => '--hs-arrow-stroke: {{SIZE}};' ],
        ] );

        $this->end_controls_section();
    }

    /* ══════════════════════════════════════════════════════
       RENDER  (PHP — actual page output)
    ══════════════════════════════════════════════════════ */
    protected function render() {
        $s = $this->get_settings_for_display();

        $wrap_id  = 'hs-wrap-'  . $this->get_id();
        $track_id = 'hs-track-' . $this->get_id();
        $prev_id  = 'hs-prev-'  . $this->get_id();
        $next_id  = 'hs-next-'  . $this->get_id();

        $cards        = ! empty( $s['cards'] )     ? $s['cards']     : [];
        $global_label = ! empty( $s['btn_label'] ) ? $s['btn_label'] : 'Discover more';
        $strip_type   = ! empty( $s['closed_strip_type'] ) ? $s['closed_strip_type'] : 'image';
        $strip_class  = $strip_type === 'text' ? ' hs-strip-text' : '';

        /* Enqueue selected font from Google Fonts (only when not Poppins — Poppins is already loaded) */
        $font = ! empty( $s['font_family'] ) ? $s['font_family'] : 'Poppins';
        if ( 'Poppins' !== $font ) {
            $font_url = 'https://fonts.googleapis.com/css2?family=' . urlencode( $font ) . ':wght@400;600;700&display=swap';
            wp_enqueue_style( 'hs-carousel-font-' . sanitize_title( $font ), $font_url, [], null );
        }
        ?>
        <section class="hs-section<?php echo esc_attr( $strip_class ); ?>">
            <div class="hs-inner">
                <div class="hs-track-wrap" id="<?php echo esc_attr( $wrap_id ); ?>">
                    <div class="hs-track" id="<?php echo esc_attr( $track_id ); ?>">

                        <?php foreach ( $cards as $index => $card ) :
                            $num         = $index + 1;
                            $is_image    = ( isset( $card['header_type'] ) && $card['header_type'] === 'image' );
                            $card_class  = 'hs-item' . ( $index === 0 ? ' hs-closed' : '' ) . ( $is_image ? ' hs-item--image-header' : '' );
                            $link        = ! empty( $card['card_link']['url'] ) ? $card['card_link']['url'] : '';
                            $target      = ! empty( $card['card_link']['is_external'] ) ? ' target="_blank"' : '';
                            $nofollow    = ! empty( $card['card_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                            $btn_label   = ! empty( $card['card_btn_label'] ) ? esc_html( $card['card_btn_label'] ) : esc_html( $global_label );
                            $img_url     = $is_image && ! empty( $card['card_image']['url'] ) ? esc_url( $card['card_image']['url'] ) : '';
                            $img_alt     = $is_image ? esc_attr( $card['card_image_alt'] ?? '' ) : '';
                            /* Strip label: always text — for closed desktop strip */
                            $strip_label = $is_image
                                ? esc_html( $card['card_strip_title'] ?? '' )
                                : esc_html( $card['card_title'] ?? '' );
                            /* Mobile header label */
                            $mobile_label = $strip_label;
                        ?>
                        <div class="<?php echo esc_attr( $card_class ); ?>" data-index="<?php echo $index; ?>">

                            <!-- Desktop badge (top-right on open card, arch on closed strip) -->
                            <div class="hs-num-badge"><?php echo $num; ?></div>

                            <!-- Mobile header row (always visible on mobile) -->
                            <div class="hs-card-header">
                                <?php if ( $is_image && $img_url ) : ?>
                                    <img class="hs-card-header-img" src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                                <?php else : ?>
                                    <span class="hs-card-title"><?php echo $strip_label; ?></span>
                                <?php endif; ?>
                                <div class="hs-card-badge"><?php echo $num; ?></div>
                            </div>

                            <!-- Desktop closed strip: vertical text OR image thumbnail -->
                            <div class="hs-closed-content">
                                <span class="hs-closed-title"><?php echo $strip_label; ?></span>
                                <?php if ( $is_image && $img_url ) : ?>
                                    <img class="hs-closed-img" src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                                <?php endif; ?>
                            </div>

                            <!-- Open card content -->
                            <div class="hs-open-content">
                                <?php if ( $is_image && $img_url ) : ?>
                                    <img class="hs-header-img" src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                                <?php else : ?>
                                    <p class="hs-open-title"><?php echo esc_html( $card['card_title'] ?? '' ); ?></p>
                                <?php endif; ?>

                                <div class="hs-divider"></div>
                                <p class="hs-body"><?php echo wp_kses_post( $card['card_body'] ); ?></p>

                                <div class="hs-btn-wrap">
                                    <?php if ( $link ) : ?>
                                        <a href="<?php echo esc_url( $link ); ?>"<?php echo $target . $nofollow; ?> class="hs-btn">
                                            <?php echo $btn_label; ?>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </a>
                                    <?php else : ?>
                                        <button class="hs-btn" type="button">
                                            <?php echo $btn_label; ?>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <div class="hs-nav">
                    <button class="hs-arrow" id="<?php echo esc_attr( $prev_id ); ?>" aria-label="<?php esc_attr_e( 'Previous', 'hs-accordion-carousel' ); ?>" type="button">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="hs-arrow" id="<?php echo esc_attr( $next_id ); ?>" aria-label="<?php esc_attr_e( 'Next', 'hs-accordion-carousel' ); ?>" type="button">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </section>

        <script>
        /* hs-carousel: init instance <?php echo esc_js( $this->get_id() ); ?> */
        (function(){
            function init(){
                if(typeof hsInitCarousel==='function'){
                    hsInitCarousel(
                        '<?php echo esc_js( $wrap_id ); ?>',
                        '<?php echo esc_js( $track_id ); ?>',
                        '<?php echo esc_js( $prev_id ); ?>',
                        '<?php echo esc_js( $next_id ); ?>'
                    );
                }else{setTimeout(init,50);}
            }
            document.readyState==='loading'
                ? document.addEventListener('DOMContentLoaded',init)
                : init();
        })();
        </script>
        <?php
    }

    /* ══════════════════════════════════════════════════════
       LIVE EDITOR PREVIEW TEMPLATE  (JS — Elementor editor)
    ══════════════════════════════════════════════════════ */
    protected function content_template() {
        ?>
        <#
        var cards       = settings.cards || [];
        var globalLabel = settings.btn_label || 'Discover more';
        var stripClass  = settings.closed_strip_type === 'text' ? ' hs-strip-text' : '';
        #>
        <section class="hs-section{{ stripClass }}">
            <div class="hs-inner">
                <div class="hs-track-wrap">
                    <div class="hs-track">
                        <# _.each(cards, function(card, index) {
                            var btnLabel   = card.card_btn_label ? card.card_btn_label : globalLabel;
                            var isImage    = card.header_type === 'image';
                            var imgUrl     = isImage && card.card_image && card.card_image.url ? card.card_image.url : '';
                            var imgAlt     = card.card_image_alt || '';
                            var stripLabel = isImage ? (card.card_strip_title || '') : (card.card_title || '');
                        #>
                        <div class="hs-item <# if(index===0){#>hs-closed<#}#><# if(isImage){#> hs-item--image-header<#}#>" data-index="{{ index }}">
                            <div class="hs-num-badge">{{ index+1 }}</div>
                            <div class="hs-card-header">
                                <# if(isImage && imgUrl){ #>
                                    <img class="hs-card-header-img" src="{{{ imgUrl }}}" alt="{{{ imgAlt }}}">
                                <# } else { #>
                                    <span class="hs-card-title">{{{ stripLabel }}}</span>
                                <# } #>
                                <div class="hs-card-badge">{{ index+1 }}</div>
                            </div>
                            <div class="hs-closed-content">
                                <span class="hs-closed-title">{{{ stripLabel }}}</span>
                                <# if(isImage && imgUrl){ #>
                                    <img class="hs-closed-img" src="{{{ imgUrl }}}" alt="{{{ imgAlt }}}">
                                <# } #>
                            </div>
                            <div class="hs-open-content">
                                <# if(isImage && imgUrl){ #>
                                    <img class="hs-header-img" src="{{{ imgUrl }}}" alt="{{{ imgAlt }}}">
                                <# } else { #>
                                    <p class="hs-open-title">{{{ card.card_title }}}</p>
                                <# } #>
                                <div class="hs-divider"></div>
                                <p class="hs-body">{{{ card.card_body }}}</p>
                                <div class="hs-btn-wrap">
                                    <button class="hs-btn" type="button">
                                        {{{ btnLabel }}}
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <# }); #>
                    </div>
                </div>
                <div class="hs-nav">
                    <button class="hs-arrow" type="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></button>
                    <button class="hs-arrow" type="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg></button>
                </div>
            </div>
        </section>
        <?php
    }
}
