<?php

class SingleAccountContext extends AccountContext {

	private $account;
	
	public function __construct( Account $acct ) {
		$this->account = $acct;
	}

	public function contains( Account $acct ) {
		return $acct === $this->account;
	}
	
	public function getDescription() {
		return $this->account->getName();
	}

}