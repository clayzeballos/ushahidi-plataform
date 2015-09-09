<?php

/**
 * OAuth2 Resource Server
 *
 * License is MIT, to be more compatible with PHP League.
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\OAuth2
 * @copyright  2014 Ushahidi
 * @license    http://mit-license.org/
 * @link       http://github.com/php-loep/oauth2-server
 */

use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\Storage\SessionInterface;
// use League\OAuth2\Server\Exception\InsufficientScopeException;
use League\OAuth2\Server\Exception\InvalidScopeException;

class OAuth2_ResourceServer extends ResourceServer 
{
    protected $currentSession;

    public function getCurrentSession() 
    {
        if ($this->currentSession === null)
        {
            $this->setCurrentSession(); 
        }

        return $this->currentSession; // maybe also check for correct instance ?
    }

    private function setCurrentSession()
    {
        $this->currentSession = $this->getSessionStorage()->getByAccessToken($this->getAccessToken());

        return $this;
    }

    public function getCurrentSessionScopes()
    {
        return $this->getCurrentSession()->getScopes($this->getCurrentSession());
    }

    public function getOwnerId()
    {
        return $this->getCurrentSession()->getOwnerId();
    }   

    public function verifyScope($requiredScope = null)
    {
        if ($this->hasScope($requiredScope) !== true)
        {
           // $message = "User %d does not have privilegies to access %s scope";
            throw new InvalidScopeException($requiredScope);
            // throw new InvalidScopeException(sprintf($message, $this->getOwnerId(), $requiredScope));
        }
    }

    public function hasScope($scope = null)
    { 
        $scopes = $this->getCurrentSessionScopes();

        if ($scope and array_key_exists($scope, $scopes))
        {
            return true;
        }

        return false;
    }

}