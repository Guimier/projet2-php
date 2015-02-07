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

	/** Escape a string for inclusion in HTML.
	 * @param string $text The string to escape.
	 */
	protected static function escape( $text ) {
		return htmlspecialchars( $text );
	}
	
	/** Build a table cell.
	 * @param string $text The content (will be escaped).
	 */
	protected static function buildTableCell( $text ) {
		return '<td>' . self::escape( $text ) . '</td>';
	}
	
/*----- Form building -----*/
	
	/** Build a form.
	 * @param string $page Identifiant of the page the forms gives acces to.
	 * @param string $title Title of the form.
	 * @param string $submit Text of the submit button.
	 * @param string $inputs HTML string containing inputs.
	 */
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

	/** Build an input with a label.
	 * @param string $type Input type.
	 * @param string $name Input name (use for the identifiant too).
	 * @param string $label Label for the input.
	 */
	protected static function buildInput( $type, $name, $label ) {
		return <<<HTML
<label for="$name">$label</label>
<input type="$type" name="$name" id="$name" />
HTML
		;
	}
	

	/** Build a select with a label.
	 * @param array $values Associative array of options. Array keys are the actul values,
	 *        array values are the displayed texts.
	 * @param string $name Select name (use for the identifiant too).
	 * @param string $label Label for the select.
	 */
	protected static function buildSelect( array $values, $name, $label ) {
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
