<?php defined('SYSPATH') or die('No direct script access');
/**
 * OAuth2 Storage for Sessions
 *
 * License is MIT, to be more compatible with PHP League.
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\OAuth2
 * @copyright  2014 Ushahidi
 * @license    http://mit-license.org/
 * @link       http://github.com/php-loep/oauth2-server
 */

use League\OAuth2\Server\Storage\ScopeInterface;

class OAuth2_Storage_Scope extends OAuth2_Storage implements ScopeInterface
{

    /**
     * {@inheritdoc}
     */
	public function get($scope, $grantType = null, $clientId = null)
	{
		// NOTE: this implementation does not implement any grant type checks!
		$query = $this->createSelectQuery('oauth_scopes', ['id' => $scope]);
		$result = $this->fetchSingleResult($query);

		if (!$result) {
            return null;
        }

        return (new ScopeEntity($this->server))->hydrate([
            'id'          =>  $result['id'],
            'description' =>  $result['description'],
        ]);
	}
}
