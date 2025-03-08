# Minimal Markdown Parser For PHP
Very simple, low level Markdown Parser for PHP. I don't need all functions or parsing features of the big ones, so this minimal, light weight version covers my tiny needs for my blog. 

## It can parse:
* Headlines
* Links
* Images
* Code block
* bold
* italic
* Unordered list

## Safety:
* DoS, too long entries will be blocked (can be set)
* HTML Escape & sanitizing
* Whitelisting of Code
* Blocks JavaScript inside Markdown
* X-Frame, XSS

## How to use:
* Upload the markdown.php file to your website directory.
* Include the file in your blog or whatever PHP page - see the example.php file.
* That's it.
* Note: also prevent direct access to the markdown.php file, e.g. with .htaccess or similar.

## Background:
The (very good & great) markdown parser for PHP from parsedown.org has too many features and increases the ressource load for my personal small needs & blog. So that's the reason, why I use this self made mini parser, which just supports minimal parsing for my own content and has a small footprint.

## Feedback is welcome
I don't need more features, but I'm still interested to improve the existing code & safety more, so any inputs & feedbacks are welcome!
