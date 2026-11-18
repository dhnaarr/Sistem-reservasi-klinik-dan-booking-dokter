(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

 
  document.addEventListener('submit', (e) => {
    const m = e.target.dataset && e.target.dataset.confirm;
    if (m && !confirm(m)) e.preventDefault();
  });


  $$('[data-open]').forEach((btn) => btn.addEventListener('click', () => {
    const dlg = document.getElementById(btn.dataset.open);
    const form = $('form', dlg);
    form.reset();
    if (form.elements.id) form.elements.id.value = '';
    $('.d-title', dlg).textContent = btn.dataset.judul || '';
    if (btn.dataset.edit) {
      Object.entries(JSON.parse(btn.dataset.edit)).forEach(([k, v]) => {
        if (form.elements[k]) form.elements[k].value = v ?? '';
      });
    }
    dlg.showModal();
  }));
  $$('dialog').forEach((d) => {
    d.addEventListener('click', (e) => { if (e.target === d) d.close(); });
    $$('[data-close]', d).forEach((b) => b.addEventListener('click', () => d.close()));
  });


  $$('[data-filter]').forEach((inp) => inp.addEventListener('input', () => {
    const q = inp.value.trim().toLowerCase();
    $$(inp.dataset.filter).forEach((el) => { el.hidden = q !== '' && !el.textContent.toLowerCase().includes(q); });
  }));

 
  $$('[data-chip]').forEach((chip) => chip.addEventListener('click', () => {
    $$('[data-chip]').forEach((c) => c.classList.toggle('on', c === chip));
    const k = chip.dataset.chip;
    $$('.pesan-item').forEach((it) => { it.hidden = k !== 'semua' && it.dataset.kat !== k; });
  }));


  const preset = { pagi: ['08:00', '12:00', 20], siang: ['12:00', '15:00', 15], sore: ['15:00', '18:00', 15] };
  const sesi = $('#form-jadwal select[name=sesi]');
  if (sesi) sesi.addEventListener('change', () => {
    const p = preset[sesi.value], f = sesi.form;
    if (p) { f.elements.jam_mulai.value = p[0]; f.elements.jam_selesai.value = p[1]; f.elements.kuota.value = p[2]; }
  });


  $$('input[name=nik]').forEach((i) => i.addEventListener('input', () => { i.value = i.value.replace(/\D/g, ''); }));


  const reg = $('#form-register');
  if (reg) reg.addEventListener('submit', (e) => {
    if (reg.password.value !== reg.konfirmasi.value) { e.preventDefault(); alert('Konfirmasi password tidak sama.'); }
  });


  const fb = $('#form-booking');
  if (fb) {
    const radios = $$('input[name=jenis_penjamin]', fb);
    const slide = $('.slide', fb);
    const jenis = () => (radios.find((r) => r.checked) || {}).value;
    const tampil = () => { $('#box-asuransi').hidden = jenis() !== 'asuransi'; };
    radios.forEach((r) => r.addEventListener('change', tampil));
    tampil();
    slide.addEventListener('change', () => {
      if (+slide.value >= 96) fb.requestSubmit(); else slide.value = 0;
    });
    fb.addEventListener('submit', (e) => {
      if (jenis() === 'bpjs') { e.preventDefault(); slide.value = 0; $('#dlg-bpjs').showModal(); }
      else if (jenis() === 'asuransi' && !$('input[name=id_asuransi]:checked', fb)) {
        e.preventDefault(); slide.value = 0; alert('Pilih perusahaan asuransi terlebih dahulu.');
      }
    });
  }


  const live = $('#live');
  if (live) setInterval(async () => {
    try {
      const d = await (await fetch(live.dataset.src, { credentials: 'same-origin' })).json();
      $('#lv-saya').textContent = d.saya;
      $('#lv-dilayani').textContent = d.dilayani;
      $('#lv-estimasi').textContent = d.estimasi;
      $('#lv-depan').textContent = d.depan;
    } catch (_) {}
  }, 10000);
})();
