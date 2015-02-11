<?php

/** Page showing the call log for a group. */
class GroupLogPage extends LogPage {

	/** Build the form giving access to this page.
	 * @param array $groups Groups as defined in the configuration.
	 */
	public static function buildAccessForm( array $groups ) {
		return self::buildForm(
			'group',
			'Voir le journal d’un groupe',
			'Voir',
			self::buildGroupSelect( $groups )
				. self::buildYearMonthSelect()
		);
	}

	/* Build the content. */
	protected function build() {
		$this->prepareLog(
			new AccountGroup( $this->config, $this->getParam( 'group' ) ),
			'filterByContext'
		);
	}
}
