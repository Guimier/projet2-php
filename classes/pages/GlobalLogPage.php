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
		$this->prepareLog(
			new DomainAccountContext( $this->config['domain'] ),
			'filterByContext'
		);
	}
}
