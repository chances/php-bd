<?php
require_once __DIR__ . '/inc/header.php';

if(!empty($_POST['id'])) {
  deleteProjectById($_POST['id']);
  header('Location: ' . $GLOBALS['BASE_URL']);
  die();
}

if(!isset($_GET['id'])) {
  header('Location: ' . $GLOBALS['BASE_URL']);
  die(); // not all clients respect location headers
}


$project = getProjectById($_GET['id']);

?>

  <section>

    <h2>Delete Project</h2>

    <p>Are you sure you want to delete your project, <strong>"Example project"</strong>?</p>

    <form action="delete.php" method="post">
      <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
      <div class="row">
        <a class="button" href="index.php">No, cancel</a>
        <input class="button button-primary" type="submit" value="Yes, delete it">
      </div>
    </form>

  </section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
