<?php

class FilteredCallList extends CallList {

/*----- Predefined filters -----*/

	/** Filter by accounts.
	 * @param AccountContext $context Context to filter on.
	 * @return callable
	 */
	public static function filterByContext( AccountContext $context ) {
		return function ( Call $call ) use ( $context ) {
			return $context->contains( $call->getCaller() ) || $context->contains( $call->getCallee() );
		};
	}

	/** Filter by caller account.
	 * @param AccountContext $context Context to filter on.
	 * @return callable
	 */
	public static function filterByCaller( AccountContext $context ) {
		return function ( Call $call ) use ( $context ) {
			return $context->contains( $call->getCaller() );
		};
	}

	/** Filter by callee account.
	 * @param AccountContext $context Context to filter on.
	 * @return callable
	 */
	public static function filterByCallee( AccountContext $context ) {
		return function ( Call $call ) use ( $context ) {
			return $context->contains( $call->getCallee() );
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
