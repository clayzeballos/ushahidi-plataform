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

use League\OAuth2\Server\Storage\ClientInterface;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Entity\ClientEntity;

class OAuth2_Storage_Client extends OAuth2_Storage implements ClientInterface
{
    public function get($clientId, $clientSecret = null, $redirectUri = null, $grantType = null)
    {
        if ($redirectUri AND $clientId === $this->get_internal_client_id())
        {
            // The internal client only supports local redirects, so we strip the
            // domain information from the URI. This also prevents accidental redirect
            // outside of the current domain.
            $redirectUri = parse_url($redirectUri, PHP_URL_PATH);

            // We attempt to strip out the base URL, so that deployments work properly
            // when installed to a sub-directory.
            $baseUrl = preg_quote(URL::base(NULL, true), '~');
            $redirectUri = preg_replace("~^{$baseUrl}~", '/', $redirectUri);
        }

        if ($clientSecret and $redirectUri)
        {
            $query = $this->query_secret_and_redirect_uri()
                        ->param(':clientId', $clientId)
                        ->param(':secret', $clientSecret)
                        ->param(':redirectUri', $redirectUri);
        }
        else if ($clientSecret) 
        {
            $query = $this->query_secret()
                        ->param(':clientId', $clientId)
                        ->param(':secret', $clientSecret);
        }
        else if ($redirectUri) 
        {
            $query = $this->query_redirect_uri()
                        ->param(':clientId', $clientId)
                        ->param(':redirectUri', $redirectUri);
        }

        $result = $this->fetchSingleResult($query);

        if ($result)
        {
            $client = new ClientEntity($this->server);
            $client->hydrate([
                'id'   =>  $result['id'],
                'name' =>  $result['name'],
            ]);

            return $client;
        }

        return null;
    }

    public function getBySession(SessionEntity $session)
    {
        $query = DB::query(Database::SELECT, '
                        SELECT oauth_clients.id, oauth_clients.name
                          FROM oauth_clients, oauth_sessions
                         WHERE oauth_sessions.client_id = oauth_clients.id
                           AND oauth_sessions.id = :sessionId');

        $query->param(':sessionId', $session->getId());

        $result = $this->fetchSingleResult($query);

        if ($result) {
            $client = new ClientEntity($this->server);
            $client->hydrate([
                'id'   =>  $result['id'],
                'name' =>  $result['name'],
            ]);

            return $client;
        }

        return null;
    }

    private function query_secret_and_redirect_uri()
    {
        return DB::query(Database::SELECT, '
                    SELECT oauth_clients.*, 
                            oauth_client_endpoints.client_id, 
                            oauth_client_endpoints.redirect_uri
                      FROM oauth_clients, oauth_client_endpoints oce
                     WHERE oauth_clients.id = :clientId
                       AND oauth_clients.id = oauth_client_endpoints.client_id
                       AND oauth_client_endpoints.redirect_uri = :redirectUri
                       AND oauth_clients.secret = :secret');
    }

    private function query_secret()
    {
        return DB::query(Database::SELECT, '
                    SELECT oauth_clients.*
                      FROM oauth_clients
                     WHERE oauth_clients.id = :clientId
                       AND oauth_clients.secret = :secret');
    }

    private function query_redirect_uri()
    {
        return DB::query(Database::SELECT, '
                    SELECT oauth_clients.*, 
                            oauth_client_endpoints.client_id, 
                            oauth_client_endpoints.redirect_uri
                      FROM oauth_clients, oauth_client_endpoints
                     WHERE oauth_clients.id = :clientId
                       AND oauth_clients.id = oauth_client_endpoints.client_id
                       AND oauth_client_endpoints.redirect_uri = :redirectUri');
    }

    private function get_internal_client_id()
    {
        return Kohana::$config->load('ushahidiui.oauth.client');
    }
}
