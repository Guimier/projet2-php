<?php

class FilteredCallList extends CallList {

/*----- Predefined filters -----*/

	/** Filter by accounts.
	 * @param array $accounts Valid accounts.
	 * @return callable
	 */
	public static function filterByAccounts( array $accounts ) {
		return function ( Call $call ) use ( $accounts ) {
			return in_array( $call->getCaller(), $accounts ) || in_array( $call->getCallee(), $accounts );
		};
	}

	
/*----- Object members -----*/

	/** Filtered out calls.
	 * @type CallList
	 */
	private $filteredOut;

	/** Constructor.
	 * @param CallList $list Original list.
	 * @param callable $filter Called as `boolean filter( Call )`.
	 */
	public function __construct( CallList $list, $test ) {
		$this->filteredOut = new CallList();
		foreach ( $list as $call ) {
			if ( call_user_func( $test, $call ) ) {
				$this->add( $call );
			} else {
				$this->filteredOut->add( $call );
			}
		}
	}

	/** Get the filtered out calls. */
	public function getFilteredOut() {
		return $this->filteredOut;
	}

}
