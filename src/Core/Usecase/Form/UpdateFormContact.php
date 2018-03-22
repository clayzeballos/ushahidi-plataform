<?php

/**
 * Ushahidi Platform Update Form Role Use Case
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Platform
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Usecase\Form;

use Ushahidi\Core\Usecase\CreateUsecase;
use Ushahidi\Core\Usecase\Concerns\IdentifyRecords;
use Ushahidi\Core\Usecase\Concerns\VerifyEntityLoaded;
use Ushahidi\Core\Entity\FormContact;
use Ushahidi\Core\Usecase\UpdateUsecase;

class UpdateFormContact extends UpdateUsecase
{
	// - VerifyFormLoaded for checking that the form exists
	use VerifyFormLoaded;

	// For form check:
	// - IdentifyRecords
	// - VerifyEntityLoaded
	use IdentifyRecords,
		VerifyEntityLoaded;


	protected function getEntity()
	{
		$entity = $this->repo->getEntity()->setState($this->payload);

		// Add user id if this is not provided
		if (empty($entity->user_id) && $this->auth->getUserId()) {
			$entity->setState(['user_id' => $this->auth->getUserId()]);
		}

		return $entity;
	}
	protected function getEntityByContact($contact)
	{
		// ... attempt to load the entity
		$entity = $this->repo->getByContact($contact);

		// ... and verify that the entity was actually loaded
//		$this->verifyEntityLoaded($entity, compact('id'));

		// ... then return it
		return $entity;
	}


	// Usecase
	public function interact()
	{
		// First verify that the form even exists
		$this->verifyFormExists();
		$this->verifyTargetedSurvey();
		$this->verifyFormExistsInContactPostState();
		// Fetch a default entity and ...
		$entity = $this->getEntity();

		// ... verify the current user has have permissions
		$this->verifyUpdateAuth($entity);

		// Get each item in the collection
		$entities = [];
		$invalid = [];
		$countryCode = $this->getPayload('country_code');
		$contacts = explode(',', $this->getPayload('contacts'));
		foreach ($contacts as $contact) {
			// .. generate an entity for the item
			$entity = $this->repo->getByContact(intval($this->getIdentifier('form_id')), $contact);
			/**
			 * we only use this field for validation
			 * we check that country code + phone number are valid.
			 * country_code is unset before saving the entity
			 */
			$entity->country_code = $countryCode;
			$entity->setState(
				[
					'updated' => time(),
					'contact' => $entity->contact,
				]
			);
			// ... and save it for later
			$entities[] = $entity;

			if (!$this->validator->check($entity->asArray())) {
				$invalid[$entity->contact] = $this->validator->errors();
			}
		}
		// FIXME: move to collection error trait?
		if (!empty($invalid)) {
			$invalidList = implode(',', array_keys($invalid));
			throw new ValidatorException(sprintf(
				'The following contacts are invalid:',
				$invalidList
			), $invalid);
		} else {
			// ... persist the new collection
			$this->repo->updateCollection($entities, intval($this->getIdentifier('form_id')));
			// ... and finally format it for output
			return $this->formatter->__invoke(intval($this->getIdentifier('form_id')), $entities);
		}
	}
}
