<?php

class AccountGroup implements AccountContext {

	private $config;
	private $id;
	private $definition;
	
	public function __construct( array $config, $id ) {
		$this->config = $config;
		$this->id = $id;
		
		if ( ! array_key_exists( $id, $config['groups'] ) ) {
			throw new Exception( "Groupe inconnu « $id »" );
		}
		
		$this->definition = $config['groups'][$id];
		
		if (
			! array_key_exists( 'name', $this->definition ) || ! is_string( $this->definition['name'] ) ||
			! array_key_exists( 'accounts', $this->definition ) || ! is_array( $this->definition['accounts'] )
		) {
			throw new Exception( "Configuration du groupe « $id » invalide" );
		}
		
	}

	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->config['domain'] && in_array( $acct->getName(), $this->definition['accounts'] );
	}

	public function getDescription() {
		return $this->definition['name'];
	}

	public function getId() {
		return $this->id;
	}

}