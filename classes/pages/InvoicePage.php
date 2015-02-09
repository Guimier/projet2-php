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

	/** Group for which the invoice will be displayed.
	 * @param string
	 */
	private $group;

	/** Invoice.
	 * @type array
	 */
	private $invoice;

	/** Apply the prices.
	 * @param CallList $log Log to split.
	 * @param array $prices Prices definition as in configuration.
	 */
	private function applyPrices( CallList $log, array $prices ) {
		$remaining = $log;
		$res = array();

		foreach ( $prices as $priceDef ) {
			$context = $priceDef[0];
			$price = $priceDef[1];

			if ( $context[0] === '*' ) {
				$res[] = array(
					'label' => 'Autres',
					'duration' => $remaining->getTotalDuration(),
					'price' => $price
				);
				/* Nothing left */
				$remaining = new CallList();
				break;
			} else {
				if ( $context[0] !== '@' ) {
					$label = $this->config['groups'][$context]['name'];
					$context = Account::getGroup(
						$this->config['groups'][$context],
						$this->config['domain']
					);
				} else {
					$label = substr( $context, 1 );
				}

				$partialLog = new FilteredCallList(
					$remaining,
					FilteredCallList::filterByCallee( $context )
				);
				$remaining = $partialLog->getFilteredOut();

				$res[] = array(
					'label' => $label,
					'duration' => $partialLog->getTotalDuration(),
					'price' => $price
				);
			}
		}

		if ( $remaining->getLength() > 0 ) {
			throw new Exception( 'Les appels ne correspondent pas tous aux filtres de prix.' );
		}

		return $res;
	}

	/** Build the content. */
	protected function build() {
		$year = (int) $this->getParam( 'year' );
		$month = (int) $this->getParam( 'month' );
		$this->group = $this->getParam( 'group' );

		$accounts = Account::getGroup(
			$this->config['groups'][$this->group],
			$this->config['domain']
		);
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		$log = new FilteredCallList(
			$fullLog,
			FilteredCallList::filterByCaller( $accounts )
		);
		
		$prices = $this->config['prices'];
		if ( isset( $this->config['groups'][$this->group]['prices'] ) ) {
			$prices = array_merge(
				$this->config['groups'][$this->group]['prices'],
				$prices
			);
		}

		$this->invoice = $this->applyPrices( $log, $prices );
	}

	/** Get the page title. */
	protected function getTitle() {
		return 'Facture : ' . $this->config['groups'][$this->group]['name'];
	}

	/** Get the main content. */
	protected function getcontent() {
		$currency = $this->escape( $this->config['currency'] );
		$res = '<!-- Groupe `' . $this->escape( $this->group ) . '` -->';
		$res .= <<<HTML
<table class="invoice">
	<thead>
		<tr>
			<th scope="col">Destination</th>
			<th scope="col">Temps</th>
			<th scope="col">Tarif ($currency/heure)</th>
			<th scope="col">Prix</th>
		</tr>
	</thead>
	<tbody>
HTML
		;

		$total = 0;

		foreach ( $this->invoice as $group ) {
			$local = $group['duration'] * $group['price'] / 3600;
			$res .= '<tr>';
			$res .= $this->buildTableCell( $group['label'] );
			$res .= $this->buildTableCell( $group['duration'] . ' s' );
			$res .= $this->buildTableCell( $group['price'] );
			$res .= $this->buildTableCell( $local . $this->config['currency'] );
			$res .= '</tr>';

			$total += $local;
		}

		$currency = $this->escape( $this->config['currency'] );

		$res .= <<<HTML
	</tbody>
	<tfoot>
		<tr>
			<td class="hiddencell"></td>
			<td class="hiddencell"></td>
			<td class="hiddencell"></td>
			<td>$total$currency</td>
		</tr>
	</tfoot>
</table>
HTML
		;

		return $res;
	}
}
