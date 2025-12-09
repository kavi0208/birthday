<?php
// Set content type
header('Content-Type: application/json');

// Allow CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON data from request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate required fields
if (!isset($data['name']) || empty($data['name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

if (!isset($data['attending']) || empty($data['attending'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Attendance selection is required']);
    exit;
}

// Create rsvps directory if it doesn't exist
$rsvpDir = __DIR__ . '/rsvps';
if (!file_exists($rsvpDir)) {
    mkdir($rsvpDir, 0755, true);
}

// Prepare data
$name = $data['name'];
$phone = isset($data['phone']) ? $data['phone'] : 'N/A';
$attending = $data['attending'] === 'yes' ? 'YES' : 'NO';
$guests = isset($data['guests']) ? $data['guests'] : '1';
$food = isset($data['food']) ? ucfirst($data['food']) : 'N/A';
$message = isset($data['message']) && !empty($data['message']) ? $data['message'] : 'N/A';
$timestamp = isset($data['timestamp']) ? $data['timestamp'] : date('c');
$formattedDate = date('F j, Y g:i A', strtotime($timestamp));

// Check for duplicates in existing RSVPs
$logFile = $rsvpDir . '/rsvp_log.txt';
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $logLines = explode("\n", $logContent);

    foreach ($logLines as $line) {
        if (empty(trim($line))) continue;

        $parts = explode(' | ', $line);
        if (count($parts) >= 4) {
            $existingName = trim($parts[1]);
            $existingPhone = trim($parts[3]);

            // Check if name matches (case-insensitive)
            if (strcasecmp($existingName, $name) === 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'You have already submitted an RSVP. For modifications, please call Kavi on 52514201.'
                ]);
                exit;
            }

            // Check if phone matches (if both are provided and not N/A)
            if ($phone !== 'N/A' && $existingPhone !== 'N/A' && $phone === $existingPhone) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'You have already submitted an RSVP. For modifications, please call Kavi on 52514201.'
                ]);
                exit;
            }
        }
    }
}

// Create file content
$fileContent = "Birthday RSVP - Mr Neerunjun Daboo's 75th Birthday
====================================================

Name: $name
Phone: $phone
Attending: $attending
Number of Guests: $guests
Food Preference: $food
Message: $message
Submitted: $formattedDate

====================================================
Event Details:
Date: 27 December 2025
Time: 11:00 - 16:00
Location: Quatre-Bornes @Kavi & Lauri house
";

// Create safe filename
$safeName = preg_replace('/[^a-z0-9]/i', '_', $name);
$timestamp_filename = time();
$filename = "RSVP_{$safeName}_{$timestamp_filename}.txt";
$filepath = $rsvpDir . '/' . $filename;

// Save file
try {
    $result = file_put_contents($filepath, $fileContent);

    if ($result === false) {
        throw new Exception('Failed to write file');
    }

    // Log to a summary file as well
    $logEntry = date('Y-m-d H:i:s') . " | $name | $attending | $phone | $guests guests | $food\n";
    file_put_contents($rsvpDir . '/rsvp_log.txt', $logEntry, FILE_APPEND);

    echo json_encode([
        'success' => true,
        'message' => 'RSVP received successfully',
        'filename' => $filename
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error saving RSVP: ' . $e->getMessage()
    ]);
}
?>
