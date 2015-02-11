<?php

class GroupAccountContext extends AccountContext {

	private $domain;
	private $groupDef;
	
	public function __construct( array $config, $groupName ) {
		if ( ! array_key_exists( $groupName, $config['groups'] ) ) {
			throw new Exception( "Groupe inconnu « $groupName »" );
		}
		
		$this->domain = $config['domain'];
		$this->groupDef = $config['groups'][$groupName];
		
		if (
			! array_key_exists( 'name', $this->groupDef ) || ! is_string( $this->groupDef['name'] ) ||
			! array_key_exists( 'accounts', $this->groupDef ) || ! is_array( $this->groupDef['accounts'] )
		) {
			throw new Exception( "Configuration du groupe « $groupName » invalide" );
		}
		
	}

	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->domain && in_array( $acct->getName(), $this->groupDef['accounts'] );
	}

	public function getDescription() {
		return $this->groupDef['name'];
	}

}