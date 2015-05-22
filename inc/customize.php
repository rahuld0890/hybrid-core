<?php
/**
 * Loads customizer-related files (see `/inc/customize`) and sets up customizer functionality.
 *
 * @package    HybridCore
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Load custom control classes.
add_action( 'customize_register', 'hybrid_load_customize_classes', 0 );

# Register customizer panels, sections, settings, and/or controls.
add_action( 'customize_register', 'hybrid_customize_register' );

# Register customize controls scripts/styles.
add_action( 'customize_controls_enqueue_scripts', 'hybrid_customize_controls_register_scripts', 5 );
add_action( 'customize_controls_enqueue_scripts', 'hybrid_customize_controls_register_styles',  5 );

# Register/Enqueue customize preview scripts/styles.
add_action( 'customize_preview_init', 'hybrid_customize_preview_register_scripts', 5 );
add_action( 'customize_preview_init', 'hybrid_customize_preview_enqueue_scripts'     );

/**
 * Loads framework-specific customize classes.  These are classes that extend the core `WP_Customize_*`
 * classes to provide theme authors access to functionality that core doesn't handle out of the box.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_load_customize_classes() {

	// Load customize setting classes.
	require_once( HYBRID_CUSTOMIZE . 'setting-image-data.php' );

	// Load customize control classes.
	require_once( HYBRID_CUSTOMIZE . 'control-dropdown-terms.php'   );
	require_once( HYBRID_CUSTOMIZE . 'control-palette.php'          );
	require_once( HYBRID_CUSTOMIZE . 'control-radio-image.php'      );
	require_once( HYBRID_CUSTOMIZE . 'control-select-group.php'     );
	require_once( HYBRID_CUSTOMIZE . 'control-select-multiple.php'  );
	require_once( HYBRID_CUSTOMIZE . 'control-background-image.php' );

	require_if_theme_supports( 'theme-layouts', HYBRID_CUSTOMIZE . 'control-theme-layout.php' );
}

/**
 * Register customizer panels, sections, controls, and/or settings.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_customize_register( $wp_customize ) {

	// Always add the layout section so that theme devs can utilize it.
	$wp_customize->add_section(
		'layout',
		array(
			'title'      => esc_html__( 'Layout', 'hybrid-core' ),
			'priority'   => 30,
		)
	);

	// Check if the theme supports the theme layouts customize feature.
	if ( current_theme_supports( 'theme-layouts', 'customize' ) ) {

		// Add the layout setting.
		$wp_customize->add_setting(
			'theme_layout',
			array(
				'default'           => hybrid_get_theme_layout(),
				'sanitize_callback' => 'sanitize_html_class',
				'transport'         => 'postMessage'
			)
		);

		// Add the layout control.
		$wp_customize->add_control(
			new Hybrid_Customize_Control_Theme_Layout(
				$wp_customize,
				'theme_layout',
				array(
					'label'    => esc_html__( 'Global Layout', 'hybrid-core' ),
					'section'  => 'layout',
				)
			)
		);
	}
}

/**
 * Register customizer controls scripts.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_customize_controls_register_scripts() {
	wp_register_script( 'hybrid-customize-controls', esc_url( HYBRID_JS . 'customize-controls' . hybrid_get_min_suffix() . '.js' ), array( 'jquery' ), '20150507', true );
}

/**
 * Register customizer controls styles.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_customize_controls_register_styles() {
	wp_register_style( 'hybrid-customize-controls', esc_url( HYBRID_CSS . 'customize-controls' . hybrid_get_min_suffix() . '.css' ) );
}

/**
 * Register customizer preview scripts.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_customize_preview_register_scripts() {
	wp_register_script( 'hybrid-customize-preview', esc_url( HYBRID_JS . 'customize-preview' . hybrid_get_min_suffix() . '.js' ), array( 'jquery' ), '20150507', true );
}

/**
 * Register customizer preview scripts.
 *
 * @since  3.0.0
 * @access public
 * @return void
 */
function hybrid_customize_preview_enqueue_scripts() {

	if ( current_theme_supports( 'theme-layouts' ) )
		wp_enqueue_script( 'hybrid-customize-preview' );
}
