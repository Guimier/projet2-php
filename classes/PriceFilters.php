<?php

class PriceFilters {

	private $alreadyAdded = array();

	private $filters = array();
	private $config;
	
	/** Get the AccountContext described in the configuration.
	 * @param string $rawContext Description of the context in the documentation.
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
	
	/** Append a collection of filters.
	 * @param array $def Filters definition.
	 */
	private function addFilters( array $def ) {
		foreach ( $def as $onedef ) {
			if ( ! in_array( $onedef[0], $this->alreadyAdded ) ) {
				$this->alreadyAdded[] = $onedef[0];
				
				$this->filters[] = array(
					'context' => $this->getContext( $onedef[0] ),
					'price' => $onedef[1]
				);
			}
		}
	}

	/** Constructor.
	 * @param array $conf Configuration.
	 * @param string [$group=null] Group.
	 */
	public function __construct( array $conf, $group = null ) {
		$this->config = $conf;
		
		if ( ! is_null( $group ) ) {
			$this->addFilters( $conf['groups'][$group]['prices'] );
		}
		
		$this->addFilters( $conf['prices'] );
	}
	
	/** Get the context an account belongs to.
	 * @param Account $acct The account to test.
	 */
	public function filterAccount( Account $acct ) {
		$i = 0;
		
		while (
			$i < count( $this->filters )
			&& ! $this->filters[$i]['context']->contains( $acct )
		) {
			++$i;
		}
		
		if ( $i == count( $this->filters ) ) {
			throw new Exception( "Le compte $acct ne correspond à aucun filtre de prix." );
		} else {
			return $this->filters[$i];
		}
	}
	
	/** Filter a call list by Callee.
	 * @param CallList List to filter.
	 * @return array A list of partial call lists with prices.
	 *               Array of { "price": integer, "list": CallList, "label": integer }
	 */
	public function filterByCallee( CallList $calls )  {
		$remaining = $calls;
		$res = array();

		$i = 0;
		foreach ( $this->filters as $filter ) {
			$context = $filter['context'];
			
			$partialLog = new FilteredCallList(
				$remaining,
				FilteredCallList::filterByCallee( $context )
			);
			$remaining = $partialLog->getFilteredOut();

			$res[] = array(
				'label' => $context->getDescription(),
				'list' => $partialLog,
				'price' => $filter['price']
			);
		}

		if ( $remaining->getLength() > 0 ) {
			throw new Exception( 'Les appels ne correspondent pas tous aux filtres de prix.' );
		}

		return $res;
	}

}