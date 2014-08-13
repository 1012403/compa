<?php
/**
 *
 * @author HongNguyen
 *
 */

class Core_Util_Mail {
	public static function sendMail($mailInfo) {
		$ret = FALSE;
		$mailTransport = Zend_Registry::get('mailTransport');
		$mailSender = new Zend_Mail('utf-8');

		$mailSender->setFrom($mailInfo['from']);
		$mailSender->addTo($mailInfo['to']);
		$mailSender->setSubject($mailInfo['subject']);
		$mailSender->setBodyText($mailInfo['body']);

		$mailSender->send($mailTransport);
		return true;
	}
}

