<?php

class FilteredCallList extends CallList {

/*----- Predefined filters -----*/

	/** Filter by accounts.
	 * @param mixed $context Context, see Account::inContext.
	 * @return callable
	 */
	public static function filterByContext( $context ) {
		return function ( Call $call ) use ( $context ) {
			return $call->getCaller()->inContext( $context ) || $call->getCallee()->inContext( $context );
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
