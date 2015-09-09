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

use League\OAuth2\Server\Entity\AbstractTokenEntity;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Storage\Adapter;

class OAuth2_Storage_AccessToken extends OAuth2_Storage implements AccessTokenInterface
{

    public function get($token)
    {
        $query = $this->createSelectQuery('oauth_access_tokens', ['access_token' => $token]);
        $result = $this->fetchSingleResult($query);

        if ($result) {
            $token = (new AccessTokenEntity($this->server))
                        ->setId($result['access_token'])
                        ->setExpireTime($result['expire_time']);

            return $token;
        }

        return null;
    }

    public function getScopes(AccessTokenEntity $token)
    {
        $query = DB::query(Database::SELECT, '
                        SELECT oauth_scopes.id, oauth_scopes.description
                          FROM oauth_access_token_scopes, oauth_scopes
                         WHERE oauth_access_token_scopes.scope = oauth_scopes.id
                           AND access_token = :accessToken');

        $query->param(':accessToken', $token->getId());

        $result = $this->fetchResults($query);

        $response = [];

        if ($result and count($result) > 0) {
            foreach ($result as $row) {
                $scope = (new ScopeEntity($this->server))->hydrate([
                    'id'          =>  $row['id'],
                    'description' =>  $row['description'],
                ]);
                $response[] = $scope;
            }
        }

        return $response;
    }

    public function create($token, $expireTime, $sessionId)
    {
        return $this->executeInsert('oauth_access_tokens', [
                        'access_token' =>  $token,
                        'session_id'   =>  $sessionId,
                        'expire_time'  =>  $expireTime,
                    ]);
    }

    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        return $this->executeInsert('oauth_access_token_scopes', [
                        'access_token' =>  $token->getId(),
                        'scope'        =>  $scope->getId(),
                    ]);
    }
    
    public function delete(AccessTokenEntity $token)
    {        
        $this->executeDelete('oauth_access_token_scopes', ['access_token' => $token->getId()]);
    }
}
