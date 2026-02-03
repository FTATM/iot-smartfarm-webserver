function setCardValue(key, value) {
  const card = document.getElementById(`card-${key}`);
  if (!card) return;

  const valueEl = card.querySelector('.value');
  const statusEl = card.querySelector('.status');

  if (value === null || value === undefined || isNaN(value)) {
    valueEl.textContent = '--';
    statusEl.textContent = 'N/A';
    statusEl.className =
      'status px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[12px] font-bold uppercase';
    return;
  }

  valueEl.textContent = value.toFixed(2);

  // ตัวอย่าง threshold
  let status = 'OK';
  let cls = 'bg-green-100 text-green-600';

  if (key === 'do' && value < 3) {
    status = 'LOW';
    cls = 'bg-red-100 text-red-600';
  }

  statusEl.textContent = status;
  statusEl.className =
    `status px-2 py-0.5 rounded-full ${cls} text-[12px] font-bold uppercase`;
}
