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
				. self::buildYearMonthSelect()
		);
	}

	/** Group for which the invoice will be displayed.
	 * @type AccountContext
	 */
	private $context;

	/** Invoice.
	 * @type array
	 */
	private $invoice;

	/** Get the AccountContext described in the configuration.
	 * @param string $rawContext Description of the context in the documenation.
	 * @return AccountContext
	 */
	private function getContext( $rawContext ) {
		if ( $rawContext === '*' ) {
			return new Universe();
		} elseif ( $rawContext[0] === '@' ) {
			return new AccountDomain( substr( $rawContext, 1 ) );
		} else {
			return new AccountGroup( $this->config, $rawContext );
		}
	}

	/** Apply the prices.
	 * @param CallList $log Log to split.
	 * @param array $prices Prices definition as in configuration.
	 */
	private function applyPrices( CallList $log, array $prices ) {
		$remaining = $log;
		$res = array();

		$i = 0;
		while ( $i < count( $prices ) && $remaining->getLength() > 0 ) {
			$context = $this->getContext( $prices[$i][0] );

			$partialLog = new FilteredCallList(
				$remaining,
				FilteredCallList::filterByCallee( $context )
			);
			$remaining = $partialLog->getFilteredOut();

			$res[] = array(
				'label' => $context->getDescription(),
				'duration' => $partialLog->getTotalDuration(),
				'price' => $prices[$i][1]
			);
			
			++$i;
		}

		if ( $remaining->getLength() > 0 ) {
			throw new Exception( 'Les appels ne correspondent pas tous aux filtres de prix.' );
		}

		return $res;
	}

	/** Build the content. */
	protected function build() {
		/* Get call log */
		$year = (int) $this->getParam( 'year' );
		$month = (int) $this->getParam( 'month' );
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		
		/* Get group */
		$this->context = new AccountGroup( $this->config, $this->getParam( 'group' ) );

		$log = new FilteredCallList(
			$fullLog,
			FilteredCallList::filterByCaller( $this->context  )
		);
		
		$prices = $this->config['prices'];
		if ( isset( $this->config['groups'][$this->context->getId()]['prices'] ) ) {
			$prices = array_merge(
				$this->config['groups'][$this->context->getId()]['prices'],
				$prices
			);
		}

		$this->invoice = $this->applyPrices( $log, $prices );
	}

	/** Get the page title. */
	protected function getTitle() {
		return 'Facture - ' . $this->context->getDescription();
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
			$res .= '<th scope="row">' . $this->escape( $group['label'] ) . '</th>';
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
