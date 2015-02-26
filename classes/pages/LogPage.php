<?php

/** Page showing a call log. */
abstract class LogPage extends Page {

	/** Status texts
	 * @type array
	 */
	private $statuses = array(
		'avorted' => 'Avorté',
		'internal' => 'Interne',
		'caller' => 'Appelant',
		'callee' => 'Appelé'
	);

	/** Context.
	 * @type AccountContext
	 */
	private $context;
	
	/** Log to display.
	 * @type CallList
	 */
	private $log;

	/** Build a log.
	 * @param AccountContext $context Context
	 * @param string $filterMethod Method from FilteredCallList used to create a filter.
	 */
	protected function prepareLog( AccountContext $context, $filterMethod ) {
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$this->log = new FilteredCallList(
			$fullLog,
			FilteredCallList::$filterMethod( $context )
		);
		
		$this->context = $context;
	}

	/** Build HTML representation of an account.
	 * @param Account $account The account to display.
	 * @param AccountContext $context Context (highlighted).
	 */
	private function buildAccount( Account $account, AccountContext $context ) {
		$escaped = $this->escape( $account->getShortName( $this->config['domain'] ) );
		return $context->contains( $account ) ? "<strong>$escaped</strong>" : $escaped;
	}

	/** Build a call log.
	 * @param CallList $log Log to build.
	 * @param AccountContext $context Context (highlighted).
	 */
	protected function buildCallLog( CallList $log, AccountContext $context ) {
		if ( $log->getLength() > 0 ) {
			return $this->buildNonEmptyCallLog( $log, $context );
		} else {
			return '<p>Aucun appel durant la période sélectionnée.</p>';
		}
	}

	/** Build a call log.
	 * @param CallList $log Log to build.
	 * @param AccountContext $context Context (highlighted).
	 */
	private function buildNonEmptyCallLog(
		CallList $log,
		AccountContext $context
	) {
		$currency = $this->escape( $this->config['currency'] );
		
		$res = <<<HTML
<table class="calllog">
	<thead>
		<tr>
			<th scope="col" class="hiddencell">Statut</th>
			<th scope="col">Date</th>
			<th scope="col">Durée</th>
			<th scope="col">Appelant</th>
			<th scope="col">Appelé</th>
			<th scope="col">Coût<br/><span class="unit">($currency)</span></th>
		</tr>
	</thead>
	<tbody>
HTML
		;
		
		foreach ( $log as $call ) {
			$status = $call->getStatus( $context );
			
			$res .= "<tr class=\"$status\">";
			$res .= $this->buildTableCell( $this->statuses[$status] );
			$res .= $this->buildTableCell( strftime( $this->config['dateformat'], $call->getStartTime() ) );
			$res .= $this->buildTableCell( $this->formatDuration( $call->getDuration() ) );
			$res .= '<td>' . $this->buildAccount( $call->getCaller(), $context ) . '</td>';
			$res .= '<td>' . $this->buildAccount( $call->getCallee(), $context ) . '</td>';
			
			if ( $status === 'caller' || $status === 'internal' ) {
				$filter = PriceFilters::getForAccount( $this->config, $call->getCaller() );
				$filtered = $filter->filterAccount( $call->getCallee() );
				$price = $call->getDuration() * $filtered['price'] / 3600;
				$res .= $this->buildTableCell( number_format( $price, 2 ) );
			} else {
				$res .= '<td>—</td>';
			}
			$res .= "</tr>";
		}
		
		return $res . <<<HTML
	</tbody>
</table>
HTML
		;
	}

	/* Get the page title. */
	protected function getTitle() {
		return 'Journal d’appel - ' . $this->context->getDescription();
	}
	
	/** Get the main content. */
	protected function getcontent() {
		return $this->buildCallLog( $this->log, $this->context );
	}
}
