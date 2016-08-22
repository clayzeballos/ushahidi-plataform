<?php

/**
 * Ushahidi Sanitize Entity Relation Trait
 *
 * Cleans entity of un-authorized relation references
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Platform
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Tool;

use Ushahidi\Core\Entity;

trait SanitizeEntityRelations
{

	/**
	 * Sanitize resticted relation data from entity
	 * @param  Entity $entity
	 */
	protected function sanitizeEntityRelations(Entity $entity)
	{
		$definitions = $entity->getDefinition();
		// Get list of fields which contain relation data
		foreach($entity->getRelations() as $relation) {
			// Grab the relation data
			$values = $entity->$relation;
			// If the relation is single value/not *-many
			// Wrap the value in an array
			if ($definition[$relation] !== 'array') {
				$values = [$value];
			}

			foreach ($values as $value) {
				//
			}
		}
	}

}
