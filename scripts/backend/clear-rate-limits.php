<?php
/**
 * Rate Limit Diagnostic and Reset Tool
 *
 * Shows current rate limit status and allows clearing
 *
 * ⚠️ DELETE THIS FILE AFTER USE ⚠️
 * Access: https://erinik.com/scripts/backend/clear-rate-limits.php
 */

require_once __DIR__ . '/rate-limiter.php';

// Get action from query parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Limit Manager - Eriník</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #534741 0%, #3e332f 100%);
            color: #fff8e1;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success-box {
            background: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .warning-box {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .error-box {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        button {
            background: #534741;
            color: #fff8e1;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #3e332f;
        }
        button.danger {
            background: #f44336;
        }
        button.danger:hover {
            background: #d32f2f;
        }
        pre {
            background: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #534741;
            color: #fff8e1;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-active {
            background: #f44336;
            color: white;
        }
        .status-ok {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Rate Limit Manager</h1>
            <p>Diagnostic and Reset Tool</p>
        </div>

        <div class="content">
            <?php
            // Handle clear action
            if ($action === 'clear' && isset($_GET['confirm'])) {
                $rateLimitFile = __DIR__ . '/../../logs/rate-limits.json';
                if (file_exists($rateLimitFile)) {
                    unlink($rateLimitFile);
                    echo '<div class="success-box">';
                    echo '<h3>✅ Rate Limits Cleared!</h3>';
                    echo '<p>All rate limit data has been deleted. You can now submit forms without restrictions.</p>';
                    echo '<p><a href="?action=view"><button>View Current Status</button></a></p>';
                    echo '</div>';
                } else {
                    echo '<div class="info-box">';
                    echo '<h3>ℹ️ No Rate Limit Data</h3>';
                    echo '<p>No rate limit file exists. There are no active restrictions.</p>';
                    echo '</div>';
                }
            }
            // Show current status
            else {
                $clientIP = RateLimiter::getClientIP();
                $rateLimitFile = __DIR__ . '/../../logs/rate-limits.json';

                echo '<div class="info-box">';
                echo '<h3>📊 Current Status</h3>';
                echo '<p><strong>Your IP:</strong> <code>' . htmlspecialchars($clientIP) . '</code></p>';
                echo '<p><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p>';
                echo '</div>';

                if (file_exists($rateLimitFile)) {
                    $data = json_decode(file_get_contents($rateLimitFile), true);
                    $currentTime = time();

                    if (!empty($data)) {
                        echo '<h3>Active Rate Limits</h3>';
                        echo '<table>';
                        echo '<tr><th>Type</th><th>Hash</th><th>Attempts</th><th>Recent Attempts</th><th>Status</th></tr>';

                        $hasActiveLimit = false;
                        foreach ($data as $key => $timestamps) {
                            // Filter recent attempts (within 24 hours)
                            $recentAttempts = array_filter($timestamps, function($ts) use ($currentTime) {
                                return ($currentTime - $ts) < 86400;
                            });

                            if (empty($recentAttempts)) continue;

                            $type = strpos($key, 'ip_') === 0 ? 'IP Address' : 'Email Address';
                            $count = count($recentAttempts);

                            // Check if currently limited
                            $window = $type === 'IP Address' ? 3600 : 86400;
                            $limit = $type === 'IP Address' ? 3 : 5;
                            $activeCount = count(array_filter($recentAttempts, function($ts) use ($currentTime, $window) {
                                return ($currentTime - $ts) < $window;
                            }));

                            $isLimited = $activeCount >= $limit;
                            if ($isLimited) $hasActiveLimit = true;

                            $status = $isLimited
                                ? '<span class="status-badge status-active">RATE LIMITED</span>'
                                : '<span class="status-badge status-ok">OK</span>';

                            $lastAttempt = max($recentAttempts);
                            $timeAgo = round(($currentTime - $lastAttempt) / 60);

                            echo '<tr>';
                            echo '<td>' . $type . '</td>';
                            echo '<td><code>' . substr($key, 0, 16) . '...</code></td>';
                            echo '<td>' . $count . '</td>';
                            echo '<td>' . $timeAgo . ' minutes ago</td>';
                            echo '<td>' . $status . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';

                        if ($hasActiveLimit) {
                            echo '<div class="warning-box">';
                            echo '<h3>⚠️ Active Rate Limits Detected</h3>';
                            echo '<p>You currently have active rate limits that may be blocking form submissions.</p>';
                            echo '<p><strong>Limits:</strong></p>';
                            echo '<ul>';
                            echo '<li>IP Address: 3 submissions per hour</li>';
                            echo '<li>Email Address: 5 submissions per day</li>';
                            echo '</ul>';
                            echo '</div>';
                        }

                        echo '<div style="margin-top: 30px;">';
                        echo '<h3>Clear Rate Limits</h3>';
                        echo '<p>This will remove all rate limiting restrictions. Use this to reset after testing.</p>';
                        echo '<a href="?action=clear&confirm=1"><button class="danger">Clear All Rate Limits</button></a>';
                        echo '</div>';

                        // Show raw data
                        echo '<div style="margin-top: 30px;">';
                        echo '<h3>Raw Data (for debugging)</h3>';
                        echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
                        echo '</div>';

                    } else {
                        echo '<div class="success-box">';
                        echo '<h3>✅ No Active Limits</h3>';
                        echo '<p>The rate limit file exists but contains no data. You can submit forms freely.</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="success-box">';
                    echo '<h3>✅ No Rate Limit Data</h3>';
                    echo '<p>No rate limiting is currently active. You can submit forms freely.</p>';
                    echo '</div>';
                }

                echo '<div class="info-box" style="margin-top: 30px;">';
                echo '<h3>💡 Troubleshooting Tips</h3>';
                echo '<ol>';
                echo '<li>If you see active rate limits above, click "Clear All Rate Limits"</li>';
                echo '<li>If the issue persists, check browser console for CSRF token errors</li>';
                echo '<li>Try opening the contact form in an incognito/private window</li>';
                echo '<li>Make sure JavaScript is enabled in your browser</li>';
                echo '</ol>';
                echo '</div>';

                echo '<div class="error-box" style="margin-top: 20px;">';
                echo '<h3>⚠️ Security Reminder</h3>';
                echo '<p><strong>DELETE THIS FILE after troubleshooting!</strong></p>';
                echo '<pre style="text-align: left;">git rm scripts/backend/clear-rate-limits.php
git commit -m "chore: remove rate limit diagnostic tool"
git push origin main</pre>';
                echo '</div>';
            }
            ?>

            <div style="margin-top: 30px; text-align: center;">
                <a href="../../pages/contact.html"><button>Go to Contact Form</button></a>
                <a href="test-security.php"><button>Run Security Tests</button></a>
            </div>
        </div>
    </div>
</body>
</html>
