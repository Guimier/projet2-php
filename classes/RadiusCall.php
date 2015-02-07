<?php

/** A call as described in the radius log. */
abstract class RadiusCall {

	/** Caller identifiant.
	 * @type Account
	 */
	private $caller;

	/** Callee identifiant.
	 * @type Account
	 */
	private $callee;

	/** Timestamp of the start date of the call.
	 * @type integer
	 */
	private $start;

	/** Duration of the call.
	 * @type integer
	 */
	private $duration;

	/** Constructor.
	 * @param string $caller Identifer of the caller.
	 * @param string $callee Identifer of the callee.
	 * @param integer $start Timestamp of the start date of the call.
	 * @param integer $duration Duration of the call.
	 */
	protected function __construct( $caller, $callee, $start, $duration ) {
		$this->caller = Account::get( $caller );
		$this->callee = Account::get( $callee );
		$this->start = $start;
		$this->duration = $duration;
	}

	/** Get the caller. */
	public function getCaller() {
		return $this->caller;
	}

	/** Get the callee. */
	public function getCallee() {
		return $this->callee;
	}
	
	/** Get the start time (UNIX timestamp). */
	public function getStartTime() {
		return $this->start;
	}
	
	/** Get the duration in seconds. */
	public function getDuration() {
		return $this->duration;
	}

}
