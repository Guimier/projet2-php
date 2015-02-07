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
			$res .= $this->buildTableCell( $call->getCaller()->getShortName( $this->config['domain'] ) );
			$res .= $this->buildTableCell( $call->getCallee()->getShortName( $this->config['domain'] ) );
			$res .= "</tr>";
		}
		
		return $res . <<<HTML
	</tbody>
</table>
HTML
		;
	}
}
