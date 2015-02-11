<?php

/** A domain. */
class AccountDomain implements AccountContext {

	/** Domain name.
	 * @type string
	 */
	private $name;
	
	/** Constructor.
	 * @param string $name Domain name
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	/** Know whether an account is in the domain.
	 * @param Account $acct The account to test.
	 */
	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->name;
	}
	
	/** Get the domain description. */
	public function getDescription() {
		return $this->name;
	}

}