<?php

use Phinx\Migration\AbstractMigration;

class Oauthv4Relations extends AbstractMigration
{
    private $foreign_keys = [
        // Define all foreign keys here, in format:
        // [local table, local column, remote table, remote column]
        ['oauth_access_token_scopes', 'access_token', 'oauth_access_tokens', 'access_token'],
        ['oauth_access_token_scopes', 'scope', 'oauth_scopes', 'id'],
        ['oauth_access_tokens', 'session_id', 'oauth_sessions', 'id'],
        ['oauth_auth_code_scopes', 'auth_code', 'oauth_auth_codes', 'auth_code'],
        ['oauth_auth_code_scopes', 'scope', 'oauth_scopes', 'id'],
        ['oauth_auth_codes', 'session_id', 'oauth_sessions', 'id'],
        ['oauth_client_endpoints', 'client_id', 'oauth_clients', 'id'],
        ['oauth_refresh_tokens', 'access_token', 'oauth_access_tokens', 'access_token'],
        ['oauth_session_scopes', 'scope', 'oauth_scopes', 'id'],
        ['oauth_session_scopes', 'session_id', 'oauth_sessions', 'id'],
        ['oauth_sessions', 'client_id', 'oauth_clients', 'id'],
    ];

    /**
     * Migrate Up.
     */
    public function up()
    {
        foreach ($this->foreign_keys as $key) {
            list($ltable, $lcolumn, $rtable, $rcolumn) = $key;
            try {
                $this->table($ltable)
                     ->addForeignKey($lcolumn, $rtable, $rcolumn, [
                        'delete' => 'CASCADE',
                        'update' => 'CASCADE',
                        ])
                     ->save();
            } catch (Exception $e) {
                throw new Exception(
                    "Failed to add foreign key: $ltable.$lcolumn -> $rtable.$rcolumn " .
                    $e->getMessage()
                );
            }
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        foreach ($this->foreign_keys as $key) {
            // For dropping, we only need the local table and column
            list($table, $column) = $key;
            $this->table($table)->dropForeignKey($column);
        }
    }
}
