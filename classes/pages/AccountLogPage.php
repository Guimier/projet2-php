<?php

/** Page showing the call log for an account. */
class AccountLogPage extends LogPage {

	/** Account for which the log is shown.
	 * @type Account.
	 */
	private $account;
	
	/** Log to display.
	 * @type array
	 */
	private $log;

	/** Build the form giving access to this page. */
	public static function buildAccessForm() {
		$months = array(
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
		);
		
		return self::buildForm(
			'log',
			'Voir le journal d’un appareil',
			'Voir',
			self::buildInput( 'text', 'account', 'Compte' )
				. self::buildSelect( array( 2015 => 2015 ), 'year', 'Année' )
				. self::buildSelect( $months, 'month', 'Mois' )
		);
	}

	/* Build the content. */
	protected function build() {
		$this->account = Account::get( $this->getParam( 'account' ) . '@' . $this->config['domain'] );
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$this->log = RadiusLog::filter( $fullLog, array( $this->account ) );
	}

	/* Get the page title. */
	protected function getTitle() {
		return 'Journal d’appel de ' . $this->account->getShortName( $this->config['domain'] );
	}

	/** Get the main content. */
	protected function getcontent() {
		return $this->buildCallLog( $this->log, array( $this->account ) );
	}
}
