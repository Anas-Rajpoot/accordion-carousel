<?php
/**
 * Elementor Widget: Vertical Team Carousel Pro
 * Part of the HS Accordion Carousel plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

class HS_Team_Carousel_Widget extends Widget_Base {

    public function get_name()  { return 'hs-team-carousel'; }
    public function get_title() { return esc_html__( 'Vertical Team Carousel Pro', 'hs-carousel' ); }
    public function get_icon()  { return 'eicon-person'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_keywords()   { return [ 'team', 'carousel', 'vertical', 'scroll', 'members', 'popup' ]; }

    public function get_script_depends()  { return [ 'hs-team-carousel-js' ]; }
    public function get_style_depends()   { return [ 'hs-team-carousel-css' ]; }

    /* ──────────────────────────────────────────────────────
       CONTROLS
    ────────────────────────────────────────────────────── */
    protected function register_controls() {

        /* ══ CONTENT TAB ══ */

        /* ─ Team Members Repeater ─ */
        $this->start_controls_section( 'section_members', [
            'label' => esc_html__( 'Team Members', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'member_image', [
            'label'   => esc_html__( 'Photo', 'hs-carousel' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            'dynamic' => [ 'active' => true ],
        ] );

        $repeater->add_control( 'member_name', [
            'label'       => esc_html__( 'Name', 'hs-carousel' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__( 'Team Member', 'hs-carousel' ),
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $repeater->add_control( 'member_position', [
            'label'       => esc_html__( 'Position / Company', 'hs-carousel' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__( 'Position · Company', 'hs-carousel' ),
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ] );

        $repeater->add_control( 'member_description', [
            'label'   => esc_html__( 'Description', 'hs-carousel' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__( 'A short description about this team member and their experience.', 'hs-carousel' ),
            'rows'    => 4,
            'dynamic' => [ 'active' => true ],
        ] );

        $this->add_control( 'members', [
            'label'       => esc_html__( 'Members', 'hs-carousel' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'member_name' => 'Gemma Fornas',  'member_position' => 'CEO · Mutual Medica'        ],
                [ 'member_name' => 'Alex Rivera',   'member_position' => 'CTO · TechNova'             ],
                [ 'member_name' => 'Lucia Santos',  'member_position' => 'Design Lead · Pixel Studio'  ],
                [ 'member_name' => 'Marco Fabbri',  'member_position' => 'Head of Sales · Nexus'      ],
                [ 'member_name' => 'Sara Kim',      'member_position' => 'Marketing · BrandBox'        ],
                [ 'member_name' => 'James Okafor',  'member_position' => 'Engineer · DeepCode'         ],
            ],
            'title_field' => '{{{ member_name }}}',
        ] );

        $this->end_controls_section();

        /* ─ Layout ─ */
        $this->start_controls_section( 'section_layout', [
            'label' => esc_html__( 'Layout', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'columns', [
            'label'   => esc_html__( 'Columns', 'hs-carousel' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '2',
            'options' => [ '1' => '1 Column', '2' => '2 Columns' ],
        ] );

        $this->add_responsive_control( 'section_height', [
            'label'      => esc_html__( 'Section Height', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [ 'px' => [ 'min' => 200, 'max' => 1000, 'step' => 10 ], 'vh' => [ 'min' => 20, 'max' => 100 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 520 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-columns' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'col_gap', [
            'label'      => esc_html__( 'Column Gap', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 14 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-columns' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'section_padding', [
            'label'      => esc_html__( 'Section Padding', 'hs-carousel' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'default'    => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => true ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        /* ─ Card Image ─ */
        $this->start_controls_section( 'section_card_image', [
            'label' => esc_html__( 'Card Image', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'card_width', [
            'label'      => esc_html__( 'Column Width (desktop)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 160, 'max' => 600, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 340 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-col-w: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'card_height', [
            'label'      => esc_html__( 'Card Height', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 80, 'max' => 600, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 340 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-card' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'card_gap', [
            'label'      => esc_html__( 'Gap Between Cards', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 12 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-track' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'img_object_fit', [
            'label'     => esc_html__( 'Image Fit', 'hs-carousel' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'cover',
            'options'   => [
                'cover'   => 'Cover',
                'contain' => 'Contain',
                'fill'    => 'Fill',
            ],
            'selectors' => [ '{{WRAPPER}} .hs-team-card img' => 'object-fit: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'img_position', [
            'label'     => esc_html__( 'Image Position', 'hs-carousel' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'center center',
            'options'   => [
                'center center' => 'Center',
                'top center'    => 'Top',
                'bottom center' => 'Bottom',
                'left center'   => 'Left',
                'right center'  => 'Right',
            ],
            'selectors' => [ '{{WRAPPER}} .hs-team-card img' => 'object-position: {{VALUE}};' ],
        ] );

        $this->add_control( 'border_radius', [
            'label'      => esc_html__( 'Border Radius', 'hs-carousel' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16', 'unit' => 'px', 'isLinked' => true ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .hs-team-card',
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .hs-team-card',
        ] );

        $this->end_controls_section();

        /* ─ Animation ─ */
        $this->start_controls_section( 'section_animation', [
            'label' => esc_html__( 'Animation', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'speed_left', [
            'label'   => esc_html__( 'Left Column Speed', 'hs-carousel' ),
            'type'    => Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0.1, 'max' => 6, 'step' => 0.1 ] ],
            'default' => [ 'unit' => 'px', 'size' => 0.6 ],
        ] );

        $this->add_control( 'speed_right', [
            'label'     => esc_html__( 'Right Column Speed', 'hs-carousel' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0.1, 'max' => 6, 'step' => 0.1 ] ],
            'default'   => [ 'unit' => 'px', 'size' => 0.6 ],
            'condition' => [ 'columns' => '2' ],
        ] );

        $this->add_control( 'pause_on_hover', [
            'label'        => esc_html__( 'Pause on Hover', 'hs-carousel' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'hover_animation', [
            'label'   => esc_html__( 'Card Hover Effect', 'hs-carousel' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'scale',
            'options' => [
                'scale' => 'Scale Card',
                'zoom'  => 'Zoom Image',
                'fade'  => 'Brightness',
                'none'  => 'None',
            ],
        ] );

        $this->end_controls_section();

        /* ─ Popup ─ */
        $this->start_controls_section( 'section_popup', [
            'label' => esc_html__( 'Popup', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'enable_popup', [
            'label'        => esc_html__( 'Enable Popup', 'hs-carousel' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'popup_animation', [
            'label'     => esc_html__( 'Open Animation', 'hs-carousel' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'zoom',
            'options'   => [ 'zoom' => 'Zoom + Fade', 'slide' => 'Slide Up' ],
            'condition' => [ 'enable_popup' => 'yes' ],
        ] );

        /* ── Desktop Popup Size ── */
        $this->add_control( 'popup_size_heading', [
            'label'     => esc_html__( 'Desktop Size', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'enable_popup' => 'yes' ],
        ] );

        $this->add_control( 'popup_width', [
            'label'      => esc_html__( 'Popup Width', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vw' ],
            'range'      => [ 'px' => [ 'min' => 360, 'max' => 1400, 'step' => 10 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 1025 ],
            'condition'  => [ 'enable_popup' => 'yes' ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => 'width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'popup_height', [
            'label'      => esc_html__( 'Popup Height', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [ 'px' => [ 'min' => 200, 'max' => 900, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 524 ],
            'condition'  => [ 'enable_popup' => 'yes' ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        /* Popup image controls */
        $this->add_control( 'popup_img_heading', [
            'label'     => esc_html__( 'Image Side (Desktop)', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [ 'enable_popup' => 'yes' ],
        ] );

        $this->add_control( 'popup_img_width', [
            'label'      => esc_html__( 'Image Panel Width', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 600, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 340 ],
            'condition'  => [ 'enable_popup' => 'yes' ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-img' => 'flex: 0 0 {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'popup_img_fit', [
            'label'     => esc_html__( 'Image Fit', 'hs-carousel' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'cover',
            'options'   => [ 'cover' => 'Cover', 'contain' => 'Contain', 'fill' => 'Fill' ],
            'condition' => [ 'enable_popup' => 'yes' ],
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-img img' => 'object-fit: {{VALUE}};' ],
        ] );

        $this->add_control( 'popup_img_position', [
            'label'     => esc_html__( 'Image Position', 'hs-carousel' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'center center',
            'options'   => [
                'center center' => 'Center',
                'top center'    => 'Top',
                'bottom center' => 'Bottom',
                'left center'   => 'Left',
                'right center'  => 'Right',
            ],
            'condition' => [ 'enable_popup' => 'yes' ],
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-img img' => 'object-position: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ─ Mobile ─ */
        $this->start_controls_section( 'section_mobile', [
            'label' => esc_html__( '📱 Mobile Layout', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'mobile_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => '<small style="color:#aaa">On mobile (≤768 px) the two vertical columns switch to <strong>two horizontal rows</strong> — row 1 scrolls left, row 2 scrolls right. Controls below fine-tune sizes for that view.</small>',
            'content_classes' => 'elementor-descriptor',
        ] );

        $this->add_control( 'mobile_section_height', [
            'label'      => esc_html__( 'Row Height (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 600, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 260 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-mobile-height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_col_gap', [
            'label'      => esc_html__( 'Row Gap (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 10 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-mobile-col-gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_card_width', [
            'label'      => esc_html__( 'Card Width (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 80, 'max' => 400, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 160 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-mobile-card-w: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_card_height', [
            'label'      => esc_html__( 'Card Height (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 80, 'max' => 500, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 240 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-mobile-card-h: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_card_gap', [
            'label'      => esc_html__( 'Gap Between Cards (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 12 ],
            'selectors'  => [ '{{WRAPPER}} .hs-team-section' => '--hst-mobile-card-gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_popup_heading', [
            'label'     => esc_html__( 'Popup (mobile)', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'mobile_popup_width', [
            'label'      => esc_html__( 'Popup Width (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vw' ],
            'range'      => [ 'px' => [ 'min' => 240, 'max' => 600, 'step' => 4 ], 'vw' => [ 'min' => 60, 'max' => 100 ] ],
            'default'    => [ 'unit' => 'vw', 'size' => 92 ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => '--hst-mobile-popup-width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_popup_height', [
            'label'       => esc_html__( 'Popup Height (mobile)', 'hs-carousel' ),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => [ 'px', 'vh' ],
            'range'       => [ 'px' => [ 'min' => 200, 'max' => 900, 'step' => 4 ] ],
            'default'     => [ 'unit' => 'px', 'size' => 480 ],
            'description' => esc_html__( 'Set to auto or leave at 0 for auto height on mobile.', 'hs-carousel' ),
            'selectors'   => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => '--hst-mobile-popup-h: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_popup_img_height', [
            'label'      => esc_html__( 'Popup Image Height (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 80, 'max' => 400, 'step' => 4 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 200 ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => '--hst-mobile-popup-img-h: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_popup_name_size', [
            'label'      => esc_html__( 'Popup Name Font Size (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 14, 'max' => 40 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 20 ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => '--hst-mobile-name-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'mobile_popup_desc_size', [
            'label'      => esc_html__( 'Popup Description Font Size (mobile)', 'hs-carousel' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 24 ] ],
            'default'    => [ 'unit' => 'px', 'size' => 13 ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => '--hst-mobile-desc-size: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();

        /* ══ STYLE TAB ══ */

        /* ─ Section / Background ─ */
        $this->start_controls_section( 'style_section', [
            'label' => esc_html__( 'Section', 'hs-carousel' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Background::get_type(), [
            'name'     => 'section_bg',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .hs-team-section',
        ] );

        $this->add_control( 'font_family', [
            'label'     => esc_html__( 'Font Family', 'hs-carousel' ),
            'type'      => Controls_Manager::FONT,
            'default'   => 'Poppins',
            'selectors' => [ '{{WRAPPER}} .hs-team-section' => '--hst-font: "{{VALUE}}", sans-serif; font-family: "{{VALUE}}", sans-serif;' ],
        ] );

        $this->end_controls_section();

        /* ─ Popup Text Style ─ */
        $this->start_controls_section( 'style_popup', [
            'label'     => esc_html__( 'Popup Text', 'hs-carousel' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'enable_popup' => 'yes' ],
        ] );

        $this->add_control( 'overlay_color', [
            'label'     => esc_html__( 'Overlay Color', 'hs-carousel' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.78)',
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"]' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'popup_bg', [
            'label'     => esc_html__( 'Popup Background', 'hs-carousel' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(14,18,28,0.95)',
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'popup_border_radius', [
            'label'      => esc_html__( 'Popup Border Radius', 'hs-carousel' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px', 'isLinked' => true ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;' ],
        ] );

        /* Name */
        $this->add_control( 'popup_name_heading', [
            'label'     => esc_html__( 'Name', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'popup_name_color', [
            'label'     => esc_html__( 'Name Color', 'hs-carousel' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-name' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'popup_name_typo',
            'selector' => '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-name',
        ] );

        /* Position */
        $this->add_control( 'popup_pos_heading', [
            'label'     => esc_html__( 'Position / Company', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'popup_pos_color', [
            'label'     => esc_html__( 'Position Color', 'hs-carousel' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#00c8b4',
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-position' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'popup_pos_typo',
            'selector' => '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-position',
        ] );

        /* Description */
        $this->add_control( 'popup_desc_heading', [
            'label'     => esc_html__( 'Description', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'popup_desc_color', [
            'label'     => esc_html__( 'Description Color', 'hs-carousel' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.72)',
            'selectors' => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-desc' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'popup_desc_typo',
            'selector' => '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-desc',
        ] );

        /* Padding inside popup text area */
        $this->add_control( 'popup_text_heading2', [
            'label'     => esc_html__( 'Text Area', 'hs-carousel' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_responsive_control( 'popup_text_padding', [
            'label'      => esc_html__( 'Text Padding', 'hs-carousel' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [ 'top' => '40', 'right' => '36', 'bottom' => '40', 'left' => '36', 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [ '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'popup_shadow',
            'selector' => '.hs-tc-overlay[data-widget="{{ID}}"] .hs-tc-modal',
        ] );

        $this->end_controls_section();
    }

    /* ──────────────────────────────────────────────────────
       RENDER
    ────────────────────────────────────────────────────── */
    protected function render() {
        $s       = $this->get_settings_for_display();
        $wid     = $this->get_id();
        $uid     = 'hstc-' . $wid;
        $col1Id  = $uid . '-c1';
        $col2Id  = $uid . '-c2';
        $members = $s['members'] ?? [];

        if ( empty( $members ) ) return;

        $mid    = (int) ceil( count( $members ) / 2 );
        $col1   = array_slice( $members, 0, $mid );
        $col2   = array_slice( $members, $mid );
        $twoCol = $s['columns'] === '2' && ! empty( $col2 );
        ?>

        <div class="hs-team-section"
             id="<?php echo esc_attr( $uid ); ?>"
             data-hover="<?php echo esc_attr( $s['hover_animation'] ?? 'scale' ); ?>">

            <div class="hs-team-columns">

                <!-- Column 1 (scrolls UP) -->
                <div class="hs-team-col" id="<?php echo esc_attr( $col1Id ); ?>">
                    <div class="hs-team-track">
                        <?php foreach ( $col1 as $member ) :
                            $img_url = $member['member_image']['url'] ?? '';
                            $img_alt = $member['member_name'] ?? '';
                            ?>
                            <div class="hs-team-card"
                                 data-name="<?php echo esc_attr( $member['member_name']        ?? '' ); ?>"
                                 data-position="<?php echo esc_attr( $member['member_position']   ?? '' ); ?>"
                                 data-desc="<?php echo esc_attr( $member['member_description'] ?? '' ); ?>"
                                 data-img="<?php echo esc_url( $img_url ); ?>"
                                 tabindex="0" role="button"
                                 aria-label="<?php echo esc_attr( $img_alt ); ?>">
                                <?php if ( $img_url ) : ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>"
                                         alt="<?php echo esc_attr( $img_alt ); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Column 2 (scrolls DOWN) -->
                <?php if ( $twoCol ) : ?>
                <div class="hs-team-col" id="<?php echo esc_attr( $col2Id ); ?>">
                    <div class="hs-team-track">
                        <?php foreach ( $col2 as $member ) :
                            $img_url = $member['member_image']['url'] ?? '';
                            $img_alt = $member['member_name'] ?? '';
                            ?>
                            <div class="hs-team-card"
                                 data-name="<?php echo esc_attr( $member['member_name']        ?? '' ); ?>"
                                 data-position="<?php echo esc_attr( $member['member_position']   ?? '' ); ?>"
                                 data-desc="<?php echo esc_attr( $member['member_description'] ?? '' ); ?>"
                                 data-img="<?php echo esc_url( $img_url ); ?>"
                                 tabindex="0" role="button"
                                 aria-label="<?php echo esc_attr( $img_alt ); ?>">
                                <?php if ( $img_url ) : ?>
                                    <img src="<?php echo esc_url( $img_url ); ?>"
                                         alt="<?php echo esc_attr( $img_alt ); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- .hs-team-columns -->
        </div><!-- .hs-team-section -->

        <script>
        (function(){
            function init() {
                var el = document.getElementById('<?php echo esc_js( $uid ); ?>');
                if (!el || !window.hsInitTeamCarousel) return;
                hsInitTeamCarousel({
                    sectionId:    '<?php echo esc_js( $uid ); ?>',
                    col1Id:       '<?php echo esc_js( $col1Id ); ?>',
                    col2Id:       <?php echo $twoCol ? '"' . esc_js( $col2Id ) . '"' : 'null'; ?>,
                    speed1:       <?php echo (float) ( $s['speed_left']['size']  ?? 0.6 ); ?>,
                    speed2:       <?php echo (float) ( $s['speed_right']['size'] ?? 0.6 ); ?>,
                    pauseOnHover: <?php echo 'yes' === ( $s['pause_on_hover'] ?? 'yes' ) ? 'true' : 'false'; ?>,
                    popup:        <?php echo 'yes' === ( $s['enable_popup']   ?? 'yes' ) ? 'true' : 'false'; ?>,
                    popupAnim:    '<?php echo esc_js( $s['popup_animation'] ?? 'zoom' ); ?>',
                    widgetId:     '<?php echo esc_js( $wid ); ?>',
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
        </script>
        <?php
    }
}
