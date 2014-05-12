#!/usr/bin/php
<?php
/**
 * INI File Format Parser.
 *
 * This script will parse any regular INI file, and complain if there are 
 * format errors. I wrote it to find incorrectly formatted language files 
 * in my Joomla installation. It is very basic for now, but I could not 
 * find any other parsers, and I expect to extend it with more rules, as 
 * I discover parse errors not covered by this check.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Jacob V. Rasmussen <jvr@glokal-marketing.dk>
 * @copyright  2014 GLOKAL-Marketing
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.0
 * @link       https://github.com/blackthornedk/iniparser
 */

if ($argc < 2) {
	die("Usage: {$argv[0]} <ini file>\n");
}

if (!is_file($argv[1])) {
	die("File: {$argv[1]} was not found.");
}
if (!is_readable($argv[1])) {
	die("File {$argv[1]} could not be opened.");
}

$file = file_get_contents($argv[1]);

$lines = explode("\n", $file);
$line_num = 0;
foreach ($lines as $line) {
	$line_num++;
	if (strpos(trim($line), ';') == 0 || strpos($line, '=') === FALSE) {
		continue;
	}
	$key = trim(substr($line, 0, strpos($line, '=')));
	$value = trim(substr($line, strpos($line, '=') + 1));
	if (preg_match('/[^A-Z0-9_-]/', $key)) {
		echo "{$argv[1]}:{$line_num}:Invalid key: {$key}\n";
	}
	if (substr($value, 0, 1) != '"' || substr($value, -1) != '"') {
		echo "{$argv[1]}:{$line_num}:Invalid value: {$value}\n";
	}
	if ($argc >= 3 && $argv[2] == "strict") {
		if (preg_match_all('/"/', $value) != 2) {
			echo "{$argv[1]}:{$line_num}:Warning: Embedded quotes: {$value}\n";
		}
	}
}

