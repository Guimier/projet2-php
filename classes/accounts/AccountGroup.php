<?php

/** A group of account from configuration. */
class AccountGroup implements AccountContext {

	/** Configuration.
	 * @type array
	 */
	private $config;
	
	/** Group identifer.
	 * @type string
	 */
	private $id;
	
	/** Group definition, extracted from the configuration.
	 * @type array
	 */
	private $definition;
	
	/** Constructor.
	 * @param array $config Configuration.
	 * @param string $id Group identifier.
	 */
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

	/** Know whether an account is in the group.
	 * @param Account $acct The account to test.
	 */
	public function contains( Account $acct ) {
		return $acct->getDomain() === $this->config['domain'] && in_array( $acct->getName(), $this->definition['accounts'] );
	}

	/** Get the group label. */
	public function getDescription() {
		return $this->definition['name'];
	}

	/** Get the group identifier. */
	public function getId() {
		return $this->id;
	}

}