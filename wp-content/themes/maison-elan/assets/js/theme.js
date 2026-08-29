document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.elan-header');
  const toggle = document.querySelector('.elan-menu-toggle');
  if (!header || !toggle) return;
  toggle.addEventListener('click', () => {
    const open = header.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
  });
  header.querySelectorAll('.elan-nav a').forEach((link) => link.addEventListener('click', () => {
    header.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  }));
});
