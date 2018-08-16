<?php
/*
Plugin Name: Little Bonsai WP Security

*/

defined('ABSPATH') or die('Direct access is not allowed.');

function login_failed($username) {
  $ip = $_SERVER['REMOTE_ADDR'];
  $user_agent = $_SERVER['HTTP_USER_AGENT'];

}

add_action('wp_login_failed', 'login_failed');

?>
