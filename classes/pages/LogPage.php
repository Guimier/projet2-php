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

	/** Build HTML representation of an account.
	 * @param Account $account The account to display.
	 * @param array $context List of context account (will be highlighted).
	 */
	private function buildAccount( Account $account, array $context ) {
		$escaped = $this->escape( $account->getShortName( $this->config['domain'] ) );
		return in_array( $account, $context ) ? "<strong>$escaped</strong>" : $escaped;
	}

	/** Build a call log.
	 * @param array $log Log to build.
	 * @param array $accounts Context account.
	 */
	protected function buildCallLog( array $log, array $accounts ) {
		$res = <<<HTML
<table class="calllog">
	<thead>
		<tr>
			<th scope="col" class="hiddencell">Statut</th>
			<th scope="col">Date</th>
			<th scope="col">Durée</th>
			<th scope="col">Appelant</th>
			<th scope="col">Appelé</th>
		</tr>
	</thead>
	<tbody>
HTML
		;
		
		foreach ( $log as $call ) {
			$status = $call->getStatus( $accounts );
			
			$res .= "<tr class=\"$status\">";
			$res .= $this->buildTableCell( $this->statuses[$status] );
			$res .= $this->buildTableCell( strftime( $this->config['dateformat'], $call->getStartTime() ) );
			$res .= $this->buildTableCell( $call->getDuration() . ' s' );
			$res .= '<td>' . $this->buildAccount( $call->getCaller(), $accounts ) . '</td>';
			$res .= '<td>' . $this->buildAccount( $call->getCallee(), $accounts ) . '</td>';
			$res .= "</tr>";
		}
		
		return $res . <<<HTML
	</tbody>
</table>
HTML
		;
	}
}
