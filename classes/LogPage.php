<?php

class LogPage extends Page {

	private $account;
	private $log;

	public static function getAccessForm() {
		$months = array(
			1 => 'Janvier',
			2 => 'Février',
			3 => 'Mars',
			4 => 'Avril',
			5 => 'Mai',
			6 => 'Juin',
			7 => 'Juillet',
			8 => 'Août',
			9 => 'Septembre',
			10 => 'Octobre',
			11 => 'Novembre',
			12 => 'Décembre'
		);
		
		return self::buildForm(
			'log',
			'Voir le journal d’un appareil',
			'Voir',
			self::buildInput( 'text', 'account', 'Compte' )
				. self::buildSelect( array( 2015 => 2015 ), 'year', 'Année' )
				. self::buildSelect( $months, 'month', 'Mois' )
		);
	}

	protected function build() {
		$this->account = Account::get( $this->getParam( 'account' ) . '@' . $this->config['domain'] );
		$year   = (int) $this->getParam( 'year' );
		$month  = (int) $this->getParam( 'month' );
		
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$this->log = RadiusLog::filter( $fullLog, array( $this->account ) );
	}

	protected function getTitle() {
		return 'Gestionaire de factures téléphoniques';
	}

	protected function getcontent() {
		$res = <<<HTML
<table>
	<thead>
		<tr>
			<th scope="col">Date</th>
			<th scope="col">Durée</th>
			<th scope="col">Appelant</th>
			<th scope="col">Appelé</th>
		</tr>
	</thead>
	<tbody>
HTML
		;
		
		foreach ( $this->log as $call ) {
			if ( $call instanceof RadiusAvortedCall ) {
				$type = 'avorted';
			} else if ( $call->getCaller() === $this->account ) {
				$type = 'caller';
			} else  {
				$type = 'callee';
			}
			
			$res .= "<tr class=\"$type\">";
			$res .= '<td>' . strftime( $this->config['dateformat'], $call->getStartTime() ) . '</td>';
			$res .= '<td>' . $call->getDuration() . ' s</td>';
			$res .= '<td>' . $call->getCaller()->getShortName( $this->config['domain'] ) . '</td>';
			$res .= '<td>' . $call->getCallee()->getShortName( $this->config['domain'] ) . '</td>';
			$res .= "</tr>";
		}
		
		return $res . <<<HTML
	</tbody>
</table>
HTML
		;
	}
}
