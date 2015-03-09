<?php

/** An account. */
class Account implements AccountContext {
	
	private static $aliases = array();
	
	public static function setDomainAliases( $domain, $aliases ) {
		foreach ( $aliases as $alias ) {
			if ( array_key_exists( $alias, self::$aliases ) ) {
				throw new Exception( "Tentative de réattribution du nom de domaine alternatif « $alias »" );
			}
			self::$aliases[$alias] = $domain;
		}
	}
	
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
	
	/** Account name. */
	private $name;
	
	/** Account domain. */
	private $domain;
	
	/** Constructor.
	 * @param string $id Account identifiant.
	 */
	private function __construct( $id ) {
		$exploded = explode( '@', $id );
		if ( count( $exploded ) !== 2 ) {
			throw new Exception( "Identifiant de compte « $id » mal formé" );
		}
		
		$this->name = $exploded[0];
		
		$this->domain = $exploded[1];
		//var_dump( $this->domain );
		if ( array_key_exists( $this->domain, self::$aliases ) ) {
			$this->domain = self::$aliases[$this->domain];
		}
		//var_dump( $this->domain );
	}
	
	/** Get the domain part of the account. */
	public function getDomain() {
		return $this->domain;
	}
	
	/** Get the name of the account. */
	public function getName() {
		return $this->name;
	}
	
	/** Get the identifiant of the account. */
	public function getId() {
		return $this->name . '@' . $this->domain;
	}
	
	/** Get a short name of the account in a domain.
	 * @param string $domain Domain.
	 */
	public function getShortName( $domain ) {
		return $this->domain === $domain
			? $this->name
			: $this->getId();
	}
	
	/** Get the string representation of the account. */
	public function __toString() {
		return $this->getId();
	}
	
/*----- AccountContext -----*/
	
	/** Know whether an account is the same as the current one.
	 * @param Account $acct The account to test.
	 */
	public function contains( Account $acct ) {
		return $acct === $this;
	}
	
	/** Get the context description. */
	public function getDescription() {
		return $this->getName();
	}
	
}
