<?php

$header = <<<'EOF'
This file is part of the Pinterest PHP library.

(c) Hans Ott <hansott@hotmail.be>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.md.

Source: https://github.com/hansott/pinterest-php
EOF;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

return Symfony\CS\Config\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers(array(
        'header_comment',
        'long_array_syntax',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'strict',
        'strict_param',
    ))
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->exclude('tests/Pinterest/fixtures')
            ->exclude('tests/Pinterest/responses')
            ->in(__DIR__)
    )
;
