<?php

class HealthRecordController {
    
    // GET all records
    public function getAllRecords() {
        require_once __DIR__ . '/HealthRecord.php';
        require_once __DIR__ . '/Patient.php';
        
        $records = HealthRecord::orderBy('id', 'desc')->get();
        
        // Link data manually since there are no hard DB relations
        foreach ($records as $record) {
            if (!empty($record->patient_id)) {
                $patient = Patient::where('patient_id', $record->patient_id)->first();
                if ($patient) {
                    $record->gender = $patient->gender; // override with true gender
                    $record->dob = $patient->dob;       // attach dob
                }
            }
        }
        
        echo json_encode(["status" => "success", "data" => $records]);
    }

    // POST create a new record
    public function createRecord() {
        require_once __DIR__ . '/HealthRecord.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data && !empty($_POST)) {
            $data = $_POST;
        }

        // Handle File Uploads
        $attachmentUrls = [];
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle multiple files
        if (isset($_FILES['attachments'])) {
            $files = $_FILES['attachments'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $filename = uniqid() . '_' . basename($files['name'][$i]);
                        if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $filename)) {
                            $attachmentUrls[] = '/uploads/' . $filename;
                        }
                    }
                }
            }
        }
        
        // Handle single file fallback
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $filename = uniqid() . '_' . basename($_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $filename)) {
                $attachmentUrls[] = '/uploads/' . $filename;
            }
        }

        // Auto-generate record_id (e.g. HR-1001)
        $lastRecord = HealthRecord::orderBy('id', 'desc')->first();
        $nextId = 1001;
        if ($lastRecord && preg_match('/HR-(\d+)/', $lastRecord->record_id, $matches)) {
            $nextId = intval($matches[1]) + 1;
        }

        $record = new HealthRecord();
        $record->record_id = 'HR-' . $nextId;
        $record->patient_name = $data['patient_name'] ?? '';
        $record->patient_id = $data['patient_id'] ?? '';
        $record->gender = $data['gender'] ?? 'Other';
        $record->status = $data['status'] ?? 'Active';
        $record->record_type = $data['record_type'] ?? '';
        $record->date = $data['date'] ?? date('Y-m-d');
        $record->blood_pressure = $data['blood_pressure'] ?? '';
        $record->pulse = $data['pulse'] ?? '';
        $record->weight = $data['weight'] ?? '';
        $record->height = $data['height'] ?? '';
        $record->bmi = $data['bmi'] ?? '';
        $record->attending_doctor = $data['attending_doctor'] ?? '';
        $record->note = $data['note'] ?? null;
        
        if (!empty($attachmentUrls)) {
            $record->attachment_url = json_encode($attachmentUrls);
        }
        
        $record->save();

        echo json_encode(["status" => "success", "message" => "Record added successfully!"]);
    }

    // PUT update existing record
    public function updateRecord($id) {
        require_once __DIR__ . '/HealthRecord.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data && !empty($_POST)) {
            $data = $_POST;
        }

        $record = HealthRecord::find($id);
        if (!$record) {
            echo json_encode(["status" => "error", "message" => "Record not found"]);
            return;
        }

        // Handle File Uploads
        $attachmentUrls = [];
        if (isset($_POST['existing_attachments'])) {
            $parsed = json_decode($_POST['existing_attachments'], true);
            if (is_array($parsed)) {
                $attachmentUrls = $parsed;
            }
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle multiple files
        if (isset($_FILES['attachments'])) {
            $files = $_FILES['attachments'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $filename = uniqid() . '_' . basename($files['name'][$i]);
                        if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $filename)) {
                            $attachmentUrls[] = '/uploads/' . $filename;
                        }
                    }
                }
            }
        }
        
        // Handle single file fallback
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $filename = uniqid() . '_' . basename($_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $filename)) {
                $attachmentUrls[] = '/uploads/' . $filename;
            }
        }

        $record->attachment_url = empty($attachmentUrls) ? null : json_encode($attachmentUrls);

        if (isset($data['patient_name'])) $record->patient_name = $data['patient_name'];
        if (isset($data['patient_id'])) $record->patient_id = $data['patient_id'];
        if (isset($data['gender'])) $record->gender = $data['gender'];
        if (isset($data['status'])) $record->status = $data['status'];
        if (isset($data['record_type'])) $record->record_type = $data['record_type'];
        if (isset($data['date'])) $record->date = $data['date'];
        if (isset($data['blood_pressure'])) $record->blood_pressure = $data['blood_pressure'];
        if (isset($data['pulse'])) $record->pulse = $data['pulse'];
        if (isset($data['weight'])) $record->weight = $data['weight'];
        if (isset($data['height'])) $record->height = $data['height'];
        if (isset($data['bmi'])) $record->bmi = $data['bmi'];
        if (isset($data['attending_doctor'])) $record->attending_doctor = $data['attending_doctor'];
        if (isset($data['note'])) $record->note = $data['note'];
        
        $record->save();

        echo json_encode(["status" => "success", "message" => "Record updated successfully!"]);
    }

    // DELETE a record
    public function deleteRecord($id) {
        require_once __DIR__ . '/HealthRecord.php';
        $record = HealthRecord::find($id);
        if ($record) {
            $record->delete();
        }
        echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
    }
}
