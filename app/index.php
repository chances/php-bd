<!doctype html>
<?php
require_once '../config.php';

?>

<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css">
  <title>Project List</title>
</head>
<body>

  <div class="container">

    <header>
      <h1 class="section-heading">Project Manager</h1>
    </header>

    <nav class="row">
      <a class="button" href="#">List Projects</a>
      <a class="button button-primary" href="#">New Project</a>
    </nav>

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
          <tr>
            <td>Example Project</td>
            <td>My project is cool</td>
            <td>2015-12-31</td>
            <td>git</td>
            <td><a href="#">Details</a></td>
          </tr>
        </tbody>
      </table>
    </section>

    <footer>
      <a href="#">Log out</a>
    </footer>

  </div>

  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
</body>
</html>
