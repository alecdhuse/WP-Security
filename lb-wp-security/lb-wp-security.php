<?php
/*
Plugin Name: Little Bonsai WP Security

*/

defined('ABSPATH') or die('Direct access is not allowed.');

function report_ip($api_key, $ip, $user_agent) {
  $url = 'https://littlebonsai.co/api/v0.3/add_blacklist_ip.php';
  $data = array('ip' => $ip, 'user_agent' => $user_agent, 'comment' => 'Failed WordPress Login', 'tags' => 'malicious-login', 'ref_url' => '');

  $options = array(
      'http' => array(
          'method'  => 'POST',
          'content' => http_build_query($data),
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                       "Accept: application/json\r\n" .
                       "Auth: $api_key\r\n"
      )
  );

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { /* Handle error */ }

  var_dump($result);
}

function login_failed($username) {
  $ip = $_SERVER['REMOTE_ADDR'];
  $user_agent = $_SERVER['HTTP_USER_AGENT'];

  $myfile = fopen("api.key", "r") or die("Error reading api key.");
  $api_key = trim(fread($myfile,filesize("api.key")));
  fclose($myfile);

  report_ip($api_key, $ip, $user_agent);
}

function init() {
  add_action('wp_login_failed', 'login_failed');
}

init();

?>
