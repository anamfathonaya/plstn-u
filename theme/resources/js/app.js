import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

// Cadangan light-dismiss untuk peramban tanpa dialog[closedby] (mis. Safari): klik backdrop menutup dialog.
if (!('closedBy' in HTMLDialogElement.prototype)) {
  document.addEventListener('click', (e) => {
    const d = e.target
    if (!(d instanceof HTMLDialogElement) || !d.open || !d.hasAttribute('closedby')) return
    const r = d.getBoundingClientRect()
    const inside = e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom
    if (!inside) d.close()
  })
}
