const form = document.getElementById('form-register');
if (form) {
  form.addEventListener('submit', (ev) => {
    if (form.password.value !== form.konfirmasi.value) {
      ev.preventDefault();
      alert('Konfirmasi password tidak sama.');
      form.konfirmasi.focus();
    }
  });
  // NIK hanya angka
  form.nik.addEventListener('input', () => {
    form.nik.value = form.nik.value.replace(/\D/g, '');
  });
}

