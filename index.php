<?php

set_include_path( __DIR__ . '/classes' );

function __autoload( $class ) {
	require_once "$class.php";
}

$config = json_decode( file_get_contents( __DIR__ . '/config.json' ), true );
setlocale( LC_ALL, $config['locale'] );
setlocale( LC_ALL, 'fr_FR' );

$pageClass = 'IndexPage';

if ( array_key_exists( 'page', $_GET ) ) {
	switch ( $_GET['page'] ) {
		case 'log':
			$pageClass = 'LogPage';
			break;
		case 'invoice':
			$pageClass = 'InvoicePage';
			break;
	}
}

$page = new $pageClass( $config, $_GET );
$page->display();

