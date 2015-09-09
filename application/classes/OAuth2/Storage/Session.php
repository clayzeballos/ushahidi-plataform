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

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\Adapter;
use League\OAuth2\Server\Storage\SessionInterface;

class OAuth2_Storage_Session extends OAuth2_Storage implements SessionInterface
{
    public function getByAccessToken(AccessTokenEntity $accessToken)
    {
        $query = DB::query(Database::SELECT, '
                        SELECT oauth_sessions.id, 
                                oauth_sessions.owner_type, 
                                oauth_sessions.owner_id, 
                                oauth_sessions.client_id, 
                                oauth_sessions.client_redirect_uri
                          FROM oauth_sessions, oauth_access_tokens
                         WHERE oauth_access_tokens.session_id = oauth_sessions.id
                           AND oauth_access_tokens.access_token = :accessToken');

        $query->param(':accessToken', $accessToken->getId());

        $result = $this->fetchSingleResult($query);

        if ($result) {
            $session = new SessionEntity($this->server);
            $session->setId($result['id']);
            $session->setOwner($result['owner_type'], $result['owner_id']);

            return $session;
        }

        return null;
    }

    public function getByAuthCode(AuthCodeEntity $authCode)
    {
        $query = DB::query(Database::SELECT, '
                        SELECT oauth_sessions.id, 
                                oauth_sessions.owner_type, 
                                oauth_sessions.owner_id, 
                                oauth_sessions.client_id, 
                                oauth_sessions.client_redirect_uri
                          FROM oauth_sessions, oauth_auth_codes
                         WHERE oauth_auth_codes.session_id = oauth_sessions.id
                           AND oauth_auth_codes.auth_code = :authCode');

        $query->param(':authCode', $authCode->getId());

        $result = $this->fetchSingleResult($query);

        if ($result) {
            $session = new SessionEntity($this->server);
            $session->setId($result['id']);
            $session->setOwner($result['owner_type'], $result['owner_id']);

            return $session;
        }

        return null;
    }

    public function getScopes(SessionEntity $session)
    {

        $query = DB::query(Database::SELECT, '
                        SELECT oauth_scopes.*
                          FROM oauth_sessions, oauth_session_scopes, oauth_scopes
                         WHERE oauth_sessions.id = oauth_session_scopes.session_id
                           AND oauth_scopes.id = oauth_session_scopes.scope
                           AND oauth_sessions.id = :session');

        $query->param(':session', $session->getId());

        $result = $this->fetchResults($query);

        $scopes = [];
        if ($result) {
            foreach ($result as $scope) {
                $scopes[] = (new ScopeEntity($this->server))->hydrate([
                    'id'          =>  $scope['id'],
                    'description' =>  $scope['description'],
                ]);
            }
        }

        return $scopes;
    }

    public function create($ownerType, $ownerId, $clientId, $clientRedirectUri = null)
    {
        return $this->executeInsert('oauth_sessions', [
                            'owner_type' => $ownerType,
                            'owner_id'   => $ownerId,
                            'client_id'  => $clientId,
                        ]);
    }

    public function associateScope(SessionEntity $session, ScopeEntity $scope)
    {
        $this->executeInsert('oauth_session_scopes', [
                                'session_id' =>  $session->getId(),
                                'scope'      =>  $scope->getId(),
                            ]);
    }
}
