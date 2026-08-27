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

        // 🛡️ SECURITY (OWASP A03/XSS): Sanitize inputs (Strip HTML tags)
        if (is_array($data)) {
            array_walk_recursive($data, function(&$item) {
                if (is_string($item)) $item = strip_tags($item);
            });
        }

        // Handle File Uploads
        $attachmentUrls = [];

        $uploadDir = __DIR__ . "/../public/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }



        // Handle multiple files
        if (isset($_FILES['attachments'])) {
            $files = $_FILES['attachments'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
                        if (!in_array($ext, $allowed)) continue;
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
            $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
            if (in_array($ext, $allowed)) {
            $filename = uniqid() . '_' . basename($_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $filename)) {
                $attachmentUrls[] = '/uploads/' . $filename;
            }
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

        require_once __DIR__ . '/Activity.php';
        Activity::log(
            'record_created',
            'Health Record Added',
            "{$record->record_type} recorded for {$record->patient_name} (BP: {$record->blood_pressure})",
            $record->attending_doctor ?: 'Doctor',
            'vitals'
        );

        echo json_encode(["status" => "success", "message" => "Record added successfully!"]);
    }

    // PUT update existing record
    public function updateRecord($id) {
        require_once __DIR__ . '/HealthRecord.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data && !empty($_POST)) {
            $data = $_POST;
        }

        // 🛡️ SECURITY (OWASP A03/XSS): Sanitize inputs (Strip HTML tags)
        if (is_array($data)) {
            array_walk_recursive($data, function(&$item) {
                if (is_string($item)) $item = strip_tags($item);
            });
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

        // Delete orphaned files (removed from UI)
        $oldAttachments = $record->attachment_url ? json_decode($record->attachment_url, true) : [];
        if (is_array($oldAttachments)) {
            foreach ($oldAttachments as $oldFile) {
                if (!in_array($oldFile, $attachmentUrls)) {
                    // File was deleted from UI
                    $filePath = __DIR__ . '/../public' . $oldFile;
                    if (file_exists($filePath) && is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }

        // Handle multiple files
        if (isset($_FILES['attachments'])) {
            $files = $_FILES['attachments'];
            if (is_array($files['name'])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
                        if (!in_array($ext, $allowed)) continue;
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
            $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];
            if (in_array($ext, $allowed)) {
            $filename = uniqid() . '_' . basename($_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $filename)) {
                $attachmentUrls[] = '/uploads/' . $filename;
            }
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

        require_once __DIR__ . '/Activity.php';
        Activity::log(
            'record_updated',
            'Health Record Updated',
            "Updated {$record->record_type} encounter for {$record->patient_name}",
            $record->attending_doctor ?: 'Doctor',
            'record'
        );

        echo json_encode(["status" => "success", "message" => "Record updated successfully!"]);
    }
    // DELETE a record
    public function deleteRecord($id) {
        require_once __DIR__ . "/HealthRecord.php";
        $record = HealthRecord::find($id);
        if ($record) {
            // Delete associated physical files
            $attachments = $record->attachment_url ? json_decode($record->attachment_url, true) : [];
            if (is_array($attachments)) {
                foreach ($attachments as $fileUrl) {
                    $filePath = __DIR__ . "/../public" . $fileUrl;
                    if (file_exists($filePath) && is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            $pName = $record->patient_name;
            $rType = $record->record_type;
            $doc = $record->attending_doctor;
            $record->delete();

            require_once __DIR__ . '/Activity.php';
            Activity::log(
                'record_deleted',
                'Health Record Deleted',
                "Deleted {$rType} record for {$pName}",
                $doc ?: 'Staff',
                'delete'
            );
        }
        echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
    }
}
