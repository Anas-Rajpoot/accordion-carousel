<?php
/**
 * Plugin Name: HS Accordion Carousel
 * Description: Responsive accordion carousel + Vertical Team Carousel Pro — Elementor widgets with full controls. Shortcode fallback: [hs_carousel].
 * Version: 2.6
 * Author: HS Agency
 * Text Domain: hs-accordion-carousel
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'HS_CAROUSEL_VERSION', '2.6' );
define( 'HS_CAROUSEL_URL',     plugin_dir_url( __FILE__ ) );
define( 'HS_CAROUSEL_PATH',    plugin_dir_path( __FILE__ ) );

/* ══════════════════════════════════════
   ASSETS
══════════════════════════════════════ */
function hs_carousel_enqueue_assets() {
    /* ── Accordion Carousel ── */
    wp_register_style(
        'hs-carousel-css',
        HS_CAROUSEL_URL . 'assets/hs-carousel.css',
        [],
        HS_CAROUSEL_VERSION
    );
    wp_register_script(
        'hs-carousel-js',
        HS_CAROUSEL_URL . 'assets/hs-carousel.js',
        [],
        HS_CAROUSEL_VERSION,
        true
    );

    /* ── Team Carousel ── */
    wp_register_style(
        'hs-team-carousel-css',
        HS_CAROUSEL_URL . 'assets/hs-team-carousel.css',
        [],
        HS_CAROUSEL_VERSION
    );
    wp_register_script(
        'hs-team-carousel-js',
        HS_CAROUSEL_URL . 'assets/hs-team-carousel.js',
        [],
        HS_CAROUSEL_VERSION,
        true
    );

    /* ── Poppins (default font) ── */
    wp_enqueue_style(
        'hs-carousel-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap',
        [],
        null
    );
}
add_action( 'wp_enqueue_scripts', 'hs_carousel_enqueue_assets' );

/* Also register inside Elementor editor */
add_action( 'elementor/editor/after_enqueue_scripts', 'hs_carousel_enqueue_assets' );

/* ══════════════════════════════════════
   ELEMENTOR WIDGET
══════════════════════════════════════ */
function hs_carousel_register_widgets( $widgets_manager ) {
    if ( ! did_action( 'elementor/loaded' ) ) return;

    /* Accordion Carousel */
    require_once HS_CAROUSEL_PATH . 'includes/class-hs-carousel-widget.php';
    $widgets_manager->register( new HS_Carousel_Widget() );

    /* Vertical Team Carousel Pro */
    require_once HS_CAROUSEL_PATH . 'includes/class-hs-team-carousel-widget.php';
    $widgets_manager->register( new HS_Team_Carousel_Widget() );
}
add_action( 'elementor/widgets/register', 'hs_carousel_register_widgets' );

/* ══════════════════════════════════════
   SHORTCODE FALLBACK
   (for pages not using Elementor)
══════════════════════════════════════ */
$hs_carousel_instance_counter = 0;

function hs_card_shortcode( $atts, $content = '' ) {
    $atts = shortcode_atts( [ 'title' => '', 'link' => '#' ], $atts, 'hs_card' );
    return serialize( [
        'title'   => esc_attr( $atts['title'] ),
        'link'    => esc_url( $atts['link'] ),
        'content' => wp_kses_post( trim( $content ) ),
    ] );
}
add_shortcode( 'hs_card', 'hs_card_shortcode' );

