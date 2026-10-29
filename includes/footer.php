  </main>
  <footer class="site-footer"><span><?= h(t('footer_note')) ?></span><span>PHP · MySQL/MariaDB</span></footer>
</div>
<script>
document.querySelectorAll('[data-confirm]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    if (!window.confirm(form.dataset.confirm || 'Continue?')) event.preventDefault();
  });
});
</script>
</body>
</html>
