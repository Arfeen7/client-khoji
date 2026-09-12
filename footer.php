<footer class="foot">
  <canvas class="foot__canvas" id="footCanvas" aria-hidden="true"></canvas>

  <div class="wrap">
    <div class="lock">
      <div class="lock__row">
        <span class="lock__big">Khoji</span>
        <span class="lock__tiny">&copy;<br>PK</span>
      </div>
      <div class="lock__row">
        <span class="lock__tiny">For</span>
        <span class="lock__big">every step</span>
        <span class="lock__tiny">and<br>its</span>
        <span class="lock__big">search</span>
      </div>
    </div>

    <div class="foot__links">
      <div>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Men</a>
        <a href="#" class="is-soon">Women &middot; soon</a>
      </div>
      <div>
        <a href="#">Size guide</a>
        <a href="#">Exchange &amp; returns</a>
      </div>
      <div>
        <a href="#">WhatsApp</a>
        <a href="mailto:hello@khoji.pk">hello@khoji.pk</a>
      </div>
      <div>
        <span>Rawalpindi</span>
        <span>Pakistan</span>
      </div>
      <div>
        <a href="#">Instagram</a>
        <a href="#">Facebook</a>
      </div>
    </div>

    <div class="foot__base">
      <span>&copy; <?php echo date( 'Y' ); ?> Khoji</span>
      <span>Cash on delivery nationwide</span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
