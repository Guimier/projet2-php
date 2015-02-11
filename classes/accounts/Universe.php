<?php

class Universe implements AccountContext {

	public function contains( Account $acct ) {
		return true;
	}
	
	public function getDescription() {
		return 'Autres';
	}

}