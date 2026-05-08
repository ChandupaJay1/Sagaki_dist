<?php

/**
 * Polyfill for ctype functions if the extension is not loaded.
 * This ensures Laravel 11 runs even on servers with limited PHP extensions.
 */

if (!function_exists('ctype_alnum')) {
    function ctype_alnum($text) {
        return preg_match('/^[a-z0-9]+$/iD', (string) $text) === 1;
    }
}

if (!function_exists('ctype_alpha')) {
    function ctype_alpha($text) {
        return preg_match('/^[a-z]+$/iD', (string) $text) === 1;
    }
}

if (!function_exists('ctype_digit')) {
    function ctype_digit($text) {
        return preg_match('/^[0-9]+$/D', (string) $text) === 1;
    }
}

if (!function_exists('ctype_lower')) {
    function ctype_lower($text) {
        return preg_match('/^[a-z]+$/D', (string) $text) === 1;
    }
}

if (!function_exists('ctype_upper')) {
    function ctype_upper($text) {
        return preg_match('/^[A-Z]+$/D', (string) $text) === 1;
    }
}

if (!function_exists('ctype_space')) {
    function ctype_space($text) {
        return preg_match('/^\s+$/D', (string) $text) === 1;
    }
}

if (!function_exists('ctype_xdigit')) {
    function ctype_xdigit($text) {
        return preg_match('/^[a-f0-9]+$/iD', (string) $text) === 1;
    }
}
