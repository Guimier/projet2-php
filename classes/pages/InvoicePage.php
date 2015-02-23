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

	/** Build the content. */
	protected function build() {
		/* Get call log */
		$year = (int) $this->getParam( 'year' );
		$month = (int) $this->getParam( 'month' );
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		
		$this->group = $this->getParam( 'group' );
		
		/* Get group */
		$this->context = new AccountGroup( $this->config, $this->group );

		$log = new FilteredCallList(
			$fullLog,
			FilteredCallList::filterByCaller( $this->context  )
		);
		
		$filters = new PriceFilters( $this->config, $this->context->getId() );

		$this->invoice = $filters->filterByCallee( $log );
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
			$duration = $group['list']->getTotalDuration();
			$local = $duration * $group['price'] / 3600;
			$local = number_format( $local, 2 );	
			$res .= '<tr>';
			$res .= '<th scope="row">' . $this->escape( $group['label'] ) . '</th>';
			$res .= $this->buildTableCell( $duration . ' s' );
			$res .= $this->buildTableCell( number_format( $group['price'], 2 ) );
	
			$res .= $this->buildTableCell( $local . ' ' . $this->config['currency'] );
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
			<td>$total $currency</td>
		</tr>
	</tfoot>
</table>
HTML
		;

		return $res;
	}
}
