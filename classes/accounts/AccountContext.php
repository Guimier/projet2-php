<?php

abstract class AccountContext {

	abstract public function contains( Account $acct );
	abstract public function getDescription();

}