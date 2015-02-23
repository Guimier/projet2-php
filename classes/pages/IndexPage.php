<?php

/** Page giving access to others. */
class IndexPage extends Page {

	/** Get the page title. */
	protected function getTitle() {
		return 'Gestionnaire de factures téléphoniques';
	}

	/** Get the main content. */
	protected function getcontent() {
		return GroupInvoicePage::buildAccessForm( $this->config['groups'] )
			. AccountInvoicePage::buildAccessForm()
			. '<hr/>'
			. GlobalLogPage::buildAccessForm()
			. GroupLogPage::buildAccessForm( $this->config['groups'] )
			. AccountLogPage::buildAccessForm();
	}

}
