<?php

/*----- Tools -----*/

/** Get the absolute path of a file in the file system.
 * @param string $relative Relative path of the file from the project root directory.
 */
function path( $relative ) {
	return __DIR__ . '/' . $relative;
}

/* Add classes paths. */
set_include_path( implode( ':', array(
	path( 'classes' ),
	path( 'classes/pages' ),
	path( 'classes/call' )
) ) );

/** Class autoloader.
 * @see http://php.net/manual/en/function.autoload.php
 * @param $class Class to load.
 */
function __autoload( $class ) {
	require_once "$class.php";
}

/*----- Configuration -----*/

$config = json_decode( file_get_contents( __DIR__ . '/config.json' ), true );

/*----- Output -----*/

$pageClass = 'IndexPage';

if ( array_key_exists( 'page', $_GET ) ) {
	switch ( $_GET['page'] ) {
		case 'log':
			$pageClass = 'AccountLogPage';
			break;
		case 'invoice':
			$pageClass = 'InvoicePage';
			break;
	}
}

$page = new $pageClass( $config, $_GET );
$page->display();

