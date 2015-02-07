<?php

/** A web page. */
abstract class Page {

	/** Configuration.
	 * See /config.example.json for the structure.
	 * @type array
	 */
	protected $config;

	/** Web request parameters.
	 * @type array
	 */
	private $params;

	/** ExceptionPage to display in case of exception.
	 * @type ExceptionPage
	 */
	private $exceptionPage = null;

	/** Constructor.
	 * @param array $config Configuration.
	 * @param array $params Web request parameters.
	 */
	public function __construct( array $config, array $params ) {
		$this->config = $config;
		$this->params = $params;
		try {
			$this->build();
		} catch ( Exception $e ) {
			$this->exceptionPage = new ExceptionPage( $config, $params, $e );
		}
	}

	/** Get a required parameter.
	 * @param string $name Name of the parameter.
	 */
	final public function getParam( $name ) {
		if ( ! array_key_exists( $name, $this->params ) ) {
			throw new Exception( "Paramètre « $name » requis" );
		}
		
		return $this->params[$name];
	}

	/* Build the content.
	 * Children may override this to define dynamic content.
	 * This method may throw an exception, which will be displayed.
	 */
	protected function build() {}

	/** Get the page title.
	 * @return string The title.
	 */
	abstract protected function getTitle();

	/** Get the main content.
	 * @return string The HTML string representing the main content of the page.
	 */
	abstract protected function getContent();

	/** Output the HTTP/HTML document. */
	final public function display() {
		if ( is_null( $this->exceptionPage ) ) {
			header( 'Content-Type: text/html; charset=UTF-8' );
			require 'template.php';
		} else {
			$this->exceptionPage->display();
		}
	}
	
/*----- HTML building -----*/

	public static function escape( $text ) {
		return htmlspecialchars( $text );
	}
	
	public function buildTableCell( $text ) {
		return '<td>' . self::escape( $text ) . '</td>';
	}
	
/*----- Form building -----*/
	
	protected static function buildForm( $page, $title, $submit, $inputs ) {
		return <<<HTML
<div class="form">
	<h2>$title</h2>
	<form action="index.php" method="GET">
		<input type="hidden" name="page" value="$page" />
		$inputs
		<input type="submit" value="$submit" />
	</form>
</div>
HTML
		;
	}

	protected static function buildInput( $type, $name, $label ) {
		return <<<HTML
<label for="$name">$label</label>
<input type="$type" name="$name" id="$name" />
HTML
		;
	}
	
	protected static function buildSelect( $values, $name, $label ) {
		$options = '';
		
		foreach ( $values as $id => $display ) {
			$options .= "<option value=\"$id\">$display</option>";
		}
		
		return <<<HTML
<label for="$name">$label</label>
<select name="$name" id="$name">
$options
</select>
HTML
		;
	}
	
}
