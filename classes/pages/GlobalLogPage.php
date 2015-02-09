<?php

/** Page showing the call log for an account. */
class GlobalLogPage extends LogPage {

	/** Log to display.
	 * @type array
	 */
	private $log;

	/** Build the form giving access to this page. */
	public static function buildAccessForm() {
		return self::buildForm(
			'global',
			'Voir le journal global',
			'Voir',
			self::buildYearSelect() . self::buildMonthSelect()
		);
	}

	/* Build the content. */
	protected function build() {
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$rl = new RadiusLog( $this->config['logsdir'] );
		$this->log = $rl->getMonthCalls( $year, $month );
	}

	/* Get the page title. */
	protected function getTitle() {
		return 'Journal d’appel global';
	}

	/** Get the main content. */
	protected function getcontent() {
		return $this->buildCallLog( $this->log, '@' . $this->config['domain'] );
	}
}
