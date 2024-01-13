<?php
function exists($array, $callback)
{
    foreach ($array as $e) {
        if ($callback($e)) {
            return true;
        }
    }
    return false;
}