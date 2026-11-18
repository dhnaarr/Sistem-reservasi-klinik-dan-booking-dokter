<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


function jumlah_terisi($id_jadwal) {
    return db_satu("SELECT COUNT(*) AS n FROM pendaftaran WHERE id_jadwal = ? AND status <> 'batal'", [$id_jadwal])['n'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
    if (isset($_POST['hapus'])) {
        $id = (int)$_POST['hapus'];
        if (jumlah_terisi($id) > 0) set_pesan('error', 'Jadwal sudah punya booking. Ubah statusnya menjadi cuti, jangan dihapus.');
        else {
            db_jalankan('DELETE FROM jadwal_praktik WHERE id_jadwal = ?', [$id]);
            set_pesan('sukses', 'Jadwal dihapus.');
        }
        redirect('admin/jadwal.php');
    }

    
    $id        = (int)$_POST['id'];                         
    $id_dokter = (int)$_POST['id_dokter'];
    $tanggal   = $_POST['tanggal'];
    $sesi      = $_POST['sesi'];
    $mulai     = $_POST['jam_mulai'];
    $selesai   = $_POST['jam_selesai'];
    $kuota     = (int)$_POST['kuota'];
    $status    = $_POST['status'];
    $catatan   = trim($_POST['catatan']);
    if ($status === 'cuti' && $catatan === '') $catatan = 'Cuti';

    if (!$id_dokter || $tanggal === '' || !in_array($sesi, ['pagi', 'siang', 'sore']) || !in_array($status, ['aktif', 'cuti'])) {
        set_pesan('error', 'Dokter, tanggal, sesi, dan status wajib diisi dengan benar.');
    } elseif ($mulai === '' || $selesai === '' || $selesai <= $mulai) {
        set_pesan('error', 'Jam selesai harus setelah jam mulai.');
    } elseif ($kuota < 1 || $kuota > 200) {
        set_pesan('error', 'Kuota harus antara 1 sampai 200.');
    } elseif ($id && $kuota < jumlah_terisi($id)) {
        set_pesan('error', 'Kuota tidak boleh lebih kecil dari jumlah booking yang sudah ada.');
    } else {
        try {
            if ($id) {
                db_jalankan('UPDATE jadwal_praktik SET id_dokter=?, tanggal=?, sesi=?, jam_mulai=?, jam_selesai=?, kuota=?, status=?, catatan=? WHERE id_jadwal=?',
                    [$id_dokter, $tanggal, $sesi, $mulai, $selesai, $kuota, $status, $catatan ?: null, $id]);
            } else {
                db_jalankan('INSERT INTO jadwal_praktik (id_dokter, tanggal, sesi, jam_mulai, jam_selesai, kuota, status, catatan) VALUES (?,?,?,?,?,?,?,?)',
                    [$id_dokter, $tanggal, $sesi, $mulai, $selesai, $kuota, $status, $catatan ?: null]);
            }
            set_pesan('sukses', 'Jadwal disimpan.');
        } catch (PDOException $e) {
            set_pesan('error', 'Jadwal dokter pada tanggal dan sesi tersebut sudah ada.');
        }
    }
    redirect('admin/jadwal.php');
}


$edit = isset($_GET['edit']) ? db_satu('SELECT * FROM jadwal_praktik WHERE id_jadwal = ?', [(int)$_GET['edit']]) : null;
$semua_dokter = db_semua('SELECT d.id_dokter, d.nama_dokter, p.nama_poli FROM dokter d JOIN poli p ON p.id_poli = d.id_poli ORDER BY d.nama_dokter');


$filter = (int)($_GET['dokter'] ?? 0);
$sql = "SELECT j.*, d.nama_dokter, p.nama_poli,
        (SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jadwal = j.id_jadwal AND x.status <> 'batal') AS terisi
    FROM jadwal_praktik j
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli p ON p.id_poli = d.id_poli
    WHERE j.tanggal >= CURDATE()";
$param = [];
if ($filter) { $sql .= ' AND j.id_dokter = ?'; $param[] = $filter; }
$sql .= ' ORDER BY j.tanggal, j.jam_mulai LIMIT 100';
$rows = db_semua($sql, $param);


$f = $edit ?: ['id_jadwal' => 0, 'id_dokter' => 0, 'tanggal' => '', 'sesi' => 'pagi', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00',
               'kuota' => 20, 'status' => 'aktif', 'catatan' => ''];

$judul = 'Jadwal Praktik';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Jadwal praktik</h1><p class="redup">Atur tanggal, sesi, kuota, dan status cuti dokter.</p></div>
  </div>

  <form method="post" class="kartu">
    <h3><?= $edit ? 'Ubah jadwal' : 'Tambah jadwal' ?></h3>
    <input type="hidden" name="id" value="<?= $f['id_jadwal'] ?>">
    <div class="dua-kolom">
      <div><label>Dokter</label>
        <select name="id_dokter" required>
          <option value="">Pilih dokter</option>
          <?php foreach ($semua_dokter as $d): ?>
            <option value="<?= $d['id_dokter'] ?>" <?= $f['id_dokter'] == $d['id_dokter'] ? 'selected' : '' ?>><?= e($d['nama_dokter']) ?> (<?= e($d['nama_poli']) ?>)</option>
          <?php endforeach; ?>
        </select></div>
      <div><label>Tanggal</label><input type="date" name="tanggal" value="<?= e($f['tanggal']) ?>" required></div>
      <div><label>Sesi</label>
        <select name="sesi">
          <?php foreach (['pagi', 'siang', 'sore'] as $s): ?>
            <option value="<?= $s ?>" <?= $f['sesi'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div><label>Status</label>
        <select name="status">
          <option value="aktif" <?= $f['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
          <option value="cuti" <?= $f['status'] === 'cuti' ? 'selected' : '' ?>>Cuti</option>
        </select></div>
      <div><label>Jam mulai</label><input type="time" name="jam_mulai" value="<?= e(jam($f['jam_mulai'])) ?>" required></div>
      <div><label>Jam selesai</label><input type="time" name="jam_selesai" value="<?= e(jam($f['jam_selesai'])) ?>" required></div>
      <div><label>Kuota</label><input type="number" name="kuota" value="<?= (int)$f['kuota'] ?>" min="1" max="200" required></div>
      <div><label>Catatan (opsional)</label><input name="catatan" value="<?= e($f['catatan']) ?>" maxlength="100" placeholder="Contoh: Dokter sedang cuti"></div>
    </div>
    <div class="tombol-form" style="margin-top:1rem">
      <button class="tombol"><?= $edit ? 'Simpan perubahan' : '+ Tambah jadwal' ?></button>
      <?php if ($edit): ?><a class="tombol garis" href="<?= url('admin/jadwal.php') ?>">Batal</a><?php endif; ?>
    </div>
  </form>

  <h2 class="sub-judul">Daftar jadwal</h2>
  <form method="get" class="baris-cari">
    <select name="dokter">
      <option value="0">Semua dokter</option>
      <?php foreach ($semua_dokter as $d): ?>
        <option value="<?= $d['id_dokter'] ?>" <?= $filter == $d['id_dokter'] ? 'selected' : '' ?>><?= e($d['nama_dokter']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="tombol">Tampilkan</button>
  </form>

  <div class="tabel-bungkus"><table>
    <tr><th>Tanggal</th><th>Dokter</th><th>Sesi / jam</th><th>Terisi</th><th>Status</th><th></th></tr>
    <?php if (!$rows): ?><tr><td colspan="6" class="kosong">Belum ada jadwal.</td></tr><?php endif; ?>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e(tgl_indo($r['tanggal'])) ?></td>
      <td><b><?= e($r['nama_dokter']) ?></b><br><small class="redup"><?= e($r['nama_poli']) ?></small></td>
      <td><?= ucfirst($r['sesi']) ?><br><small class="redup"><?= jam($r['jam_mulai']) ?> - <?= jam($r['jam_selesai']) ?></small></td>
      <td><?= $r['terisi'] ?> / <?= $r['kuota'] ?></td>
      <td><?= $r['status'] === 'cuti' ? '<span class="pil kuning">Cuti</span>' : '<span class="pil hijau">Aktif</span>' ?></td>
      <td class="aksi">
        <a class="tombol kecil garis" href="<?= url('admin/jadwal.php?edit=' . $r['id_jadwal']) ?>">Ubah</a>
        <form method="post" onsubmit="return confirm('Hapus jadwal ini?')">
          <input type="hidden" name="hapus" value="<?= $r['id_jadwal'] ?>">
          <button class="tombol kecil bahaya">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
