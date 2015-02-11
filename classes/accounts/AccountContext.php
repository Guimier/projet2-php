<?php

/** An account selection filter. */
interface AccountContext {
	
	/** Know whether an account is in the context.
	 * @param Account $acct The account to test.
	 */
	public function contains( Account $acct );
	
	/** Get the context description. */
	public function getDescription();

}