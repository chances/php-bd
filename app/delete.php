<?php
require_once __DIR__ . '/inc/header.php';
?>

  <section>

    <h2>Delete Project</h2>

    <p>Are you sure you want to delete your project, <strong>"Example project"</strong>?</p>

    <form action="delete.php" method="post">
      <div class="row">
        <a class="button" href="index.php">No, cancel</a>
        <input class="button button-primary" type="submit" value="Yes, delete it">
      </div>
    </form>

  </section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
