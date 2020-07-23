<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020, Georg Ehrke
 *
 * @author Georg Ehrke <oc.list@georgehrke.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\UserStatus\AppInfo;

use OCA\UserStatus\Capabilities;
use OCA\UserStatus\Listener\UserDeletedListener;
use OCA\UserStatus\Listener\UserLiveStatusListener;
use OCA\UserStatus\Service\JSDataService;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IInitialStateService;
use OCP\User\Events\UserDeletedEvent;
use OCP\User\Events\UserLiveStatusEvent;

/**
 * Class Application
 *
 * @package OCA\UserStatus\AppInfo
 */
class Application extends App implements IBootstrap {

	/** @var string */
	public const APP_ID = 'user_status';

	/**
	 * Application constructor.
	 *
	 * @param array $urlParams
	 */
	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	/**
	 * @inheritDoc
	 */
	public function register(IRegistrationContext $context): void {
		// Register OCS Capabilities
		$context->registerCapability(Capabilities::class);

		// Register Event Listeners
		$context->registerEventListener(UserDeletedEvent::class, UserDeletedListener::class);
		$context->registerEventListener(UserLiveStatusEvent::class, UserLiveStatusListener::class);
	}

	/**
	 * @inheritDoc
	 */
	public function boot(IBootContext $context): void {
		$appContainer = $context->getAppContainer();

		/** @var IInitialStateService $initialState */
		$initialState = $appContainer->query(IInitialStateService::class);
		$initialState->provideLazyInitialState(self::APP_ID, 'status', static function () use ($appContainer) {
			/** @var JSDataService $data */
			$data = $appContainer->query(JSDataService::class);
			return $data;
		});

		$dispatcher = $appContainer->query(IEventDispatcher::class);
		$dispatcher->addListener(TemplateResponse::EVENT_LOAD_ADDITIONAL_SCRIPTS_LOGGEDIN, static function () {
			\OC_Util::addScript('user_status', 'user-status-menu');
			\OC_Util::addStyle('user_status', 'user-status-menu');
		});
	}
}
