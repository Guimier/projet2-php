<?php

class DomainAccountContext extends AccountContext {

	private $domain;
	
	public function __construct( $domain ) {
		$this->domain = $domain;
	}

	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->domain;
	}
	
	public function getDescription() {
		return $this->domain;
	}

}