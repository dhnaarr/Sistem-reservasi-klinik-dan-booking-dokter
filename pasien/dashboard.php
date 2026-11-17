<?php
require "../includes/auth.php";
require_role('pasien');
?>
<h2>Dashboard Pasien</h2>
<p>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></p>
<a href="../auth/logout.php">Logout</a>
