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
	path( 'classes/calls' ),
	path( 'classes/accounts' )
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

if ( is_null( $config ) ) {
	require path( 'html/configerror.html' );
}

/*----- Output -----*/

$pageClass = 'IndexPage';

$pageClasses = array(
	'log' => 'AccountLogPage',
	'global' => 'GlobalLogPage',
	'group' => 'GroupLogPage',
	'invoice' => 'GroupInvoicePage',
	'acctinvoice' => 'AccountInvoicePage'
);

if ( array_key_exists( 'page', $_GET ) && array_key_exists( $_GET['page'], $pageClasses ) ) {
	$pageClass = $pageClasses[ $_GET['page'] ];
}

$page = new $pageClass( $config, $_GET );
$page->display();

