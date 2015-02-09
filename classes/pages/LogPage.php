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
	 * @param mixed $context Context (highlighted), see Account::inContext.
	 */
	private function buildAccount( Account $account, $context ) {
		$escaped = $this->escape( $account->getShortName( $this->config['domain'] ) );
		return $account->inContext( $context ) ? "<strong>$escaped</strong>" : $escaped;
	}

	/** Build a call log.
	 * @param CallList $log Log to build.
	 * @param mixed $context Context, see Account::inContext.
	 */
	protected function buildCallLog( CallList $log, $context ) {
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
			$status = $call->getStatus( $context );
			
			$res .= "<tr class=\"$status\">";
			$res .= $this->buildTableCell( $this->statuses[$status] );
			$res .= $this->buildTableCell( strftime( $this->config['dateformat'], $call->getStartTime() ) );
			$res .= $this->buildTableCell( $call->getDuration() . ' s' );
			$res .= '<td>' . $this->buildAccount( $call->getCaller(), $context ) . '</td>';
			$res .= '<td>' . $this->buildAccount( $call->getCallee(), $context ) . '</td>';
			$res .= "</tr>";
		}
		
		return $res . <<<HTML
	</tbody>
</table>
HTML
		;
	}
}
