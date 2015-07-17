<!doctype html>
<?php
require_once __DIR__ . '/inc/header.php';

$allProjects = getProjects();
var_dump($allProjects);

?>

    <section>
      <table class="u-full-width">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Expires</th>
            <th>Repository</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach ($allProjects as $project) {
        ?>
          <tr>
            <td><?= $project['name'] ?></td>
            <td><?= $project['description'] ?></td>
            <td><?= $project['expires'] ?></td>
            <td><?= $project['repository_type'] ?></td>
            <td><a href="detail.php?id=<?= $project['id'] ?>">Details</a></td>
          </tr>
        <?php
        } // End foreach allProjects
        ?>
        </tbody>
      </table>
    </section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
