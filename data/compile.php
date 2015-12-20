<?php

// compile commands to a single file

$commands = require dirname(__FILE__) . '/base.php';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));

foreach ($it as $file) {
    if (substr($file, -4) !== '.txt') {
        continue;
    }

    $fileCommands = array_filter(array_map('trim', file($file)), 'strlen');

    // command mode is stored in file name
    if (!preg_match('#^(?P<mode>both|math|text)#', basename($file), $match)) {
        continue;
    }
    $mode = $match['mode'];

    foreach ($fileCommands as $command) {
        // extract command name and number of args

        if (!preg_match('#^(?P<command>\\\\([a-zA-Z]+|[^a-zA-Z]))#', $command, $match)) {
            throw new Exception(
                sprintf('File %s contains invalid command name %s', $file, $command)
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

        $commands[$name]['mode'] = $mode;
        $commands[$name]['numArgs'] = $numArgs;
        $commands[$name]['numOptArgs'] = $numOptArgs;
    }
}

uksort($commands, 'strcasecmp');

$php = str_replace('  ', '    ', var_export($commands, true));
$php = preg_replace('#\s+=>\s+array \(#', " => array(", $php);

file_put_contents(dirname(__FILE__) . '/../library/PhpLatex/commands.php', '<?php return ' . $php . ";\n");

