<?php

/** Page showing the invoice for a group. */
class InvoicePage extends Page {

	/** Build the form giving access to this page.
	 * @param array $groups Groups as defined in the configuration.
	 */
	public static function buildAccessForm( array $groups ) {
		return self::buildForm(
			'invoice',
			'Voir la facture d’un groupe',
			'Voir',
			self::buildGroupSelect( $groups )
				. self::buildYearSelect() . self::buildMonthSelect()
		);
	}

	/** Get the page title. */
	protected function getTitle() {
		return 'Facture';
	}

	/** Get the main content. */
	protected function getcontent() {
		return 'TODO';
	}
}
