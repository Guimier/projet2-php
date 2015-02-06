<?php

require_once __DIR__ . '/RadiusCall.php';

/** An avorted call as described in the radius log. */
class RadiusAvortedCall extends RadiusCall {

	/** Constructor.
	 * @param string $caller Identifer of the caller.
	 * @param string $callee Identifer of the callee.
	 * @param integer $date Timestamp of the date of the call.
	 */
	public function __construct( $caller, $callee, $date ) {
		parent::__construct( $caller, $callee, $date, 0 );
	}

}
