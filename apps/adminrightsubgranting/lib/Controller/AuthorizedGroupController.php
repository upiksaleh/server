<?php
namespace OCA\AdminRightSubgranting\Controller;

use OCA\AdminRightSubgranting\Db\AuthorizedGroup;
use OCA\AdminRightSubgranting\Service\AuthorizedGroupService;
use OCA\AdminRightSubgranting\Service\NotFoundException;
use OCP\DB\Exception;
use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class AuthorizedGroupController extends Controller {
	use Errors;

	private $userId;

	/** @var AuthorizedGroupService $service */
	private $service;

	public function __construct($AppName, IRequest $request, $UserId, AuthorizedGroupService $service){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
	}

	/**
	 * @param string $class
	 * @return DataResponse
	 * @throws NotFoundException
	 * @throws Exception
	 * @AuthorizedAdminSetting(settings=OCA\AdminRightSubgranting\Settings\AdminSettings)
	 */
	public function saveSettings(array $groups, string $class): DataResponse {
		$oldGroups = $this->service->findOldGroups($class);

		foreach ($oldGroups as $group) {
			/** @var AuthorizedGroup $group */
			$removed = true;
			foreach ($groups as $groupData) {
				if ($groupData['gid'] === $group->getGroupId()) {
					$removed = false;
					break;
				}
			}
			if ($removed) {
				$this->service->delete($group->getId());
			}
		}

		var_dump($groups);
		foreach ($groups as $groupData) {
			$added = true;
			foreach ($oldGroups as $group) {
				/** @var AuthorizedGroup $group */
				if ($groupData['gid'] === $group->getGroupId()) {
					$added = false;
					break;
				}
			}
			if ($added) {
				$this->service->create($groupData['gid'], $class);
			}
		}
		return new DataResponse(['valid' => true]);
	}
}
