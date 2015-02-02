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
	private function getDomain( $identifier ) {
		return explode( '@', $identifier )[1];
	}

	/** Get the caller domain. */
	public function getCallerDomain() {
		return $this->getDomain( $this->caller );
	}

	/** Get the callee domain. */
	public function getCalleeDomain() {
		return $this->getDomain( $this->callee );
	}
	
	/** Get the name part of an identifier.
	 * @param string $identifier Identifier.
	 */
	private function getName( $identifier ) {
		return explode( '@', $identifier )[0];
	}
	
	/** Get the caller name. */
	public function getCallerName() {
		return $this->getName( $this->caller );
	}
	
	/** Get the callee name. */
	public function getCalleeName() {
		return $this->getName( $this->callee );
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
