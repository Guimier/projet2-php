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

	/** Filter by caller account.
	 * @param mixed $context Context, see Account::inContext.
	 * @return callable
	 */
	public static function filterByCaller( $context ) {
		return function ( Call $call ) use ( $context ) {
			return $call->getCaller()->inContext( $context );
		};
	}

	/** Filter by callee account.
	 * @param mixed $context Context, see Account::inContext.
	 * @return callable
	 */
	public static function filterByCallee( $context ) {
		return function ( Call $call ) use ( $context ) {
			return $call->getCallee()->inContext( $context );
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
	 *        You may want to use `filter*` static functions of this
	 *        class to create this parameter.
	 */
	public function __construct( CallList $list, $filter ) {
		$this->filteredOut = new CallList();
		foreach ( $list as $call ) {
			if ( call_user_func( $filter, $call ) ) {
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
