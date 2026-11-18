<?php
require_once __DIR__ . '/../includes/fungsi.php';

session_destroy();  
redirect('auth/login.php');