function hs_carousel_shortcode( $atts, $content = '' ) {
    global $hs_carousel_instance_counter;
    $hs_carousel_instance_counter++;

    $id      = 'sc' . $hs_carousel_instance_counter;
    $wrap_id  = 'hs-wrap-'  . $id;
    $track_id = 'hs-track-' . $id;
    $prev_id  = 'hs-prev-'  . $id;
    $next_id  = 'hs-next-'  . $id;

    $atts = shortcode_atts( [
        'bg_color'    => '#0098ED',
        'card_color'  => '#1DCBF1',
        'badge_bg'    => '#ffffff',
        'badge_text'  => '#1DCBF1',
        'title_color' => '#09202e',
        'body_color'  => 'rgba(255,255,255,0.94)',
        'btn_bg'      => '#ffffff',
        'btn_color'   => '#1DCBF1',
        'arrow_color' => '#00E2A1',
        'card_height' => '400',
        'card_width'  => '403',
        'btn_label'   => 'Discover more',
    ], $atts, 'hs_carousel' );

    $vars = sprintf(
        '--hs-bg:%s;--hs-card:%s;--hs-badge-bg:%s;--hs-badge-text:%s;--hs-title:%s;--hs-body:%s;--hs-btn-bg:%s;--hs-btn-text:%s;--hs-arrow:%s;--hs-card-height:%spx;--hs-card-width:%spx;',
        esc_attr( $atts['bg_color'] ),
        esc_attr( $atts['card_color'] ),
        esc_attr( $atts['badge_bg'] ),
        esc_attr( $atts['badge_text'] ),
        esc_attr( $atts['title_color'] ),
        esc_attr( $atts['body_color'] ),
        esc_attr( $atts['btn_bg'] ),
        esc_attr( $atts['btn_color'] ),
        esc_attr( $atts['arrow_color'] ),
        intval( $atts['card_height'] ),
        intval( $atts['card_width'] )
    );

    $cards = [];
    if ( ! empty( $content ) ) {
        preg_match_all( '/a:3:\{.*?\}(?=a:3:|$)/s', do_shortcode( $content ), $m );
        foreach ( $m[0] as $raw ) {
            $c = @unserialize( $raw );
            if ( is_array( $c ) ) $cards[] = $c;
        }
    }

    $btn_label = esc_html( $atts['btn_label'] );

    ob_start();
    ?>
    <section class="hs-section" style="<?php echo $vars; ?>">
        <div class="hs-inner">
            <div class="hs-track-wrap" id="<?php echo esc_attr( $wrap_id ); ?>">
                <div class="hs-track" id="<?php echo esc_attr( $track_id ); ?>">
                    <?php foreach ( $cards as $i => $card ) :
                        $num   = $i + 1;
                        $class = 'hs-item' . ( $i === 0 ? ' hs-closed' : '' );
                    ?>
                    <div class="<?php echo $class; ?>" data-index="<?php echo $i; ?>">
                        <div class="hs-num-badge"><?php echo $num; ?></div>
                        <div class="hs-card-header">
                            <span class="hs-card-title"><?php echo esc_html( $card['title'] ); ?></span>
                            <div class="hs-card-badge"><?php echo $num; ?></div>
                        </div>
                        <div class="hs-closed-content">
                            <span class="hs-closed-title"><?php echo esc_html( $card['title'] ); ?></span>
                        </div>
                        <div class="hs-open-content">
                            <p class="hs-open-title"><?php echo esc_html( $card['title'] ); ?></p>
                            <div class="hs-divider"></div>
                            <p class="hs-body"><?php echo wp_kses_post( $card['content'] ); ?></p>
                            <div class="hs-btn-wrap">
                                <?php if ( ! empty( $card['link'] ) && $card['link'] !== '#' ) : ?>
                                    <a href="<?php echo esc_url( $card['link'] ); ?>" class="hs-btn"><?php echo $btn_label; ?> <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
                                <?php else : ?>
                                    <button class="hs-btn" type="button"><?php echo $btn_label; ?> <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="hs-nav">
                <button class="hs-arrow" id="<?php echo esc_attr( $prev_id ); ?>" aria-label="Previous" type="button">
                    <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="hs-arrow" id="<?php echo esc_attr( $next_id ); ?>" aria-label="Next" type="button">
                    <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </section>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        hsInitCarousel('<?php echo $wrap_id; ?>','<?php echo $track_id; ?>','<?php echo $prev_id; ?>','<?php echo $next_id; ?>');
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'hs_carousel', 'hs_carousel_shortcode' );
