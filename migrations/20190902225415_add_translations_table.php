<?php

use Phinx\Migration\AbstractMigration;

class AddTranslationsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     *
    */
    public function change()
    {
        $this->table('translations')
            ->addColumn('resource', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('resource_id', 'integer', ['null' => false])
            ->addColumn('property', 'string', ['limit' => 50, 'null' => false])
            /**
             * Source is the source text. 
             * Not being used yet but just for future reference and 
             * possibly flagging when the source changes
            **/
            ->addColumn('source', 'string')
            ->addColumn('translation', 'string', ['null' => false])
             ->addColumn('locale', 'string', ['limit' => 5, 'default' => 'en'])
             ->addIndex(['resource', 'resource_id', 'property', 'locale'], ['unique' => true])
             ->create();
     }
}
