Security
========

## Input Sanitization/Validation

Ensure any data given by the user is safe and clean to be passed to potentially fragile pieces of a web application,
like the database, for example.

[Reference explanation](https://www.owasp.org/index.php/Data_Validation#Description)

> “The most common web application security weakness is the failure to properly validate input from the client or
> environment. This weakness leads to almost all of the major vulnerabilities in applications, such as Interpreter
> Injection, locale/Unicode attacks, file system attacks and buffer overflows. Data from the client should never be
> trusted for the client has every possibility to tamper with the data.”

Basil covered simple sanitazion of data sent to the database (preventing SQL injections) as parameterized queries
last week.

### Ensure required input is present

Ideally, we'd want to validate that required information is entered before the user submits a form. This involves
writing client side JavaScript to validate all of the inputs and to prevent the form from submitting if there's any
problem.

Really, though, client side validation should not be the catch all. The server should always validate input
regardless of the source.

Basil's code already checks for the existence of a project's name, description, and expiration date.

### Accept known good input

This strategy is also known as "whitelist" or "positive" validation. The idea is that you should check that the data
is one of a set of tightly constrained known good values. Any data that doesn't match should be rejected. Data
should be:

* Strongly typed at all times
* Length checked and fields length minimized (maxlength with HTML 5 client side)
* Range checked if a numeric (min and max attributes)
* Unsigned unless required to be signed
* Syntax or grammar should be checked prior to first use or inspection
* If you expect a postcode, validate for a postcode (type, length and syntax):

For creating a new project, validating the lengths of inputs and using some RegEx for the expires date:

```
$length = strlen(utf8_decode($s));

if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['expires'])) {
    $message = "Please fill in a name, a description, and an expiration date.";
}

if (0 < strlen(utf8_decode($s)) > 25) {
    throw new Exception("The $layman_variable_name must not be longer than 25 characters.");
}

preg_match('/^\d{2}\/\d{2}\/\d{4}$', $expires, $matches);
if (count($matches) == 0) {
    throw new Exception("Please format date as mm/dd/yyyy");
}
```

## Error Checking

### Ensuring queries run successfully

Lots of pg functions return FALSE if they failed to run and there's an error.

pg_query - If an error occurs, and FALSE is returned, details of the error can be retrieved using the pg_last_error()
function if the connection is valid.
pg_fetch_all - FALSE is returned if there are no rows in the result, or on any other error.

```
if ($result == false) {
    throw new Exception("Could not retrieve projects.");
}
$result = pg_fetch_all($result);
if (count($result) == 0) {
    throw new Exception("There are no projects.", 1);
}
return $result;
```

In the project listing:

```
/**
 * @var $error Exception
 */
$error = null;

try {
  $allProjects = getProjects();
} catch (Exception $ex) {
  $error = $ex->getMessage();
}

if (!is_null($error)) {
?>
  <section><?= $error ?></section>
<?php
}

if (is_null($error) || $error->getCode() != 1) {
  ?>
```

```
} // End no projects check
```

### Transactions

A transaction is an atomic unit of database operations against the data in one or more databases.
SQL statements in a transaction can be either all committed to the database or all rolled back. SQL statements are
put into transactions for data safety and integrity.

In PostgreSQL PHP each SQL statement is committed to the database after it is executed. This is not true for all
language bindings. For example in Python's psycopg2 module all changes must be explicitly committed with a commit()
method by default.

In direct SQL, a transaction is started with BEGIN TRANSACTION statement and ended with END TRANSACTION, COMMIT
statement. In PostgreSQL these statements are BEGIN and COMMIT. In some drivers these statements are omitted. They
are handled by the driver. In PHP there are no such methods and the we must use the direct SQL. (In PHP PDO there
are such methods.)

```
if (!pg_query("BEGIN"))
    throw new Exception("Could not create a new project.");

if (!pg_query("COMMIT"))
    throw new Exception("Could not create a new project.");

if (!pg_query("ROLLBACK"))
    throw new Exception("Could not create a new project.");
    
## Some UX Philosophies

User Experience is a very important aspect of any application, website, etc. These aren't philosophies so much as
they're guidelines usually backed up by heaps of scientific research. [UX Stackexchange](http://ux.stackexchange.com)

Remove as many unnecessary details which don't help the user do what they want to do.

* Don't overcrowd pages with unimportant information. Get to the point.
* 

### Layout

[Z Layout Pattern](http://webdesign.tutsplus.com/articles/understanding-the-z-layout-in-web-design--webdesign-28)

![Z Layout Site](https://cdn.tutsplus.com/webdesign/uploads/legacy/004_Z_Layout/2.jpg)

![Z Layout Example](https://cdn.tutsplus.com/webdesign/uploads/legacy/004_Z_Layout/3.jpg)

It works because most Western readers will scan a site the same way that they would scan a book - top to bottom,
left to right.

### Prose and Information

* Use concise, clear, and unambiguous language when telling the user anything.
* Be professional. Don't yell at them.
* Don't use overly technical jargon they most likely won't be able to understand. (Remember those terrible error
  messages?)
* Don't say nothing in exceptional situations. If something is required of the user or something has gone wrong, tell
  the user. Don't leave user scratching their heads.
* Be nice. If there's a problem, suggest how they can fix it.


