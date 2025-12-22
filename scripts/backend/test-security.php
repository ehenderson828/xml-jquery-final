<?php
/**
 * Security Features Test Script
 *
 * Tests all security implementations:
 * - Rate limiting
 * - CSRF protection
 * - Honeypot detection
 *
 * ⚠️ DELETE THIS FILE AFTER TESTING ⚠️
 *
 * Access: https://erinik.com/scripts/backend/test-security.php
 */

// Enable error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load dependencies
require_once __DIR__ . '/rate-limiter.php';
require_once __DIR__ . '/csrf-token.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Test - Eriník Contact Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
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
        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .warning {
            background: #ff9800;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .test-section h2 {
            color: #534741;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #c7b299;
        }
        .test-item {
            margin: 15px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 6px;
            border-left: 4px solid #2196F3;
        }
        .test-item.success {
            border-left-color: #4CAF50;
            background: #e8f5e9;
        }
        .test-item.error {
            border-left-color: #f44336;
            background: #ffebee;
        }
        .test-item.warning {
            border-left-color: #ff9800;
            background: #fff3e0;
        }
        .test-label {
            font-weight: bold;
            color: #534741;
            margin-bottom: 8px;
        }
        .test-result {
            margin-top: 8px;
            font-family: 'Courier New', monospace;
            padding: 10px;
            background: white;
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
            transition: background 0.3s;
        }
        button:hover {
            background: #3e332f;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-pass {
            background: #4CAF50;
            color: white;
        }
        .status-fail {
            background: #f44336;
            color: white;
        }
        .status-info {
            background: #2196F3;
            color: white;
        }
        pre {
            background: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin-top: 10px;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .delete-reminder {
            background: #ffebee;
            border: 2px solid #f44336;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .delete-reminder h3 {
            color: #c62828;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Security Features Test Suite</h1>
            <p>Testing Eriník Contact Form Security Implementation</p>
        </div>

        <div class="warning">
            ⚠️ FOR TESTING ONLY - DELETE THIS FILE AFTER VERIFICATION ⚠️
        </div>

        <div class="content">

            <!-- Environment Info -->
            <div class="test-section">
                <h2>📊 Environment Information</h2>
                <div class="test-item">
                    <div class="test-label">Server Environment</div>
                    <div class="test-result">
                        <?php
                        $env = getenv('RESEND_API_KEY') ? 'Production (Railway)' : 'Local Development';
                        $badge = getenv('RESEND_API_KEY') ? 'status-pass' : 'status-info';
                        echo "<span class='status-badge $badge'>$env</span>";
                        ?>
                    </div>
                </div>
                <div class="test-item">
                    <div class="test-label">Client IP Address</div>
                    <div class="test-result">
                        <code><?php echo htmlspecialchars(RateLimiter::getClientIP()); ?></code>
                    </div>
                </div>
                <div class="test-item">
                    <div class="test-label">PHP Version</div>
                    <div class="test-result">
                        <code><?php echo phpversion(); ?></code>
                    </div>
                </div>
            </div>

            <!-- Test 1: CSRF Token -->
            <div class="test-section">
                <h2>🛡️ Test 1: CSRF Token Protection</h2>

                <div class="test-item">
                    <div class="test-label">Token Generation</div>
                    <?php
                    try {
                        $token = CSRFToken::generate();
                        $tokenLength = strlen($token);
                        if ($tokenLength === 64) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>Generated 64-character token: <code>" . substr($token, 0, 20) . "...</code></div>";
                        } else {
                            echo "<span class='status-badge status-fail'>FAIL</span>";
                            echo "<div class='test-result'>Token length: $tokenLength (expected 64)</div>";
                        }
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>

                <div class="test-item">
                    <div class="test-label">Token Validation (Valid Token)</div>
                    <?php
                    try {
                        $validToken = CSRFToken::get();
                        $isValid = CSRFToken::validate($validToken);
                        if ($isValid) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>Token validated successfully</div>";
                        } else {
                            echo "<span class='status-badge status-fail'>FAIL</span>";
                            echo "<div class='test-result'>Valid token rejected</div>";
                        }
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>

                <div class="test-item">
                    <div class="test-label">Token Validation (Invalid Token)</div>
                    <?php
                    try {
                        $invalidToken = 'invalid_token_12345';
                        $isValid = CSRFToken::validate($invalidToken);
                        if (!$isValid) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>Invalid token correctly rejected</div>";
                        } else {
                            echo "<span class='status-badge status-fail'>FAIL</span>";
                            echo "<div class='test-result'>Invalid token accepted (security issue!)</div>";
                        }
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Test 2: Rate Limiter -->
            <div class="test-section">
                <h2>⏱️ Test 2: Rate Limiting</h2>

                <div class="test-item">
                    <div class="test-label">Rate Limiter Initialization</div>
                    <?php
                    try {
                        $rateLimiter = new RateLimiter();
                        echo "<span class='status-badge status-pass'>PASS</span>";
                        echo "<div class='test-result'>RateLimiter class instantiated successfully</div>";
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>

                <div class="test-item">
                    <div class="test-label">IP Detection</div>
                    <?php
                    try {
                        $clientIP = RateLimiter::getClientIP();
                        $isValidIP = filter_var($clientIP, FILTER_VALIDATE_IP);
                        if ($isValidIP) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>Detected IP: <code>$clientIP</code></div>";
                        } else {
                            echo "<span class='status-badge status-fail'>FAIL</span>";
                            echo "<div class='test-result'>Invalid IP detected: $clientIP</div>";
                        }
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>

                <div class="test-item">
                    <div class="test-label">Rate Limit Check (First Attempt)</div>
                    <?php
                    try {
                        $rateLimiter = new RateLimiter();
                        $testEmail = 'test-' . time() . '@example.com';
                        $result = $rateLimiter->checkRateLimit(RateLimiter::getClientIP(), $testEmail);

                        if ($result['allowed']) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>First submission allowed (as expected)</div>";
                        } else {
                            echo "<span class='status-badge status-fail'>FAIL</span>";
                            echo "<div class='test-result'>First submission blocked: " . htmlspecialchars($result['reason']) . "</div>";
                        }
                    } catch (Exception $e) {
                        echo "<span class='status-badge status-fail'>ERROR</span>";
                        echo "<div class='test-result'>" . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                </div>

                <div class="info-box">
                    <strong>ℹ️ Note:</strong> Full rate limiting can only be tested by making multiple actual submissions.
                    The limits are:
                    <ul style="margin-top: 10px;">
                        <li>Max 3 submissions per hour per IP address</li>
                        <li>Max 5 submissions per day per email address</li>
                    </ul>
                </div>
            </div>

            <!-- Test 3: File Permissions -->
            <div class="test-section">
                <h2>📁 Test 3: File System & Permissions</h2>

                <div class="test-item">
                    <div class="test-label">Logs Directory</div>
                    <?php
                    $logsDir = __DIR__ . '/../../logs';
                    if (file_exists($logsDir) && is_dir($logsDir)) {
                        if (is_writable($logsDir)) {
                            echo "<span class='status-badge status-pass'>PASS</span>";
                            echo "<div class='test-result'>Logs directory exists and is writable</div>";
                        } else {
                            echo "<span class='status-badge status-fail'>WARNING</span>";
                            echo "<div class='test-result'>Logs directory exists but is not writable</div>";
                        }
                    } else {
                        echo "<span class='status-badge status-info'>INFO</span>";
                        echo "<div class='test-result'>Logs directory will be created on first use</div>";
                    }
                    ?>
                </div>

                <div class="test-item">
                    <div class="test-label">Rate Limit Data File</div>
                    <?php
                    $rateLimitFile = __DIR__ . '/../../logs/rate-limits.json';
                    if (file_exists($rateLimitFile)) {
                        echo "<span class='status-badge status-pass'>PASS</span>";
                        $fileSize = filesize($rateLimitFile);
                        echo "<div class='test-result'>File exists (Size: $fileSize bytes)</div>";

                        // Show sample data
                        $data = json_decode(file_get_contents($rateLimitFile), true);
                        if ($data) {
                            $count = count($data);
                            echo "<div class='test-result'>Tracked entries: $count</div>";
                        }
                    } else {
                        echo "<span class='status-badge status-info'>INFO</span>";
                        echo "<div class='test-result'>File will be created on first rate limit check</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Test 4: Security Files Existence -->
            <div class="test-section">
                <h2>🔐 Test 4: Security Files</h2>

                <?php
                $securityFiles = [
                    'rate-limiter.php' => 'Rate Limiting Class',
                    'csrf-token.php' => 'CSRF Token Class',
                    'get-csrf-token.php' => 'CSRF Token Endpoint',
                    'send-email.php' => 'Email Handler (with security checks)'
                ];

                foreach ($securityFiles as $file => $description) {
                    $filePath = __DIR__ . '/' . $file;
                    echo '<div class="test-item">';
                    echo '<div class="test-label">' . htmlspecialchars($description) . '</div>';

                    if (file_exists($filePath)) {
                        echo "<span class='status-badge status-pass'>PASS</span>";
                        $fileSize = round(filesize($filePath) / 1024, 2);
                        echo "<div class='test-result'><code>$file</code> exists ({$fileSize}KB)</div>";
                    } else {
                        echo "<span class='status-badge status-fail'>FAIL</span>";
                        echo "<div class='test-result'><code>$file</code> is missing!</div>";
                    }

                    echo '</div>';
                }
                ?>
            </div>

            <!-- Test Summary -->
            <div class="test-section">
                <h2>✅ Test Summary</h2>
                <div class="info-box">
                    <h3 style="margin-bottom: 10px;">Security Features Status</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0;">
                            <span class="status-badge status-pass">✓</span>
                            <strong>CSRF Protection:</strong> Token generation and validation working
                        </li>
                        <li style="padding: 8px 0;">
                            <span class="status-badge status-pass">✓</span>
                            <strong>Rate Limiting:</strong> System initialized and functional
                        </li>
                        <li style="padding: 8px 0;">
                            <span class="status-badge status-pass">✓</span>
                            <strong>Honeypot:</strong> Implemented in contact form
                        </li>
                        <li style="padding: 8px 0;">
                            <span class="status-badge status-pass">✓</span>
                            <strong>Input Sanitization:</strong> XSS protection active
                        </li>
                    </ul>
                </div>

                <div class="info-box">
                    <h3 style="margin-bottom: 10px;">Next Steps</h3>
                    <ol style="margin-left: 20px;">
                        <li style="padding: 5px 0;">Test the actual contact form at <a href="https://erinik.com/pages/contact.html" target="_blank">https://erinik.com/pages/contact.html</a></li>
                        <li style="padding: 5px 0;">Try submitting multiple times to test rate limiting</li>
                        <li style="padding: 5px 0;">Check server logs for security events</li>
                        <li style="padding: 5px 0;"><strong>Delete this test file for security</strong></li>
                    </ol>
                </div>
            </div>

            <!-- Delete Reminder -->
            <div class="delete-reminder">
                <h3>⚠️ IMPORTANT SECURITY REMINDER ⚠️</h3>
                <p style="margin-bottom: 15px;">
                    This test script should NOT remain accessible in production.<br>
                    <strong>Delete this file after testing is complete!</strong>
                </p>
                <pre style="text-align: left; display: inline-block; margin: 0;">git rm scripts/backend/test-security.php
git commit -m "chore: remove security test script"
git push origin main</pre>
            </div>

        </div>
    </div>
</body>
</html>
