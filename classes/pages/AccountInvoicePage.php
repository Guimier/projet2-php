<?php

/** Page showing the invoice for an account. */
class AccountInvoicePage extends InvoicePage {

	/** Build the form giving access to this page. */
	public static function buildAccessForm() {
		return self::buildForm(
			'acctinvoice',
			'Voir la facture d’un appareil',
			'Voir',
			self::buildInput( 'text', 'account', 'Compte' )
				. self::buildYearMonthSelect()
		);
	}
	
	/** Account
	 * @type Account
	 */
	private $account;
	
	/** Get the context. */
	protected function getContext() {
		return $this->account;
	}
	
	/** Get the price filter for this account. */
	protected function getPriceFilter() {
		return PriceFilters::getForAccount( $this->config, $this->account );
	}
	
	/** Build the content. */
	protected function build() {
		$this->account = Account::get(
			$this->getParam( 'account' ) . '@' . $this->config['domain']
		);
		parent::build();
	}
}
