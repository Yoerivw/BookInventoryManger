<?php

$pdo = new PDO('mysql:host=localhost;dbname=ijdb;
charset=utf8', 'yoeri', 'Ondenkbaar1406');
$pdo->setAttribute(PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION);