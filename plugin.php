<?php
/*
Plugin Name: WR Elementor Widgets
Description: Custom Elementor widget set for WR E-Commerce Theme.
Version: 1.0.0
Author: Wisdom Rain
Text Domain: wr-ew
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin paths.
define( 'WR_EW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WR_EW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load plugin loader.
require_once WR_EW_PLUGIN_DIR . 'loader.php';
