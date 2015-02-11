<?php

/** Page giving access to others. */
class IndexPage extends Page {

	/** Get the page title. */
	protected function getTitle() {
		return 'Gestionnaire de factures téléphoniques';
	}

	/** Get the main content. */
	protected function getcontent() {
		return InvoicePage::buildAccessForm( $this->config['groups'] )
			. '<hr/>'
			. AccountLogPage::buildAccessForm()
			. GroupLogPage::buildAccessForm( $this->config['groups'] )
			. GlobalLogPage::buildAccessForm();
	}

}
