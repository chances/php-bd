<?php
require_once __DIR__ . '/inc/header.php';
?>

  <section>

    <h2>New Project Form</h2>

    <p>Fill out the form below to create a Redmine project. After it is created, you can log in at https://projects.cecs.pdx.edu with your MCECS credentials and manage your new project from there.</p>

    <form action="new.php" method="post">
      <div class="row">
        <label for="name-field">Project Name</label>
        <input class="u-full-width" type="text" id="name-field" name="name" placeholder="myproject">
      </div>

      <div class="row">
        <label for="description-field">Project Description</label>
        <textarea class="u-full-width" id="description-field" placeholder="Briefly describe your project here"></textarea>
      </div>

      <div class="row">
        <label for="expires-field">Expiration Date</label>
        <input type="date" id="expires-field" name="expires">
      </div>

      <div class="row">
        <label for="repsitory-field">Repository</label>
        <select>
          <option value="0">None</option>
        </select>
      </div>

      <div class="row">
        <input class="button button-primary" type="submit" value="Submit">
      </div>
    </form>

  </section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
