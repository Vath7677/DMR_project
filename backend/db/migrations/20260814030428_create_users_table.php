<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void{
        $table = $this->table('users');

        $table->addColumn('username', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('role', 'string', ['limit' => 50, 'default' => 'doctor'])
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}