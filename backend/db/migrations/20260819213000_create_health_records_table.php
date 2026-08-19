<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

final class CreateHealthRecordsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('health_records');
        $table->addColumn('record_id', 'string', ['limit' => 50])
              ->addColumn('patient_name', 'string', ['limit' => 100])
              ->addColumn('patient_id', 'string', ['limit' => 50, 'null' => true])
              ->addColumn('gender', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'Active'])
              ->addColumn('record_type', 'string', ['limit' => 100])
              ->addColumn('date', 'date')
              ->addColumn('blood_pressure', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('pulse', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('weight', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('height', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('bmi', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('attending_doctor', 'string', ['limit' => 100, 'null' => true])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['record_id'], ['unique' => true])
              ->create();
    }
}
