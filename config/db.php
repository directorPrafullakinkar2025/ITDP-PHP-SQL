<?php
$DB_HOST = getenv('ITDP_DB_HOST') ?: '127.0.0.1';
$DB_USER = getenv('ITDP_DB_USER') ?: 'root';
$DB_PASS = getenv('ITDP_DB_PASS') ?: '';
$DB_NAME = getenv('ITDP_DB_NAME') ?: 'itdp';
$conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if(!$conn) die('Database connection failed: '.mysqli_connect_error());
mysqli_set_charset($conn,'utf8mb4');
