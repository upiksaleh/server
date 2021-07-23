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

namespace OC\Settings;

use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class AuthorizedGroupMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'authorizedgroups', AuthorizedGroup::class);
	}

	public function findAllClassesForUser(string $userId) {
		// TODO
	}

	/**
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws \OCP\DB\Exception
	 */
	public function find(int $id): AuthorizedGroup {
		$queryBuilder = $this->db->getQueryBuilder();
		$queryBuilder->select('*')
			->from($this->getTableName())
			->where($queryBuilder->expr()->eq('id', $queryBuilder->createNamedParameter($id)));
		return $this->findEntity($queryBuilder);
	}

	/**
	 * Get all the authorizations stored in the database.
	 *
	 * @return AuthorizedGroup[]
	 * @throws \OCP\DB\Exception
	 */
	public function findAll(): array {
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')->from($this->getTableName());
		return $this->findEntities($qb);
	}

	public function findByGroupIdAndClass(string $groupId, string $class) {
		$queryBuilder = $this->db->getQueryBuilder();
		$queryBuilder->select('*')
			->from($this->getTableName())
			->where($queryBuilder->expr()->andX(
				$queryBuilder->expr()->eq('group_id', $groupId),
				$queryBuilder->expr()->eq('class', $class)
			));
		return $this->findEntity($queryBuilder);
	}

	/**
	 * @return Entity[]
	 * @throws \OCP\DB\Exception
	 */
	public function findOldGroups(string $class): array {
		$queryBuilder = $this->db->getQueryBuilder();
		$queryBuilder->select('*')
			->from($this->getTableName())
			->where(
				$queryBuilder->expr()->eq('class', $class)
			);
		return $this->findEntities($queryBuilder);
	}
}
