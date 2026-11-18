<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    if (isset($_POST['hapus'])) {
        $d = db_satu('SELECT * FROM dokter WHERE id_dokter = ?', [(int)$_POST['hapus']]);
        if ($d) {
            db_jalankan('DELETE FROM dokter WHERE id_dokter = ?', [$d['id_dokter']]);
            if ($d['id_user']) db_jalankan('DELETE FROM users WHERE id_user = ?', [$d['id_user']]);
            set_pesan('sukses', 'Dokter beserta jadwalnya dihapus.');
        }
        redirect('admin/dokter.php');
    }

 
    $id       = (int)$_POST['id'];                      
    $id_poli  = (int)$_POST['id_poli'];
    $nama     = trim($_POST['nama_dokter']);
    $spesialis = trim($_POST['spesialis']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $lama = $id ? db_satu('SELECT * FROM dokter WHERE id_dokter = ?', [$id]) : null;
    $id_user_lama = $lama['id_user'] ?? 0;


    $dipakai = db_satu('SELECT 1 FROM users WHERE email = ? AND id_user <> ?', [$email, $id_user_lama]);

    if (!$id_poli || $nama === '' || $spesialis === '') {
        set_pesan('error', 'Poli, nama, dan spesialis wajib diisi.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_pesan('error', 'Email login tidak valid.');
    } elseif ($dipakai) {
        set_pesan('error', 'Email sudah dipakai akun lain.');
    } elseif (!$id_user_lama && strlen($password) < 6) {
        set_pesan('error', 'Password minimal 6 karakter untuk akun baru.');
    } elseif ($password !== '' && strlen($password) < 6) {
        set_pesan('error', 'Password minimal 6 karakter.');
    } else {
        if ($id_user_lama) {
            
            db_jalankan('UPDATE users SET nama = ?, email = ? WHERE id_user = ?', [$nama, $email, $id_user_lama]);
            if ($password !== '') {
                db_jalankan('UPDATE users SET password = ? WHERE id_user = ?', [$password, $id_user_lama]);
            }
            $id_user = $id_user_lama;
        } else {
           
            db_jalankan("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'dokter')",
                [$nama, $email, $password]);
            $id_user = $pdo->lastInsertId();
        }

        if ($id) db_jalankan('UPDATE dokter SET id_user = ?, id_poli = ?, nama_dokter = ?, spesialis = ? WHERE id_dokter = ?',
                             [$id_user, $id_poli, $nama, $spesialis, $id]);
        else     db_jalankan('INSERT INTO dokter (id_user, id_poli, nama_dokter, spesialis) VALUES (?, ?, ?, ?)',
                             [$id_user, $id_poli, $nama, $spesialis]);
        set_pesan('sukses', 'Data dokter disimpan.');
    }
    redirect('admin/dokter.php');
}


$edit = isset($_GET['edit'])
    ? db_satu('SELECT d.*, u.email FROM dokter d LEFT JOIN users u ON u.id_user = d.id_user WHERE d.id_dokter = ?', [(int)$_GET['edit']])
    : null;
$semua_poli = db_semua('SELECT * FROM poli ORDER BY nama_poli');
$rows = db_semua('SELECT d.*, p.nama_poli, u.email,
        (SELECT COUNT(*) FROM jadwal_praktik j WHERE j.id_dokter = d.id_dokter) AS jml_jadwal
    FROM dokter d JOIN poli p ON p.id_poli = d.id_poli LEFT JOIN users u ON u.id_user = d.id_user
    ORDER BY p.nama_poli, d.nama_dokter');

$judul = 'Data Dokter';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Dokter</h1><p class="redup">Setiap dokter punya akun login sendiri untuk mengisi rekam medis.</p></div>
  </div>

  <form method="post" class="kartu">
    <h3><?= $edit ? 'Ubah dokter' : 'Tambah dokter' ?></h3>
    <input type="hidden" name="id" value="<?= $edit['id_dokter'] ?? 0 ?>">
    <div class="dua-kolom">
      <div><label>Poli</label>
        <select name="id_poli" required>
          <option value="">Pilih poli</option>
          <?php foreach ($semua_poli as $p): ?>
            <option value="<?= $p['id_poli'] ?>" <?= ($edit['id_poli'] ?? 0) == $p['id_poli'] ? 'selected' : '' ?>><?= e($p['nama_poli']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div><label>Nama dokter (beserta gelar)</label><input name="nama_dokter" value="<?= e($edit['nama_dokter'] ?? '') ?>" required></div>
      <div><label>Spesialis</label><input name="spesialis" value="<?= e($edit['spesialis'] ?? '') ?>" required></div>
      <div><label>Email login</label><input type="email" name="email" value="<?= e($edit['email'] ?? '') ?>" required></div>
      <div><label>Password login</label><input type="password" name="password" placeholder="<?= $edit ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' ?>"></div>
    </div>
    <div class="tombol-form" style="margin-top:1rem">
      <button class="tombol"><?= $edit ? 'Simpan perubahan' : '+ Tambah dokter' ?></button>
      <?php if ($edit): ?><a class="tombol garis" href="<?= url('admin/dokter.php') ?>">Batal</a><?php endif; ?>
    </div>
  </form>

  <div class="tabel-bungkus"><table>
    <tr><th>Nama</th><th>Poli</th><th>Spesialis</th><th>Akun login</th><th>Jadwal</th><th></th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><b><?= e($r['nama_dokter']) ?></b></td>
      <td><?= e($r['nama_poli']) ?></td>
      <td><?= e($r['spesialis']) ?></td>
      <td><?= $r['email'] ? e($r['email']) : '<span class="pil abu">Belum ada</span>' ?></td>
      <td><?= $r['jml_jadwal'] ?></td>
      <td class="aksi">
        <a class="tombol kecil garis" href="<?= url('admin/dokter.php?edit=' . $r['id_dokter']) ?>">Ubah</a>
        <form method="post" onsubmit="return confirm('Hapus dokter ini? Semua jadwal dan bookingnya ikut terhapus.')">
          <input type="hidden" name="hapus" value="<?= $r['id_dokter'] ?>">
          <button class="tombol kecil bahaya">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
