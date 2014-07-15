<?php
/*
Plugin Name: WordPress Mobile Theme Switcher
Plugin URI: https://github.com/faishal/WordPress-Mobile-Theme-Switcher
Description: Automatic Switch theme on mobile device.
Version: 0.1
Author: faishal
Author URI: http://about.me/faishal
License: GPL2
*/

include_once 'class-mobile-theme-switcher.php';
global $mobile_theme_switcher;

$mobile_theme_switcher = new Mobile_Theme_Switcher();