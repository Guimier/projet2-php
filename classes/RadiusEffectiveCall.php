<?php

require_once __DIR__ . '/RadiusCall.php';

/** An effective call as described in the radius log. */
class RadiusEffectiveCall extends RadiusCall {

	/** Constructor.
	 * @param string $caller Identifer of the caller.
	 * @param string $callee Identifer of the callee.
	 * @param integer $start Timestamp of the start date of the call.
	 * @param integer $end Timestamp of the end date of the call.
	 */
	public function __construct( $caller, $callee, $start, $end ) {
		parent::__construct( $caller, $callee, $start, $end - $start );
	}
	
	/** Get the call type in the context of a set of accounts.
	 * @param array $accounts The context accounts.
	 */
	public function getStatus( array $accounts ) {
		$callerInside = in_array( $this->getCaller(), $accounts );
		$calleeInside = in_array( $this->getCallee(), $accounts );
		
		if ( $callerInside ) {
			if ( $calleeInside ) {
				return 'internal';
			} else {
				return 'caller';
			}
		} else {
			if ( $calleeInside ) {
				return 'callee';
			} else {
				return 'external';
			}
		}
	}

}
