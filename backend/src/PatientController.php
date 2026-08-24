<?php

class PatientController {
    
    // GET
    public function getAllPatients() {
        require_once __DIR__ . '/Patient.php';
        $patients = Patient::all();
        echo json_encode(["status" => "success", "data" => $patients]);
    }

    // POST
    public function createPatient() {
        require_once __DIR__ . '/Patient.php';
        $data = json_decode(file_get_contents('php://input'), true);
        
        // 🛡️ SECURITY (OWASP A03/XSS): Sanitize inputs (Strip HTML tags)
        if (is_array($data)) {
            array_walk_recursive($data, function(&$item) {
                if (is_string($item)) $item = strip_tags($item);
            });
        }

        $lastPatient = Patient::orderBy('id', 'desc')->first();
        $nextId = 1001;
        if ($lastPatient && preg_match('/P-(\d+)/', $lastPatient->patient_id, $matches)) {
            $nextId = intval($matches[1]) + 1;
        }

        $patient = new Patient();
        $patient->patient_id = 'P-' . $nextId;
        $patient->first_name = $data['first_name'] ?? '';
        $patient->last_name = $data['last_name'] ?? '';
        $patient->gender = $data['gender'] ?? 'Other';
        $patient->dob = $data['dob'] ?? date('Y-m-d');
        $patient->phone = $data['phone'] ?? '';
        $patient->address = $data['address'] ?? '';
        $patient->save();

        require_once __DIR__ . '/Activity.php';
        $fullName = trim($patient->first_name . ' ' . $patient->last_name);
        Activity::log(
            'patient_created',
            'New Patient Registered',
            "{$fullName} ({$patient->patient_id}) was added to the system",
            'Staff',
            'patient'
        );

        echo json_encode([
            "status" => "success", 
            "message" => "Patient added successfully!",
            "patient" => [
                "id" => $patient->patient_id,
                "name" => $fullName
            ]
        ]);
    }

    // PUT
    public function updatePatient($id) {
        require_once __DIR__ . '/Patient.php';
        $data = json_decode(file_get_contents('php://input'), true);
        
        // 🛡️ SECURITY (OWASP A03/XSS): Sanitize inputs (Strip HTML tags)
        if (is_array($data)) {
            array_walk_recursive($data, function(&$item) {
                if (is_string($item)) $item = strip_tags($item);
            });
        }

        $patient = is_numeric($id) ? Patient::find($id) : Patient::where('patient_id', $id)->first();
        if (!$patient) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Patient not found"]);
            return;
        }

        if (isset($data['first_name'])) $patient->first_name = $data['first_name'];
        if (isset($data['last_name'])) $patient->last_name = $data['last_name'];
        if (isset($data['gender'])) $patient->gender = $data['gender'];
        if (isset($data['dob'])) $patient->dob = $data['dob'];
        if (isset($data['phone'])) $patient->phone = $data['phone'];
        if (isset($data['address'])) $patient->address = $data['address'];
        if (isset($data['status'])) $patient->status = $data['status'];
        $patient->save();

        require_once __DIR__ . '/Activity.php';
        $fullName = trim($patient->first_name . ' ' . $patient->last_name);
        Activity::log(
            'patient_updated',
            'Patient Profile Updated',
            "Updated details for {$fullName} ({$patient->patient_id})",
            'Staff',
            'patient'
        );

        echo json_encode(["status" => "success", "message" => "Patient updated successfully!"]);
    }

    // 4. DELETE
    public function deletePatient($id) {
        require_once __DIR__ . '/Patient.php';
        $patient = is_numeric($id) ? Patient::find($id) : Patient::where('patient_id', $id)->first();
        if ($patient) {
            require_once __DIR__ . '/Activity.php';
            $fullName = trim($patient->first_name . ' ' . $patient->last_name);
            $pid = $patient->patient_id;
            $patient->delete();
            Activity::log(
                'patient_deleted',
                'Patient Deleted',
                "Patient {$fullName} ({$pid}) was removed from the system",
                'Staff',
                'delete'
            );
        }
        echo json_encode(["status" => "success", "message" => "Patient deleted successfully!"]);
    }

    public function getPatientById($id) {
        require_once __DIR__ . '/Patient.php';
        $patient = is_numeric($id) ? Patient::find($id) : Patient::where('patient_id', $id)->first();
        if ($patient) {
            echo json_encode(["status" => "success", "data" => $patient]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Patient not found"]);
        }
    }
}