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

    public function get_style_depends()  { return [ 'hs-carousel' ]; }
    public function get_script_depends() { return [ 'hs-carousel' ]; }

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

        $this->add_control( 'closed_title_color', [
            'label'     => __( 'Closed Strip Title Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#09202e',
            'selectors' => [ '{{WRAPPER}} .hs-closed-title' => 'color: {{VALUE}};' ],
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
            'label'     => __( 'Arrow Button Color', 'hs-accordion-carousel' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#00E2A1',
            'selectors' => [ '{{WRAPPER}} .hs-section' => '--hs-arrow: {{VALUE}};' ],
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

        /* Enqueue selected font from Google Fonts (only when not Poppins — Poppins is already loaded) */
        $font = ! empty( $s['font_family'] ) ? $s['font_family'] : 'Poppins';
        if ( 'Poppins' !== $font ) {
            $font_url = 'https://fonts.googleapis.com/css2?family=' . urlencode( $font ) . ':wght@400;600;700&display=swap';
            wp_enqueue_style( 'hs-carousel-font-' . sanitize_title( $font ), $font_url, [], null );
        }
        ?>
        <section class="hs-section">
            <div class="hs-inner">
                <div class="hs-track-wrap" id="<?php echo esc_attr( $wrap_id ); ?>">
                    <div class="hs-track" id="<?php echo esc_attr( $track_id ); ?>">

                        <?php foreach ( $cards as $index => $card ) :
                            $num         = $index + 1;
                            $card_class  = 'hs-item' . ( $index === 0 ? ' hs-closed' : '' );
                            $link        = ! empty( $card['card_link']['url'] ) ? $card['card_link']['url'] : '';
                            $target      = ! empty( $card['card_link']['is_external'] ) ? ' target="_blank"' : '';
                            $nofollow    = ! empty( $card['card_link']['nofollow'] ) ? ' rel="nofollow"' : '';
                            $btn_label   = ! empty( $card['card_btn_label'] ) ? esc_html( $card['card_btn_label'] ) : esc_html( $global_label );
                            $is_image    = ( isset( $card['header_type'] ) && $card['header_type'] === 'image' );
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

                            <!-- Desktop closed strip: vertical rotated text -->
                            <div class="hs-closed-content">
                                <span class="hs-closed-title"><?php echo $strip_label; ?></span>
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
        #>
        <section class="hs-section">
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
                        <div class="hs-item <# if(index===0){#>hs-closed<#}#>" data-index="{{ index }}">
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
