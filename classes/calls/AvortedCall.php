<?php

/** An avorted call. */
class AvortedCall extends Call {

	/** Constructor.
	 * @param string $caller Identifer of the caller.
	 * @param string $callee Identifer of the callee.
	 * @param integer $date Timestamp of the date of the call.
	 */
	public function __construct( $caller, $callee, $date ) {
		parent::__construct( $caller, $callee, $date, 0 );
	}
	
	/** Get the call type in the context of a set of accounts.
	 * @param mixed $context Context, see Account::inContext.
	 */
	public function getStatus( $context ) {
		return 'avorted';
	}

}
