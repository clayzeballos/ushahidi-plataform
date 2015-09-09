<?php

use Phinx\Migration\AbstractMigration;

class InitialOauthv4 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {
        $this->dropTable('oauth_session_authcode_scopes');
        $this->dropTable('oauth_session_token_scopes');
        $this->dropTable('oauth_scopes');
        $this->dropTable('oauth_session_refresh_tokens');
        $this->dropTable('oauth_session_redirects');
        $this->dropTable('oauth_session_authcodes');
        $this->dropTable('oauth_session_access_tokens');
        $this->dropTable('oauth_sessions');
        $this->dropTable('oauth_client_endpoints');
        $this->dropTable('oauth_clients');

        $this->table('oauth_clients', ['id' => false])
            ->addColumn('id', 'string', ['limit' => 40])
            ->addColumn('secret', 'string', ['limit' => 40])
            ->addColumn('name', 'string')
            ->addColumn('auto_approve', 'boolean', ['default' => 0])
            ->addIndex(['id'], ['unique' => true])
            ->addIndex(['secret', 'id'], ['unique' => true])
            ->create();

        $this->table('oauth_access_token_scopes')
            ->addColumn('access_token', 'string', ['limit' => 40])
            ->addColumn('scope', 'string')
            ->create();

        $this->table('oauth_access_tokens', ['id'=>false])
            ->addColumn('access_token', 'string', ['limit' => 40])
            ->addColumn('session_id', 'integer')
            ->addColumn('expire_time', 'integer')
            ->addIndex(['access_token', 'session_id'], ['unique' => true])
            ->create();

        $this->table('oauth_auth_code_scopes')
            ->addColumn('auth_code', 'string', ['limit' => 40])
            ->addColumn('scope', 'string')
            ->create();

        $this->table('oauth_auth_codes', ['id'=>false])
            ->addColumn('auth_code', 'string', ['limit' => 40])
            ->addColumn('session_id', 'integer')
            ->addColumn('expire_time', 'integer')
            ->addColumn('client_redirect_uri', 'string')
            ->addIndex(['auth_code', 'session_id'], ['unique' => true])
            ->create();

        $this->table('oauth_client_endpoints') // oauth_client_redirect_uris
            ->addColumn('client_id', 'string', ['limit' => 40])
            ->addColumn('redirect_uri', 'string')
            ->create();

        $this->table('oauth_refresh_tokens', ['id'=>false])
            ->addColumn('refresh_token', 'string', ['limit' => 40])
            ->addColumn('expire_time', 'integer')
            ->addColumn('access_token', 'string', ['limit' => 40])
            ->create();

        $this->table('oauth_scopes', ['id'=>false])
            ->addColumn('id', 'string')
            ->addColumn('description', 'string')
            ->addIndex(['id'], ['unique' => true])
            ->create();

        $this->table('oauth_session_scopes')
            ->addColumn('session_id', 'integer')
            ->addColumn('scope', 'string')
            ->create();


        $this->table('oauth_sessions')
            ->addColumn('client_id', 'string', ['limit' => 40])
            ->addColumn('owner_type', 'string', [
                'default' => 'user',
                'comment' => 'user, client',
                ])
            ->addColumn('owner_id', 'string')
            ->addColumn('client_redirect_uri', 'string', ['null' => true])
            ->addIndex(['owner_type'])
            ->addIndex(['owner_id'])
            ->create();
    }
}
