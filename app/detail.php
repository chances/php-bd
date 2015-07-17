<?php
require_once __DIR__ . '/inc/header.php';
?>

  <section>

    <h2>Project Details: Example Project</h2>

    <h3>Redmine URL</h3>

    <p>You can access your project at <a href="#">(insert link here)</a>.</p>

    <h3>Repository Access</h3>
    <p>Project members with either the Manager or Developer role can check out and commit to the repository using the following method(s).</p>
    <h4>HTTPS</h4>
    <p>You will be asked for your MCECS username and password.</p>
    <pre><code>
    git clone https://you@projects.cecs.pdx.edu/git/exampleproject
    </code></pre>

    <h4>SSH Keys</h4>
    <p>You will be asked for your ssh key passphase. You can add ssh public keys to redmine <a href="https://intranet.cecs.pdx.edu/projects/keys">here</a>.</p>
    <pre><code>
    git clone git@projects.cecs.pdx.edu:exampleproject
    </code></pre>

    <h4>Anonymous Access</h4>
    <p>If this project is publicly accessible, anonymous users can clone with this command. To make the project public, go to Settings and check the box marked Public.</p>
    <pre><code>
    git clone https://projects.cecs.pdx.edu/git/exampleproject
    </code></pre>

  <a class="button button-primary" href="#">Delete this project</a>

  </section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
