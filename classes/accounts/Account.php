<?php

/** An account. */
class Account implements AccountContext {
	
	/** Get an instance.
	 * @param string $id Account identifiant.
	 */
	public static function get( $id ) {
		static $accounts = array();
		
		if ( ! array_key_exists( $id, $accounts ) ) {
			$accounts[$id] = new self( $id );
		}
		
		return $accounts[$id];
	}
	
	/** Account identifiant. */
	private $id;
	
	/** Constructor.
	 * @param string $id Account identifiant.
	 */
	private function __construct( $id ) {
		$this->id = $id;
	}
	
	/** Get the domain part of the account. */
	public function getDomain() {
		$exploded = explode( '@', $this->id );
		return $exploded[1];
	}
	
	/** Get the name of the account. */
	public function getName() {
		$exploded = explode( '@', $this->id );
		return $exploded[0];
	}
	
	/** Get a short name of the account in a domain.
	 * @param string $domain Domain.
	 */
	public function getShortName( $domain ) {
		return$this->getDomain() === $domain
			? $this->getName()
			: $this->id;
	}
	
	/** Get the string representation of the account. */
	public function __toString() {
		return $this->id;
	}
	
/*----- AccountContext -----*/
	
	public function contains( Account $acct ) {
		return $acct === $this;
	}
	
	public function getDescription() {
		return $this->getName();
	}
	
}
