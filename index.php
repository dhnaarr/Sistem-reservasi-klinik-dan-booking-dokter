<?php

require_once __DIR__ . '/includes/fungsi.php';
redirect(sudah_login() ? halaman_awal($_SESSION['role']) : 'auth/login.php');
