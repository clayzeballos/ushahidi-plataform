<?php

/**
 * Ushahidi Org Admin Access Trait
 *
 * Gives objects two new methods:
 * `isUserOrgAdmin(User $user)`
 * `isUpdatingOrgAdminLoginData(Entity $entity)`
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Traits;

use Ushahidi\Core\Entity\User;
use Ushahidi\Core\Entity;

trait OrgAdminAccess
{

	/**
	 * Check if the user has an Admin role
	 * @param  User    $user
	 * @return boolean
	 */
	protected function isUserOrgAdmin(User $user)
	{
		return ($user->id && $user->role === 'org_admin');
	}

	/**
	 * Check if login/authorization related fields have been modified.
	 * @param Entity $entity
	 * @return bool
	 */
	protected function isUpdatingOrgAdminLoginData(Entity $entity) {
		return ($entity->hasChanged('email') || $entity->hasChanged('password') || $entity->hasChanged('role'));
	}
}
