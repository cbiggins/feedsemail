<?php

require_once('magpierss/rss_fetch.inc');
require_once('PHPMailer/class.phpmailer.php');

/******************************/
/**********  CONFIG  **********/
/******************************/

// Who are we sending from?
$email_from_name = 'First Last';
$email_from_addr = 'first.last@example.com';

// Who are we sending to?
$email_to_name = 'First Last';
$email_to_addr = 'first.last@example.com';

// Our email subject.
$email_subject = "Your daily feeds email.";

// All the feeds we want to fetch.
$feeds = array(
  "D8 Accessibility Issues" => "http://drupal.org/project/issues/search/drupal/rss?status[0]=Open&version[0]=8.x&issue_tags=accessibility",
);

/******************************/
/********  END CONFIG  ********/
/******************************/

// Create an array of content.
$email_html = '';
foreach ($feeds as $title => $feed) {
  $email_html .= '<div class="feed_container">';
  $email_html .= '<h2>' . $title . '</h2>';

  $rss = fetch_rss($feed);

  foreach ($rss->items as $k => $item) {
    $email_html .= '<div class="feed_item"><a href="' . $item['link'] . '">' . $item['title'] . '</a></div>';
  }
  $email_html .= '</div>';
}

// Build our email.
$mail = new PHPMailer();
$body = $email_html;
$mail->AddReplyTo($email_from_addr, $email_from_name);
$mail->SetFrom($email_from_addr, $email_from_name);
$mail->AddAddress($email_to_addr, $email_to_name);
$mail->Subject = $email_subject;

$mail->MsgHTML($body);

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

