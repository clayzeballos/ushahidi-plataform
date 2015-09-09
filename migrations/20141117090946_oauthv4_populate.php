<?php

use Phinx\Migration\AbstractMigration;

class Oauthv4Populate extends AbstractMigration
{
 
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("INSERT INTO oauth_scopes (id, description)
            VALUES
                ('api', 'api'),
                ('posts', 'posts'),
                ('forms', 'forms'),
                ('sets', 'set'),
                ('tags', 'tags'),
                ('users', 'users'),
                ('media', 'media'),
                ('config', 'config'),
                ('messages', 'messages'),
                ('dataproviders', 'dataproviders'),
                ('layers', 'layers'),
                ('stats', 'stats');
        ");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute("DELETE FROM oauth_scopes
            WHERE id IN (
                'api',
                'posts',
                'forms',
                'sets',
                'tags',
                'users',
                'media',
                'config',
                'messages',
                'dataproviders',
                'layers',
                'stats'
            )
        ");
    }
}
