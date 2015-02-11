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
			require path( 'html/template.php' );
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
	
	/** Build a selector with a label.
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
	
	/** Build a month selector. */
	protected static function buildMonthSelect() {
		return self::buildSelect(
			array(
				1 => 'Janvier',
				2 => 'Février',
				3 => 'Mars',
				4 => 'Avril',
				5 => 'Mai',
				6 => 'Juin',
				7 => 'Juillet',
				8 => 'Août',
				9 => 'Septembre',
				10 => 'Octobre',
				11 => 'Novembre',
				12 => 'Décembre'
			),
			'month',
			'Mois'
		);
	}
	
	/** Build a year selector. */
	protected static function buildYearSelect() {
		return self::buildSelect( array( 2015 => 2015 ), 'year', 'Année' );
	}
	
	/** Build a year and month selector. */
	protected static function buildYearMonthSelect() {
		return self::buildYearSelect() . self::buildMonthSelect();
	}

	/** Build a group selector.
	 * @param array $groups Groups as defined in the configuration.
	 */
	protected function buildGroupSelect( array $groups ) {
		$values = array_map(
			function ( $group ) {
				return $group['name'];
			},
			$groups
		);

		return self::buildSelect( $values, 'group', 'Groupe' );
	}
	
}
