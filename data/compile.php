<?php

// compile commands to a single file

$commands = require dirname(__FILE__) . '/base.php';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));

foreach ($it as $file) {
    if (substr($file, -4) !== '.tex') {
        continue;
    }

    // command mode is stored in file name
    if (!preg_match('#^(?P<mode>both|math|text)#', basename($file), $match)) {
        continue;
    }

    $fileCommands = array_filter(array_map(function ($str) {
        $str = preg_replace('/%.*/', '', $str);
        $str = trim($str);
        return $str;
    }, file($file)), 'strlen');
    $mode = $match['mode'];

    foreach ($fileCommands as $command) {
        // extract command name and number of args
        if (!preg_match('#^(?P<command>\\\\([a-zA-Z]+|[^a-zA-Z]| ))#', $command, $match)) {
            throw new Exception(
                sprintf("File %s contains invalid command name '%s'", $file, $command)
            );
        }

        $name = $match['command'];

        // in case [ or { is part of command name, search for substrings in part
        // of command after its name
        $numArgs = substr_count(substr($command, strlen($name)), '{');
        $numOptArgs = substr_count(substr($command, strlen($name)), '[');

        if (isset($commands[$name])) {
            $c = $commands[$name];
            if ($c['numArgs'] !== $numArgs || $c['numOptArgs'] !== $numOptArgs) {
                throw new Exception(
                    sprintf('File %s contains conflicting definition of command %s', $file, $name)
                );
            }
        }

        if (isset($commands[$name])) {
            if ($commands[$name]['numArgs'] !== $numArgs) {
                throw new Exception(sprintf(
                    'Duplicate definition of %s, conflicting number of arguments %d vs %d',
                    $name, $commands[$name]['numArgs'], $numArgs
                ));
            }
            if ($commands[$name]['numOptArgs'] !== $numOptArgs) {
                throw new Exception(sprintf(
                    'Duplicate definition of %s, conflicting number of optional arguments %d vs %d',
                    $name, $commands[$name]['numOptArgs'], $numOptArgs
                ));
            }
            if ($commands[$name]['mode'] !== 'both' && $commands[$name]['mode'] !== $mode) {
                $commands[$name]['mode'] = 'both';
            }
        } else {
            $commands[$name]['mode'] = $mode;
            $commands[$name]['numArgs'] = $numArgs;
            $commands[$name]['numOptArgs'] = $numOptArgs;
        }
    }
}

uksort($commands, function ($a, $b) {
    // strip leading backslash
    $a = substr($a, 1);
    $b = substr($b, 1);

    $casecmp = strcasecmp($a, $b);
    if (!$casecmp) {
        return strcmp($a, $b);
    }

    return $casecmp;
});

$php = str_replace('  ', '    ', var_export($commands, true));
$php = preg_replace('#\s+=>\s+array \(#', " => array(", $php);

file_put_contents(dirname(__FILE__) . '/../library/PhpLatex/commands.php', '<?php return ' . $php . ";\n");

