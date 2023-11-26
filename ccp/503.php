<?php
//Use PHP's http_response_code function to send a 503
//Service Temporarily Unavailable status code to the client.
http_response_code(503);
//Seconds until the client should retry.
$retryAfterSeconds = 240;
//Retry-After header.
header('Retry-After: ' . $retryAfterSeconds);
//Custom message.
echo '<h1>503 Service Temporarily Unavailable</h1>';
echo '<p>Our site is currently under maintenance.</p>';
//Exit the script.
exit;