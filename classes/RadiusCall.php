<?php

/** A call as described in the radius log. */
abstract class RadiusCall {

	/** Caller identifiant.
	 * @type string
	 */
	private $caller;

	/** Callee identifiant.
	 * @type string
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
		$this->caller = $caller;
		$this->callee = $callee;
		$this->start = $start;
		$this->duration = $duration;
	}
	
	/** Get the domain part of an identifier.
	 * @param string $identifier Identifier.
	 */
	public static function getDomain( $identifier ) {
		return explode( '@', $identifier )[1];
	}
	
	/** Get the name part of an identifier.
	 * @param string $identifier Identifier.
	 */
	public static function getName( $identifier ) {
		return explode( '@', $identifier )[0];
	}

	/** Get the caller. */
	public function getCaller() {
		return $this->caller;
	}

	/** Get the callee. */
	public function getCallee() {
		return $this->callee;
	}
	
	public function callerIs( $name, $domain ) {
		return $this->caller === "$name@$domain";
	}
	
	public function calleeIs( $name, $domain ) {
		return $this->callee === "$name@$domain";
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
