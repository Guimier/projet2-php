#!/usr/bin/env php
<?php
/* https://stackoverflow.com/questions/4325224/doxygen-how-to-describe-class-member-variables-in-php */

$source = file_get_contents($argv[1]);

$regexp = '#\@type\s+([^\s]+)([^/]+)/\s+((?:var|public|protected|private)(?:\s+static)?)\s+(\$[^\s;=]+)#';
$replac = '${2} */ ${3} ${1} ${4}';
$source = preg_replace($regexp, $replac, $source);

echo $source;
