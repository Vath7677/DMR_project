<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

final class CreatePatientsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('patients');
        $table->addColumn('patient_id', 'string', ['limit' => 20])
              ->addColumn('first_name', 'string', ['limit' => 50]) 
              ->addColumn('last_name', 'string', ['limit' => 50])  
              ->addColumn('gender', 'enum', ['values' => ['Male', 'Female', 'Other']])
              ->addColumn('dob', 'date')
              ->addColumn('phone', 'string', ['limit' => 20])
              ->addColumn('address', 'text', ['null' => true])     
              ->addColumn('status', 'enum', ['values' => ['Active', 'Inactive'], 'default' => 'Active'])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['patient_id'], ['unique' => true])
              ->create();
    }
}