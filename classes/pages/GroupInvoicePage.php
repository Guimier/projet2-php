<?php

/** Page showing the invoice for a group. */
class GroupInvoicePage extends InvoicePage {

	/** Build the form giving access to this page.
	 * @param array $groups Groups as defined in the configuration.
	 */
	public static function buildAccessForm( array $groups ) {
		return self::buildForm(
			'invoice',
			'Voir la facture d’un groupe',
			'Voir',
			self::buildGroupSelect( $groups )
				. self::buildYearMonthSelect()
		);
	}

	/** Group
	 * @type string
	 */
	private $group;
	
	/** Get the context. */
	protected function getContext() {
		return $this->group;
	}
	
	/** Get the price filter for this account. */
	protected function getPriceFilter() {
		return new PriceFilters( $this->config, $this->group->getId() );
	}

	/** Build the content. */
	protected function build() {
		$this->group = new AccountGroup( $this->config, $this->getParam( 'group' ) );
		parent::build();
	}
}
