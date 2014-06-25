<?php

class Monolog_Helper
{
	function logUpdateFeedDTO($log, $dto, $index = null)
	{
		$log->addInfo("Updating feed".($index != null ? " #".$index : "")." (".$dto->getFeed()->getId().") - ".$dto->getFeed()->getTitle());
		if (!$dto->isValid()) {
			$log->addWarning("Feed is now invalid.");
		}
		if (sizeof($dto->getErrors()) > 0) {
			foreach ($dto->getErrors() as $error) {
				$log->addError($error);
			}
		}
		else {
			$log->addInfo("Updated ".$dto->getNbEntriesUpdated()." entries.");
		}
	}
}