<?php
// Very simple, quick & dirty example

// Include the parser in your website somehwere at the top, depending on the rest of your code:
require_once __DIR__ . '/markdown.php';
$parser = new SecureMarkdownParser();

// Wherever you have the output of your content in your page (for example from a database query), change it to:
$parser->parse($post['content']);

// That's it.
?>


