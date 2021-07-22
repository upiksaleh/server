<?php
/**
 * @copyright Copyright (c) 2021 Nextcloud GmbH
 *
 * @author Carl Schwan <carl@carlschwan.eu>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace OCA\AdminRightSubgranting\Settings;

use OCA\AdminRightSubgranting\AppInfo\Application;
use OCA\AdminRightSubgranting\Service\AuthorizedGroupService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\Settings\IIconSection;
use OCP\Settings\IManager;
use OCP\Settings\ISettings;

class AdminSettings implements ISettings {

	protected $appName;

	/** @var IConfig */
	private $config;

	/** @var IManager */
	private $settingManager;

	/** @var IInitialState $initialStateService */
	private $initialStateService;

	/** @var IGroupManager $groupManager */
	private $groupManager;

	/** @var AuthorizedGroupService $service */
	private $service;

	/**
	 * Admin constructor.
	 *
	 * @param IConfig $config
	 * @param IManager $settingManager
	 */
	public function __construct(
		IConfig $config,
		IManager $settingManager,
		IInitialState $initialStateService,
		IGroupManager $groupManager,
		AuthorizedGroupService $service
	) {
		$this->appName = Application::APP_ID;
		$this->config = $config;
		$this->settingManager = $settingManager;
		$this->initialStateService = $initialStateService;
		$this->groupManager = $groupManager;
		$this->service = $service;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$settingsClasses = $this->settingManager->getAdminDelegationAllowedSettings();

		// Available settings page initialization
		$sections = $this->settingManager->getAdminSections();
		$settings = [];
		foreach ($settingsClasses as $settingClass) {
			$settingSection = \OC::$server->get($settingClass)->getSection();
			$sectionName = $settingSection;
			foreach ($sections as $sectionPriority) {
				foreach ($sectionPriority as $section) {
					/** @var IIconSection $section */
					if ($section->getID() == $sectionName) {
						$sectionName = $section->getName();
					}
					break; // break the two foreach loop
				}
			}
			$settings[] = [
				'class' => $settingClass,
				'sectionName' => $sectionName
			];
		}
		$this->initialStateService->provideInitialState('available-settings', $settings);

		// Available groups initialization
		$groups = [];
		$groupsClass = $this->groupManager->search('');
		foreach ($groupsClass as $group) {
			$groups[] = [
				'displayName' => $group->getDisplayName(),
				'gid' => $group->getGID(),
			];
		}
		$this->initialStateService->provideInitialState('available-groups', $groups);

		// Already set authorized groups
		$this->initialStateService->provideInitialState('authorized-groups', $this->service->findAll());

		return new TemplateResponse(Application::APP_ID, 'settings/index', [], 'blank');
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection() {
		return 'adminrightsubgranting';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is re
	/**
	 * @param string $section
	 * @return array
	 */
	protected function getSettings($section) {
		/** @var IUser $user */
		$settings = $this->settingsManager->getAdminDelegationAllowedSettings();
		$formatted = $this->formatSettings($settings);
		return $formatted;
	}
	/*
	 * @inheritdoc
	 */
	public function getPriority() {
		return 75;
	}
}
