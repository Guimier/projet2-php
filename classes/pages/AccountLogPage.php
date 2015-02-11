<?php

/** Page showing the call log for an account. */
class AccountLogPage extends LogPage {

	/** Build the form giving access to this page. */
	public static function buildAccessForm() {
		return self::buildForm(
			'log',
			'Voir le journal d’un appareil',
			'Voir',
			self::buildInput( 'text', 'account', 'Compte' )
				. self::buildYearMonthSelect()
		);
	}

	/* Build the content. */
	protected function build() {
		$account = Account::get( $this->getParam( 'account' ) . '@' . $this->config['domain'] );
		
		$this->prepareLog( $account, 'filterByContext' );
	}
}
