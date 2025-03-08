# Minimal Markdown Parser For PHP
Very simple, low level Markdown Parser for PHP. I don't need more functions or parsing features, so this minimal, light weight version covers my needs for my blog. 

## It can parse:
* Headlines
* Links
* Images
* Code block
* Unordered list
* bold

## Safety:
* too long entries will be blocked (can be set)
* HTML Escape & sanitizing
* Whitelisting of Code
* Blocks JavaScript inside Markdown
* X-Frame, XSS

## How to use:
* Save it as markdown.php file.
* Include the file in your blog or whatever PHP page, for example: require_once __DIR__ . '/markdown.php';
* Now include the main function somewhere on the top: $parser = new SecureMarkdownParser();
* And change the output of cour content to: parser->parse($post['content']);
* That's it.
* Note: also prevent direct access to the markdown.php file, e.g. with .htaccess or similar.

## Background:
The (very good & great) markdown parser for PHP from parsedown.org has too many features and increases the ressource load for my personal small needs & blog. So that's the reason, why I use this self made mini parser, which just supports minimal parsing for my own content and has a small footprint.

## Feedback is welcome
I don't need more features, but I'm still interested to improve the existing code & safety more, so any inputs & feedbacks are welcome!
