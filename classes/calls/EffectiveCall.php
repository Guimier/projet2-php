<?php

/** An effective call. */
class EffectiveCall extends Call {

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
	 * @param mixed $context Context, see Account::inContext.
	 */
	public function getStatus( $context ) {
		$callerInside = $this->getCaller()->inContext( $context );
		$calleeInside = $this->getCallee()->inContext( $context );
		
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
