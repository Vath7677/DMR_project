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
        $patient->status = $data['status'] ?? 'Active';
        $patient->save();

        echo json_encode([
            "status" => "success", 
            "message" => "Patient added successfully!",
            "patient" => [
                "id" => $patient->patient_id,
                "name" => $patient->first_name . ' ' . $patient->last_name
            ]
        ]);
    }

    // PUT
    public function updatePatient($id) {
        require_once __DIR__ . '/Patient.php';
        $data = json_decode(file_get_contents('php://input'), true);

        $patient = Patient::find($id);
        if (!$patient) {
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

        echo json_encode(["status" => "success", "message" => "Patient updated successfully!"]);
    }

    // 4. DELETE
    public function deletePatient($id) {
        require_once __DIR__ . '/Patient.php';
        $patient = Patient::find($id);
        if ($patient) {
            $patient->delete();
        }
        echo json_encode(["status" => "success", "message" => "Patient deleted successfully!"]);
    }

    public function getPatientById($id) {
        require_once __DIR__ . '/Patient.php';
        $patient = Patient::find($id);
        if ($patient) {
            echo json_encode(["status" => "success", "data" => $patient]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Patient not found"]);
        }
    }
}