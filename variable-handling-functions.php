<?php

/**
 * Prints human-readable information about a variable in browser
 *
 * @param mixed $value
 * 
 * @return void
 */
function print_r_browser($value): void
{
    echo '<pre>';
    print_r($value);
    echo  '</pre>';
}

/**
 * Checks whether variable names are set within the global space or they exists as an key and return if they are set (even if their values are null)
 * 
 * @param string $var_name name of the first variable to check
 * @param mixed
 *   optional array to check for key (if null, checks from $GLOBALS) OR
 *   other variable names to check OR
 *   other variable names and their associated arrays to their right (use null for global variables, optional if its the last parameter)
 */
function is_set(string $var_name, mixed ...$args): bool
{
    $vars[$var_name] = null;

    if (array_key_exists(0, $args)) {
        if (is_array($args[0])) {
            $vars[$var_name] = $args[0];
        } elseif (is_string($args[0])) {
            goto main;
        }

        unset($args[0]);
    }

    main:

    if ($args) {
        $args = array_reverse($args);
        $cur_array = null;

        array_walk($args, function ($value) use (&$cur_array, &$vars): void {
            if (!is_string($value)) {
                $cur_array = $value;
            } else {
                $vars[$value] = $cur_array;
            }
        });
    }

    foreach ($vars as $name => $array) {
        if (!array_key_exists($name, $array ?? $GLOBALS)) return false;
    }

    return true;
}

/**
 * Check if an array has some keys
 *
 * @return bool
 */
function isset_array(): bool
{
    if (func_num_args() < 2) {
        return true;
    }

    $args = func_get_args();
    $array = array_shift($args);

    if (!is_array($array)) {
        return false;
    }

    foreach ($args as $n) {
        if (!isset($array[$n])) {
            return false;
        }
    }

    return true;
}

/**
 * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
 *
 * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
 * @link https://www.php.net/manual/en/function.var-export.php
 */
/**
 * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
 * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
 * @link https://www.php.net/manual/en/function.var-export.php
 *
 * @param mixed $expression
 * @param bool $return
 * 
 * @return null|string
 */
function var_export_short(mixed $expression, bool $return = false): ?string
{
    $export = var_export($expression, true);
    $patterns = [
        "/array \(/" => '[',
        "/^([ ]*)\)(,?)$/m" => '$1]$2',
        "/=>[ ]?\n[ ]+\[/" => '=> [',
        "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
    ];

    $export = preg_replace(array_keys($patterns), array_values($patterns), $export);

    if ((bool)$return) {
        return $export;
    }

    echo $export;
    return null;
}

/**
 * Dump a human readable value
 *
 * @param mixed $value
 * @param string $var_name
 * @param string $reference
 * @param string $method
 * @param bool $sub
 * 
 * @return mixed
 */
function var_log(mixed $value, string $var_name = '', string $reference = '', string $method = '=', bool $sub = false): mixed
{
    static $output;
    static $depth;

    if ($sub == false) {
        $output = '';
        $depth = 0;
        $reference = $var_name;
        $var = serialize($value);
        $var = unserialize($var);
    } else {
        ++$depth;
        $var = &$value;
    }

    // constants
    $nl = "\n";
    $block = 'a_big_recursion_protection_block';

    $c = $depth;
    $indent = '';
    while ($c-- > 0) {
        $indent .= '|  ';
    }

    // if this has been parsed before
    if (is_array($var) && isset($var[$block])) {

        $real = &$var[$block];
        $name = &$var['name'];
        $type = gettype($real);
        $output .= $indent . $var_name . ' ' . $method . '& ' . ($type == 'array' ? 'Array' : get_class($real)) . ' ' . $name . $nl;

        // havent parsed this before
    } else {

        // insert recursion blocker
        $var = array($block => $var, 'name' => $reference);
        $theVar = &$var[$block];

        // print it out
        $type = gettype($theVar);
        switch ($type) {

            case 'array':
                $output .= $indent . $var_name . ' ' . $method . ' Array (' . $nl;
                $keys = array_keys($theVar);
                foreach ($keys as $name) {
                    $value = &$theVar[$name];
                    var_log($value, $name, $reference . '["' . $name . '"]', '=', true);
                }
                $output .= $indent . ')' . $nl;
                break;

            case 'object':
                $output .= $indent . $var_name . ' = ' . get_class($theVar) . ' {' . $nl;
                foreach ($theVar as $name => $value) {
                    var_log($value, $name, $reference . '->' . $name, '->', true);
                }
                $output .= $indent . '}' . $nl;
                break;

            case 'string':
                $output .= $indent . $var_name . ' ' . $method . ' "' . $theVar . '"' . $nl;
                break;

            default:
                $output .= $indent . $var_name . ' ' . $method . ' (' . $type . ') ' . $theVar . $nl;
                break;
        }

        // $var=$var[$block];

    }

    --$depth;

    if ($sub == false)
        return $output;
}

/**
 * Better GI than print_r or var_dump -- but, unlike var_dump, you can only dump one variable.  
 * Added htmlentities on the var content before echo, so you see what is really there, and not the mark-up.
 * 
 * Also, now the output is encased within a div block that sets the background color, font style, and left-justifies it
 * so it is not at the mercy of ambient styles.
 *
 * Inspired from:     PHP.net Contributions
 * Stolen from:       [highstrike at gmail dot com]
 * Modified by:       stlawson *AT* JoyfulEarthTech *DOT* com 
 *
 * @param mixed $var  -- variable to dump
 * @param null|string $var_name  -- name of variable (optional) -- displayed in printout making it easier to sort out what variable is what in a complex output
 * @param null|string $indent -- used by internal recursive call (no known external value)
 * @param null|string $reference -- used by internal recursive call (no known external value)
 * 
 * @return void
 */
function html_dump(mixed $var, ?string $var_name = null, ?string $indent = null, ?string $reference = null): void
{
    global $argv;

    if (isset($argv)) {
        trigger_error("html_dump() is used only in browser", E_USER_ERROR);
    }

    $do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference . $var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme';
    $keyname = 'referenced_object_name';

    // So this is always visible and always left justified and readable
    echo "<div style='text-align:left; background-color:white; font: 100% monospace; color:black;'>";

    if (is_array($var) && isset($var[$keyvar])) {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    } else {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];

        $type = ucfirst(gettype($avar));
        if ($type == "String") $type_color = "<span style='color:green'>";
        elseif ($type == "Integer") $type_color = "<span style='color:red'>";
        elseif ($type == "Double") {
            $type_color = "<span style='color:#0099c5'>";
            $type = "Float";
        } elseif ($type == "Boolean") $type_color = "<span style='color:#92008d'>";
        elseif ($type == "NULL") $type_color = "<span style='color:black'>";

        if (is_array($avar)) {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
            $keys = array_keys($avar);
            foreach ($keys as $name) {
                $value = &$avar[$name];
                html_dump($value, "['$name']", $indent . $do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        } elseif (is_object($avar)) {
            echo "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
            foreach ($avar as $name => $value) html_dump($value, "$name", $indent . $do_dump_indent, $reference);
            echo "$indent)<br>";
        } elseif (is_int($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
        elseif (is_string($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color\"" . htmlentities($avar) . "\"</span><br>";
        elseif (is_float($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
        elseif (is_bool($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
        elseif (is_null($avar)) echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen('') . ")</span> {$type_color}NULL</span><br>";
        else echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> " . htmlentities($avar) . "<br>";

        $var = $var[$keyvar];
    }

    echo "</div>";
}
