<?php
session_start();
header('Content-Type: application/json');

$loggedIn = isset($_SESSION['user'])
         && $_SESSION['user']['role'] === 'admin';

echo json_encode(['loggedIn' => $loggedIn]);
