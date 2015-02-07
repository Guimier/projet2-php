<?php

class ExceptionPage extends Page {

	/** Exception to disaplay.
	 * @type Exception
	 */
	private $exception;

	/** Constructor.
	 * @param Exception $exception The Exception to display.
	 */
	public function __construct( array $config, array $params, Exception $exception) {
		parent::__construct( $config, $params );
		$this->exception = $exception;
	}

	protected function getTitle() {
		return 'Une erreur est survenue';
	}

	protected function getcontent() {
		return 'Une erreur est survenue&nbsp;:<pre class="error">'
			. $this->escape( $this->exception->getMessage() )
			. '</pre>';
	}
}
