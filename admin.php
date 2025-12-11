<?php
// Admin credentials - CHANGE THESE!
$ADMIN_USERNAME = 'admin';
$ADMIN_PASSWORD = 'birthday75';

// Start session for authentication
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $ADMIN_USERNAME && $_POST['password'] === $ADMIN_PASSWORD) {
        $_SESSION['authenticated'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $loginError = 'Invalid username or password';
    }
}

// Check if authenticated
$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - RSVP Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #000000;
            min-height: 100vh;
            padding: 20px;
            color: #ccc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Login Form Styles */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-box {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border: 1px solid rgba(212, 175, 55, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 0 80px rgba(212, 175, 55, 0.2);
        }

        .login-title {
            font-family: 'Cinzel', serif;
            font-size: 1.8em;
            background: linear-gradient(135deg, #f4d03f 0%, #d4af37 50%, #c5a028 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        .login-form label {
            display: block;
            font-size: 0.8em;
            font-weight: 600;
            color: #d4af37;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .login-form input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            background: rgba(0, 0, 0, 0.5);
            font-size: 0.95em;
            font-family: 'Montserrat', sans-serif;
            color: #ccc;
            transition: all 0.3s;
        }

        .login-form input:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #d4af37 0%, #c5a028 100%);
            color: #000;
            border: none;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .login-btn:hover {
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.5);
            transform: translateY(-2px);
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
            padding: 12px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        /* Dashboard Styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
        }

        .dashboard-title {
            font-family: 'Cinzel', serif;
            font-size: 2em;
            background: linear-gradient(135deg, #f4d03f 0%, #d4af37 50%, #c5a028 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
        }

        .logout-btn {
            padding: 10px 20px;
            background: transparent;
            color: #d4af37;
            border: 1px solid #d4af37;
            font-size: 0.85em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(212, 175, 55, 0.1);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .summary-card {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border: 1px solid rgba(212, 175, 55, 0.3);
            padding: 25px;
            text-align: center;
        }

        .summary-card .number {
            font-family: 'Cinzel', serif;
            font-size: 3em;
            color: #d4af37;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 10px;
        }

        .summary-card .label {
            font-size: 0.85em;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-card.attending .number {
            color: #4caf50;
        }

        .summary-card.not-attending .number {
            color: #f44336;
        }

        .summary-card.veg .number {
            color: #8bc34a;
        }

        .summary-card.nonveg .number {
            color: #ff9800;
        }

        /* Table Styles */
        .table-container {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border: 1px solid rgba(212, 175, 55, 0.3);
            overflow-x: auto;
        }

        .table-title {
            font-family: 'Cinzel', serif;
            font-size: 1.3em;
            color: #d4af37;
            padding: 20px 25px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        th {
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
            font-weight: 600;
            font-size: 0.8em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        td {
            font-size: 0.95em;
            color: #ccc;
        }

        .status-yes {
            color: #4caf50;
            font-weight: 600;
        }

        .status-no {
            color: #f44336;
            font-weight: 600;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-style: italic;
        }

        .refresh-note {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.85em;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .dashboard-title {
                font-size: 1.5em;
            }

            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary-card .number {
                font-size: 2.2em;
            }

            th, td {
                padding: 12px 15px;
                font-size: 0.85em;
            }
        }

        @media (max-width: 480px) {
            .summary-cards {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .summary-card {
                padding: 15px;
            }

            .summary-card .number {
                font-size: 1.8em;
            }

            .summary-card .label {
                font-size: 0.75em;
            }
        }
    </style>
</head>
<body>
<?php if (!$isAuthenticated): ?>
    <!-- Login Form -->
    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title">Admin Login</h1>

            <?php if (isset($loginError)): ?>
                <div class="error-message"><?php echo htmlspecialchars($loginError); ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Dashboard -->
    <div class="container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">RSVP Dashboard</h1>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>

        <?php
        // Read and parse RSVP data
        $rsvpDir = __DIR__ . '/rsvps';
        $logFile = $rsvpDir . '/rsvp_log.txt';

        $rsvps = [];
        $totalGuests = 0;
        $totalVeg = 0;
        $totalNonVeg = 0;
        $totalAttending = 0;
        $totalNotAttending = 0;

        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $lines = explode("\n", trim($logContent));

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;

                // Parse: 2025-12-11 10:30:00 | Name | YES | +1234567890 | 3 guests | 2 Vegetarian, 1 Non-Vegetarian
                $parts = explode(' | ', $line);

                if (count($parts) >= 6) {
                    $timestamp = trim($parts[0]);
                    $name = trim($parts[1]);
                    $attending = trim($parts[2]);
                    $phone = trim($parts[3]);
                    $guestsStr = trim($parts[4]);
                    $foodStr = trim($parts[5]);

                    // Parse guest count
                    preg_match('/(\d+)/', $guestsStr, $guestMatch);
                    $guestCount = isset($guestMatch[1]) ? (int)$guestMatch[1] : 1;

                    // Parse food preferences
                    $vegCount = 0;
                    $nonVegCount = 0;

                    if ($attending === 'YES') {
                        if (preg_match('/(\d+)\s*Vegetarian/i', $foodStr, $vegMatch)) {
                            $vegCount = (int)$vegMatch[1];
                        } elseif (stripos($foodStr, 'vegetarian') !== false && stripos($foodStr, 'non') === false) {
                            $vegCount = $guestCount;
                        }

                        if (preg_match('/(\d+)\s*Non-Vegetarian/i', $foodStr, $nonVegMatch)) {
                            $nonVegCount = (int)$nonVegMatch[1];
                        } elseif (stripos($foodStr, 'non-vegetarian') !== false) {
                            $nonVegCount = $guestCount;
                        }

                        $totalGuests += $guestCount;
                        $totalVeg += $vegCount;
                        $totalNonVeg += $nonVegCount;
                        $totalAttending++;
                    } else {
                        $totalNotAttending++;
                    }

                    $rsvps[] = [
                        'timestamp' => $timestamp,
                        'name' => $name,
                        'attending' => $attending,
                        'phone' => $phone,
                        'guests' => $guestCount,
                        'veg' => $vegCount,
                        'nonveg' => $nonVegCount
                    ];
                }
            }
        }
        ?>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="number"><?php echo count($rsvps); ?></div>
                <div class="label">Total Responses</div>
            </div>
            <div class="summary-card attending">
                <div class="number"><?php echo $totalAttending; ?></div>
                <div class="label">Attending</div>
            </div>
            <div class="summary-card not-attending">
                <div class="number"><?php echo $totalNotAttending; ?></div>
                <div class="label">Not Attending</div>
            </div>
            <div class="summary-card">
                <div class="number"><?php echo $totalGuests; ?></div>
                <div class="label">Total Guests</div>
            </div>
            <div class="summary-card veg">
                <div class="number"><?php echo $totalVeg; ?></div>
                <div class="label">Vegetarian</div>
            </div>
            <div class="summary-card nonveg">
                <div class="number"><?php echo $totalNonVeg; ?></div>
                <div class="label">Non-Vegetarian</div>
            </div>
        </div>

        <!-- RSVP Table -->
        <div class="table-container">
            <div class="table-title">RSVP List</div>

            <?php if (empty($rsvps)): ?>
                <div class="no-data">No RSVPs received yet</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Attending</th>
                            <th>Guests</th>
                            <th>Veg</th>
                            <th>Non-Veg</th>
                            <th>Phone</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rsvps as $index => $rsvp): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($rsvp['name']); ?></td>
                                <td class="<?php echo $rsvp['attending'] === 'YES' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $rsvp['attending']; ?>
                                </td>
                                <td><?php echo $rsvp['attending'] === 'YES' ? $rsvp['guests'] : '-'; ?></td>
                                <td><?php echo $rsvp['attending'] === 'YES' ? $rsvp['veg'] : '-'; ?></td>
                                <td><?php echo $rsvp['attending'] === 'YES' ? $rsvp['nonveg'] : '-'; ?></td>
                                <td><?php echo htmlspecialchars($rsvp['phone']); ?></td>
                                <td><?php echo htmlspecialchars($rsvp['timestamp']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: rgba(212, 175, 55, 0.15);">
                            <td colspan="3" style="font-weight: 600; color: #d4af37;">TOTALS</td>
                            <td style="font-weight: 600; color: #d4af37;"><?php echo $totalGuests; ?></td>
                            <td style="font-weight: 600; color: #8bc34a;"><?php echo $totalVeg; ?></td>
                            <td style="font-weight: 600; color: #ff9800;"><?php echo $totalNonVeg; ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>

        <p class="refresh-note">Refresh the page to see the latest RSVPs</p>
    </div>
<?php endif; ?>
</body>
</html>
