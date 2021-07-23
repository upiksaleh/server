<?php
/**
 * @copyright Copyright (c) 2016 Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author Joas Schilling <coding@schilljs.com>
 * @author Lukas Reschke <lukas@statuscode.ch>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCP\Settings;

use OCP\Activity\ISetting;
use OCP\IUser;

/**
 * @since 9.1
 */
interface IManager {
	/**
	 * @since 9.1.0
	 */
	public const KEY_ADMIN_SETTINGS = 'admin';

	/**
	 * @since 9.1.0
	 */
	public const KEY_ADMIN_SECTION = 'admin-section';

	/**
	 * @since 13.0.0
	 */
	public const KEY_PERSONAL_SETTINGS = 'personal';

	/**
	 * @since 13.0.0
	 */
	public const KEY_PERSONAL_SECTION = 'personal-section';

	/**
	 * @param string $type 'admin-section' or 'personal-section'
	 * @param string $section Class must implement OCP\Settings\ISection
	 * @since 14.0.0
	 */
	public function registerSection(string $type, string $section);

	/**
	 * @param string $type 'admin' or 'personal'
	 * @param string $setting Class must implement OCP\Settings\ISetting
	 * @param bool $allowDelegation Allow delegation
	 * @since 14.0.0
	 */
	public function registerSetting(string $type, string $setting, bool $allowDelegation = false);

	/**
	 * returns a list of the admin sections
	 *
	 * @return array array of ISection[] where key is the priority
	 * @since 9.1.0
	 */
	public function getAdminSections(): array;

	/**
	 * returns a list of the personal sections
	 *
	 * @return array array of ISection[] where key is the priority
	 * @since 13.0.0
	 */
	public function getPersonalSections(): array;

	/**
	 * returns a list of the admin settings
	 *
	 * @param string $section the section id for which to load the settings
	 * @param bool $subAdminOnly only return settings sub admins are supposed to see (since 17.0.0)
	 * @return array array of ISetting[] where key is the priority
	 * @since 9.1.0
	 * @depreacted Use IManager::getAuthorizedAdminSettings instead
	 */
	public function getAdminSettings($section, bool $subAdminOnly = false): array;

	/**
	 * Returns a list of admin settings that the current user is able to access.
	 *
	 * @param $section
	 * @return array
	 * @since 23.0.0
	 */
	public function getAuthorizedAdminSettings($section, IUser $user): array;

	/**
	 * returns a list of the personal  settings
	 *
	 * @param string $section the section id for which to load the settings
	 * @return array array of ISetting[] where key is the priority
	 * @since 13.0.0
	 */
	public function getPersonalSettings($section): array;

	/**
	 * Returns a list of admin settings there admin delegation is allowed.
	 *
	 * @return array<class-string<ISetting>> The array of admin settings there admin delegation is allowed.
	 * @since 23.0.0
	 */
	public function getAdminDelegationAllowedSettings(): array;
}
