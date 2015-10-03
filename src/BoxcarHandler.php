<?php

namespace Zortje\MonologBoxcarHandler;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Zortje\BoxcarNotifications\Boxcar;
use Zortje\BoxcarNotifications\Notification;

/**
 * Class BoxcarHandler
 *
 * @package Zortje\MonologBoxcarHandler
 */
class BoxcarHandler extends AbstractProcessingHandler {

	/**
	 * @var Boxcar
	 */
	private $boxcar;

	/**
	 * Send push notifications to Boxcar for iOS
	 *
	 * @param Boxcar   $boxcar A initialized Boxcar object
	 * @param bool|int $level  The minimum logging level at which this handler will be triggered
	 * @param bool     $bubble Whether the messages that are handled can bubble up the stack or not
	 */
	public function __construct(Boxcar $boxcar, $level = Logger::DEBUG, $bubble = true) {
		$this->boxcar = $boxcar;

		parent::__construct($level, $bubble);
	}

	/**
	 * If title is provided we use that for the notification title, otherwise the record level name
	 *
	 * @inheritdoc
	 */
	protected function write(array $record) {
		$title = !empty($record['context']['title']) ? $record['context']['title'] : $record['level_name'];

		$this->boxcar->push(new Notification($title, $record['message']));
	}

}
