<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://www.paypal.com;");
echo "Test CSP header";