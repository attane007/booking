          </div> <!-- /.container -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">พัฒนาระบบโดย นายพงศธร แสงม่วง ปรับปรุงโดย ปรมินทร์ อัตตะเนย์</div>
            </div>
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>
  </div>
  <!-- / Layout wrapper -->


  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="../assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script>
    $('.preloader').fadeOut();
  </script>

  <!-- Accessibility: ensure no focused element remains inside a modal when aria-hidden is applied -->
  <script>
    (function () {
      // Listen for bootstrap modal hide event and move focus out of the modal before aria-hidden is applied.
      document.addEventListener('hide.bs.modal', function (evt) {
        try {
          var modal = evt.target;
          var active = document.activeElement;
          if (modal && active && modal.contains(active)) {
            // Try to blur the focused element first
            try { active.blur(); } catch (e) { /* ignore */ }

            // Move focus to a sensible fallback container so assistive tech is not left pointing inside a hidden element
            var fallback = document.querySelector('.layout-wrapper') || document.body;
            // Make fallback focusable temporarily
            var removedTabindex = false;
            if (fallback && fallback.getAttribute && fallback.getAttribute('tabindex') !== '-1') {
              fallback.setAttribute('tabindex', '-1');
              removedTabindex = true;
            }
            try { fallback.focus(); } catch (e) { /* ignore */ }
            // optional: remove tabindex if we added it
            if (removedTabindex) {
              // keep the tabindex for accessibility; removal is optional
              // fallback.removeAttribute('tabindex');
            }
          }
        } catch (err) {
          // safe no-op
          console.warn('Modal focus-shift handler error', err);
        }
      }, true);
    })();
  </script>

  <!-- Optional external scripts -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
