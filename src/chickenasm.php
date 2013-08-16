<?php

namespace igorw\chicken;

use Functional as F;

/** @api */
function chickenasm_to_eggsembler($code) {
    $lines = explode("\n", $code);
    $lines = F\flatten(F\map($lines, __NAMESPACE__.'\\translate_chickenasm_line'));
    return implode("\n", $lines);
}

function translate_chickenasm_line($line) {
    if (0 === strpos($line, '#') || '' === $line) {
        return $line;
    }

    if (preg_match('#^push (\d+)$#', $line)) {
        return $line;
    }

    if (preg_match('#^load (\d+)$#', $line, $match)) {
        $sourcep = $match[1];
        return [
            'pick',
            chickenasm_number_to_eggsembly($sourcep),
        ];
    }

    $mapping = [
        'exit'      => 'axe',
        'chicken'   => 'chicken',
        'add'       => 'add',
        'subtract'  => 'fox',
        'multiply'  => 'rooster',
        'compare'   => 'compare',
        'store'     => 'peck',
        'jump'      => 'fr',
        'char'      => 'bbq',
    ];

    if (isset($mapping[$line])) {
        return $mapping[$line];
    }

    throw new \InvalidArgumentException(sprintf("Did not recognize ChickenASM instruction '%s'", $line));
}

function chickenasm_number_to_eggsembly($n) {
    $mapping = [
        0 => 'axe',
        1 => 'chicken',
        2 => 'add',
        3 => 'fox',
        4 => 'rooster',
        5 => 'compare',
        6 => 'pick',
        7 => 'peck',
        8 => 'fr',
        9 => 'bbq',
    ];

    if (isset($mapping[$n])) {
        return $mapping[$n];
    }

    return 'push '.($n - 10);
}
