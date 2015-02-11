<?php

/** Universal context. */
class Universe implements AccountContext {

	/** Know whether an account may exist in the universe.
	 * @param Account $acct The account to test.
	 */
	public function contains( Account $acct ) {
		return true;
	}
	
	/** Get the universe description.
	 * @todo This should of course return 42.
	 */
	public function getDescription() {
		return 'Autres';
	}

}