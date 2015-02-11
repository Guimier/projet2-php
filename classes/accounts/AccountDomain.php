<?php

class AccountDomain implements AccountContext {

	private $name;
	
	public function __construct( $name ) {
		$this->name = $name;
	}

	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->name;
	}
	
	public function getDescription() {
		return $this->name;
	}

}