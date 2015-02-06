<?php

class IndexPage extends Page {

	protected function getTitle() {
		return 'Gestionaire de factures téléphoniques';
	}

	protected function getcontent() {
		return LogPage::getAccessForm();
	}

}
