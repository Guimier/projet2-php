<?php

/** Page showing the call log for a group. */
class GroupLogPage extends LogPage {

	/** Group label.
	 * @type string.
	 */
	private $groupLabel;
	
	/** Log to display.
	 * @type array
	 */
	private $log;
	
	/** Context
	 * @see Account::inContext.
	 * @type mixed
	 */
	private $context;

	/** Build the form giving access to this page.
	 * @param array $groups Groups as defined in the configuration.
	 */
	public static function buildAccessForm( array $config ) {
		return self::buildForm(
			'group',
			'Voir le journal d’un groupe',
			'Voir',
			self::buildGroupSelect( $config )
				. self::buildYearSelect() . self::buildMonthSelect()
		);
	}

	/* Build the content. */
	protected function build() {
		$group = $this->getParam( 'group' );
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$this->groupLabel = $this->config['groups'][$group]['name'];
		
		$this->context = Account::getGroup(
			$this->config['groups'][$group],
			$this->config['domain']
		);
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$this->log = new FilteredCallList(
			$fullLog,
			FilteredCallList::filterByContext( $this->context )
		);
	}

	/* Get the page title. */
	protected function getTitle() {
		return 'Journal : ' . $this->groupLabel;
	}

	/** Get the main content. */
	protected function getcontent() {
		return $this->buildCallLog( $this->log, $this->context );
	}
}
