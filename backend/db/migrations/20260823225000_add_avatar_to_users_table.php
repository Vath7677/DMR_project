<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAvatarToUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        if (!$table->hasColumn('avatar')) {
            $table->addColumn('avatar', 'string', ['null' => true, 'limit' => 255, 'after' => 'role'])
                  ->update();
        }
    }
}
