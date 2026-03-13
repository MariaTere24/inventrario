// script.js - controla el menú hamburguesa y comportamientos UI
document.addEventListener('DOMContentLoaded', function(){
  const btn = document.getElementById('btnToggle');
  const sidebar = document.getElementById('sidebar');

  if (!btn || !sidebar) return;

  btn.addEventListener('click', function(){
    // Toggle collapse state. Use a single class for collapsed.
    if (sidebar.classList.contains('closed')){
      sidebar.classList.remove('closed');
      sidebar.classList.add('open');
      localStorage.setItem('sidebar', 'open');
    } else {
      sidebar.classList.remove('open');
      sidebar.classList.add('closed');
      localStorage.setItem('sidebar', 'closed');
    }
  });

  // Cerrar al hacer click fuera en pantallas pequeñas
  document.addEventListener('click', function(e){
    const target = e.target;
    if (window.innerWidth <= 900 && !sidebar.contains(target) && !btn.contains(target)){
      sidebar.classList.remove('open');
      sidebar.classList.add('closed');
    }
  });

  // Restore state from localStorage
  const state = localStorage.getItem('sidebar');
  if (state === 'closed'){
    sidebar.classList.remove('open');
    sidebar.classList.add('closed');
  } else {
    sidebar.classList.add('open');
  }

});
