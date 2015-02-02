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

}
