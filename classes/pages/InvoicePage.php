<?php

/** Page showing the invoice for a group. */
abstract class InvoicePage extends Page {

	/** Context for which the invoice will be displayed.
	 * @type AccountContext
	 */
	private $context;

	/** Invoice.
	 * @type array
	 */
	private $invoice;

	abstract protected function getPriceFilter();
	abstract protected function getContext();

	/** Build the content. */
	protected function build() {
		/* Get call log */
		$year = (int) $this->getParam( 'year' );
		$month = (int) $this->getParam( 'month' );
		$rl = new RadiusLog( $this->config['logsdir'] );
		$fullLog = $rl->getMonthCalls( $year, $month );
		
		$this->context = $this->getContext();

		$log = new FilteredCallList(
			$fullLog,
			FilteredCallList::filterByCaller( $this->context  )
		);

		$this->invoice = $this->getPriceFilter()->filterByCallee( $log );
	}

	/** Get the page title. */
	protected function getTitle() {
		return 'Facture - ' . $this->context->getDescription();
	}

	/** Get the main content. */
	protected function getcontent() {
		$currency = $this->escape( $this->config['currency'] );
		$res = <<<HTML
<table class="invoice">
	<thead>
		<tr>
			<th scope="col">Destination</th>
			<th scope="col">Temps</th>
			<th scope="col">Tarif<br/><span class="unit">($currency/heure)</span></th>
			<th scope="col">Prix<br/><span class="unit">($currency)</span></th>
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
			$res .= $this->buildTableCell( $this->formatDuration( $duration ) );
			$res .= $this->buildTableCell( number_format( $group['price'], 2 ) );
	
			$res .= $this->buildTableCell( $local );
			$res .= '</tr>';

			$total += $local;
		}

		$res .= <<<HTML
	</tbody>
	<tfoot>
		<tr>
			<td class="hiddencell"></td>
			<td class="hiddencell"></td>
			<td class="hiddencell"></td>
			<td>$total</td>
		</tr>
	</tfoot>
</table>
HTML
		;

		return $res;
	}
}
