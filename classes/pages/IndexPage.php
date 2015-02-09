<?php

/** Page giving access to others. */
class IndexPage extends Page {

	/** Get the page title. */
	protected function getTitle() {
		return 'Gestionnaire de factures téléphoniques';
	}

	/** Get the main content. */
	protected function getcontent() {
		return AccountLogPage::buildAccessForm()
			. GlobalLogPage::buildAccessForm();
	}

}
