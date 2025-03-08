<?php
// Start -----------------------------------------------------------------------
// -----------------------------------------------------------------------
// MARKDOWN PARSER - MINIMAL
// -----------------------------------------------------------------------
class SecureMarkdownParser {
    public function parse($markdown) {
        
// -----------------------------------------------------------------------
// Limitation against direct input / DoS (worst case)
// -----------------------------------------------------------------------
        if (strlen($markdown) > 100000) {
            return "Fehler: Eingabe zu lange";
        }
        
// -----------------------------------------------------------------------
// HTML-Escape
// -----------------------------------------------------------------------
        $markdown = htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8');

// -----------------------------------------------------------------------
// Code blocks
// -----------------------------------------------------------------------
        $codeBlocks = [];
        $markdown = preg_replace_callback('/((?:^|\n)( {4,8}.*(?:\n|$))+)/m', function($matches) use (&$codeBlocks) {
            $code = ltrim($matches[0], "\n");
            $code = preg_replace('/^( {4,8})/m', '', $code);
            $placeholder = "%%CODEBLOCK" . count($codeBlocks) . "%%";
            $codeBlocks[$placeholder] = "<pre><code>" . htmlspecialchars(rtrim($code, "\n"), ENT_QUOTES, 'UTF-8') . "</code></pre>";
            return "\n" . $placeholder . "\n";
        }, $markdown);

// -----------------------------------------------------------------------
// bold **Text** oder __Text__
// -----------------------------------------------------------------------
        $markdown = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $markdown);
        $markdown = preg_replace('/__(.*?)__/s', '<strong>$1</strong>', $markdown);

// -----------------------------------------------------------------------
// Images ![Alt](URL) -> <img src="URL" alt="Alt">
// -----------------------------------------------------------------------
        $markdown = preg_replace_callback('/!\[(.*?)\]\((.*?)\)/s', function($matches) {
            $alt = htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
            $url = $this->sanitizeUrl($matches[2]);
            return '<img src="' . $url . '" alt="' . $alt . '">';
        }, $markdown);

// -----------------------------------------------------------------------
// Links [Text](URL) -> <a href="URL">Text</a>
// -----------------------------------------------------------------------
        $markdown = preg_replace_callback('/\[(.*?)\]\((.*?)\)/s', function($matches) {
            $text = htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
            $url = $this->sanitizeUrl($matches[2]);
            return '<a href="' . $url . '">' . $text . '</a>';
        }, $markdown);

// -----------------------------------------------------------------------
// Headlines # - ######
// -----------------------------------------------------------------------
        for ($i = 6; $i >= 1; $i--) {
            $pattern = '/^' . str_repeat('#', $i) . '\s+(.+)$/m';
            $replacement = "<h$i>$1</h$i>";
            $markdown = preg_replace($pattern, $replacement, $markdown);
        }

// -----------------------------------------------------------------------
// Unordered list
// -----------------------------------------------------------------------
        $markdown = preg_replace_callback('/((?:^|\n)(?:[*\-]\s+.+(?:\n|$))+)/', function($matches) {
            $lines = preg_split('/\r?\n/', trim($matches[0]));
            $listItems = '';
            foreach($lines as $line) {
                $line = preg_replace('/^[*\-]\s+/', '', $line);
                $listItems .= '<li>' . $line . '</li>';
            }
            return '<ul>' . $listItems . '</ul>';
        }, $markdown);

// -----------------------------------------------------------------------         
// Paragraph
// -----------------------------------------------------------------------
        $blocks = preg_split('/(?:\r?\n){2,}/', $markdown);
        $output = '';
        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') continue;
            if (preg_match('/^(<(h[1-6]|ul|ol|img|p|li|blockquote|pre|div))/', $block)) {
                $output .= $block;
            } else {
                $output .= '<p>' . $block . '</p>';
            }
        }

// -----------------------------------------------------------------------       
// Code blocks back 
// -----------------------------------------------------------------------
        foreach ($codeBlocks as $placeholder => $codeBlock) {
            $output = str_replace($placeholder, $codeBlock, $output);
        }

// -----------------------------------------------------------------------       
// Remove unwanted HTML-Tags
// -----------------------------------------------------------------------
        $output = $this->allowSafeHtml($output);
        return trim($output);
    }

// -----------------------------------------------------------------------
// Check, sanitize & kill JavaScript too
// -----------------------------------------------------------------------
    private function sanitizeUrl($url) {
        $decodedUrl = html_entity_decode($url, ENT_QUOTES, 'UTF-8');
        $decodedUrl = filter_var($decodedUrl, FILTER_SANITIZE_URL);
        
        if (!preg_match('#^(https?://|mailto:|/|\.\./|\./)#i', $decodedUrl) || stripos($decodedUrl, 'javascript:') === 0) {
            return '#';
        }
        return htmlspecialchars($decodedUrl, ENT_QUOTES, 'UTF-8');
    }
    
// -----------------------------------------------------------------------
// Whitelisting of HTML tags / stay safe & explicit
// -----------------------------------------------------------------------
    private function allowSafeHtml($text) {
        return strip_tags($text, '<strong><em><h1><h2><h3><h4><h5><h6><ul><ol><li><p><br><pre><code><a><img>');
    }
}

// -----------------------------------------------------------------------
// Limit clickjacking & XSS
// -----------------------------------------------------------------------
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

// End -----------------------------------------------------------------------
?>
