<?php

function input_post ($name, $alt = null) {
    if (isset($_POST[$name])) return $_POST[$name];
    return $alt;
}

function input_get ($name, $alt = null) {
    if (isset($_GET[$name])) return $_GET[$name];
    return $alt;
}