Base URLs
===

On apache you can do this:
```
$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['CONTEXT_PREFIX'];
```

But you can't always rely on specific server variables. Usually this goes in a config file.

PECL vs PEAR
===

PECL = PHP Extension Community Library, written in C, can be loaded into PHP. You need admin rights, a C compiler.

Ubuntu's `php5-pgsql` installs the `PDO_PGSQL` pecl package.

PEAR = PHP Extension and Application Repository, libraries are IN php. It's a distribution/packageing system. It's possible to use it without root, but it's hard. Kind of like CPAN-hard.

Composer is awesome! We don't use it.

Talking to the DB
=====
You need the php5-pgsql package for this to work.

pg_connect() and pg_pconnect()

Both Return a connection resource needed by other psql functions.

Connecting to a postgres db takes rather more resources than connecting to a mysql db, so pg_connect is a bit more expensive than its php cousin.

If multiple calls with the same connstring are made to pg_pconnect specifically, the same connection will be returned - the connection stays around in persistent memory.

When there are a lot of simultaneous connections, we can use a connection pooler like http://wiki.postgresql.org/wiki/PgBouncer to lighten the load, but pg_pconnect performs well enough.

FPM mitigates the pooling need a bit, since the number of PHP workers is controlled, so pg_pconnect is a bit safer on FPM than mod_php or others.

```php
$connstring = "dbname=" . $config['db']['name']
. " host=" . $config['db']['host']
. " user=" . $config['db']['user']
. " password=" . $config['db']['password'];

// The persistent database connection
$GLOBALS['DB'] = pg_pconnect($connstring);
if(!$DB) die("Error: Can't connect to the database.");
```

Connecting
----
Get a connstring and pass it to pg_pconnect():
http://php.net/manual/en/function.pg-pconnect.php
check that pg_pconnect() returned !null


Write simple functions for getting projects stuff out of the database; no joins yet:

```php
function getProjects() {
  $query = "SELECT * FROM redmine_projects";
  $result = pg_query($GLOBALS['DB'], $query);
  return pg_fetch_all($result);
}

function getProjectById($id) {
  $query = "SELECT * FROM redmine_projects WHERE id = $1";
  $result = pg_query_params($GLOBALS['DB'], $query, array($id));
  return pg_fetch_all($result);
}

function deleteProjectById($id) {
  $query = "DELETE FROM redmine_projects WHERE id = $1";
  $result = pg_query_params($GLOBALS['DB'], $query, array($id));
  return pg_fetch_all($result);
}
```

Then put it in the index:
```php
  <?php
  $allProjects = getProjects();
  ?>
...
  <?php
  foreach ($allProjects as $project) {
  ?>
    <tr>
      <td><?= $project['name'] ?></td>
      <td><?= $project['description'] ?></td>
      <td><?= $project['expires'] ?></td>
      <td><?= $project['repository_type'] ?></td>
      <td><a href="detail.php">Details</a></td>
    </tr>
  <?php
  } // End foreach allProjects
  ?>
```

But we need the relation for project type, so let's add a join:

```php
  function getProjects() {
    $query = "SELECT projects.name, description, expires, repository_types.name as repository_type
              FROM projects
              JOIN repository_types
              ON (projects.repository_type = repository_types.id)";
    $result = pg_query($GLOBALS['DB'], $query);
    return pg_fetch_all($result);
  }
```

Now the page displays the actual repo type instead of the id.

Make detail links:
1. add `projects.id` to the select statement so we get ids out
2. add `detail.php?id=<?= $project['id'] ?>` to the detail url in the table.

Time to use the info on the detail page, but first make sure we have an id:

```php
if(!isset($_GET['id'])) {
  header('Location: ' . $GLOBALS['BASE_URL']);
  die(); // not all clients respect location headers
}

$project = getProjectById($_GET['id']);
```

We'll also need to modify our project fetching query to join and get the repository type.

```
function getProjectById($id) {
  $query = "SELECT projects.id, projects.name, description, expires, repository_types.name as repository_type
              FROM projects
              JOIN repository_types
              ON (projects.repository_type = repository_types.id)
              WHERE projects.id = $1";
  $result = pg_query_params($GLOBALS['DB'], $query, array($id));
  return pg_fetch_all($result);
}
```

When you echo $project['name'] to the page, it fails - why? pg_fetch_all returns an array of rows - and even though we only have one row, it's not smart enough to know that. It returns an array of one.

Change `pg_fetch_all` to `pg_fetch_array`, and you'll be able to do $project['name'] successfully.

Make new project form work:

Notice that our repository type dropdown contains nothing. We need to get database info about repo types that are availablei in there.

Add a function:

```php
function getRepositoryTypes() {
  $query = "SELECT * from repository_types";
  $result = pg_query($GLOBALS['DB'], $query);
  return pg_fetch_all($result);
}
```

Use this to populate the dropdown:

```php
$repositoryTypes = getRepositoryTypes();
...
<select>
  <option value="">None</option>
  <?php
  foreach ($repositoryTypes as $repositoryType) {
  ?>
      <option value="<?= $repositoryType['id'] ?>"><?= $repositoryType['name'] ?></option>
  <?php
  }
  ?>
</select>
```

(now submit the form and dump `$_POST`)

We'll need an add function:

```php
function newProject($name, $description, $expires, $repository_type = NULL) {
  $query = "INSERT INTO projects (name, description, expires, repository_type) VALUES ( $1, $2, $3, $4)";
  $result = pg_query_params($GLOBALS['DB'], $query, array(
    $name,
    $description,
    $expires,
    $repository_type
  ));
  return pg_fetch_all($result);
}
```

We need to add a basic error check to make sure we have minimum information:

```php
    $repositoryTypes = getRepositoryTypes();

    if(!empty($_POST)) {
      if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['expires'])) {
        $message = "Please fill in a name, a description, and an expiration date.";
      } else {
        newProject($_POST['name'], $_POST['description'], $_POST['expires'], $_POST['repository_type']);
        $message = "Project created.";
      }
    }
    ?>

...
    <?php if(isset($message)) { ?>
    <p><strong><?= $message ?></strong></p>
    <?php } ?>
```

Make the delete form work:

We need a project id on this form too, so add this to the top:

```
if(!empty($_POST)) {
  if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['expires'])) {
    $message = "Please fill in a name, a description, and an expiration date.";
  } else {
    newProject($_POST['name'], $_POST['description'], $_POST['expires'], $_POST['repository_type']);
    $message = "Project created.";
  }
}
?>
```

And make sure we pass in an id from the project detail view:

```php
  <a class="button button-primary" href="delete.php?id=<?= $project['id'] ?>">Delete this project</a>
```
Now to get the id of the project to delete into the form: add a hidden input:

```php
    <form action="delete.php" method="post">
      <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
      <div class="row">
        <a class="button" href="index.php">No, cancel</a>
        <input class="button button-primary" type="submit" value="Yes, delete it">
      </div>
    </form>
```
And process it above (BEFORE the `$_GET` check)

```php
if(!empty($_POST['id'])) {
  deleteProjectById($_POST['id']);
  header('Location: ' . $GLOBALS['BASE_URL']);
  die();
}
```

