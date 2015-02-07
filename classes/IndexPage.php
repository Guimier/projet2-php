<?php

class IndexPage extends Page {

	protected function getTitle() {
		return 'Gestionnaire de factures téléphoniques';
	}

	protected function getcontent() {
		return AccountLogPage::getAccessForm();
	}

}
