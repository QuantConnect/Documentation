<?php
function getGlossaryDefinition($term, $qualifier = null)
{
    $path = DOCS_RESOURCES . "/glossary/" . strtolower(str_replace(' ', '-', $term)) . ".html";
    $content = file_get_contents($path);

    if ($qualifier) {
        preg_match('/<p><span class=\'qualifier\'>\(' . preg_quote($qualifier, '/') . '\)<\/span>\s*(.*?)<\/p>/s', $content, $matches);
        return isset($matches[1]) ? trim($matches[1]) : '';
    }

    preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
    return isset($matches[1]) ? trim($matches[1]) : '';
}
?>
