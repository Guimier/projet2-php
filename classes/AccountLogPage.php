<?php

class AccountLogPage extends LogPage {

	private $account;
	private $log;

	public static function getAccessForm() {
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

	protected function build() {
		$this->account = Account::get( $this->getParam( 'account' ) . '@' . $this->config['domain'] );
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$this->log = RadiusLog::filter( $fullLog, array( $this->account ) );
	}

	protected function getTitle() {
		return 'Journal d’appel de ' . $this->account->getShortName( $this->config['domain'] );
	}

	protected function getcontent() {
		return $this->buildCallLog( $this->log, array( $this->account ) );
	}
}
