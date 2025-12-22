<?php
/**
 * Rate Limiter for Contact Form
 *
 * Prevents spam by limiting submission rates based on:
 * - IP address: max 3 submissions per hour
 * - Email address: max 5 submissions per day
 */

class RateLimiter {
    private $dataFile;
    private $ipLimit = 3;        // Max submissions per hour per IP
    private $ipWindow = 3600;    // 1 hour in seconds
    private $emailLimit = 5;     // Max submissions per day per email
    private $emailWindow = 86400; // 24 hours in seconds

    public function __construct() {
        // Store rate limit data in logs directory
        $logDir = __DIR__ . '/../../logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $this->dataFile = $logDir . '/rate-limits.json';

        // Clean up old entries on initialization
        $this->cleanupOldEntries();
    }

    /**
     * Check if the request should be rate limited
     *
     * @param string $ip IP address
     * @param string $email Email address
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkRateLimit($ip, $email) {
        $data = $this->loadData();
        $currentTime = time();

        // Check IP rate limit
        $ipKey = 'ip_' . md5($ip);
        if (isset($data[$ipKey])) {
            $ipAttempts = array_filter($data[$ipKey], function($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < $this->ipWindow;
            });

            if (count($ipAttempts) >= $this->ipLimit) {
                $oldestAttempt = min($ipAttempts);
                $waitTime = ceil(($oldestAttempt + $this->ipWindow - $currentTime) / 60);
                return [
                    'allowed' => false,
                    'reason' => "Too many submissions from your IP address. Please wait $waitTime minutes before trying again."
                ];
            }
        }

        // Check email rate limit
        $emailKey = 'email_' . md5(strtolower($email));
        if (isset($data[$emailKey])) {
            $emailAttempts = array_filter($data[$emailKey], function($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < $this->emailWindow;
            });

            if (count($emailAttempts) >= $this->emailLimit) {
                $oldestAttempt = min($emailAttempts);
                $waitTime = ceil(($oldestAttempt + $this->emailWindow - $currentTime) / 3600);
                return [
                    'allowed' => false,
                    'reason' => "Too many submissions from this email address. Please wait $waitTime hours before trying again."
                ];
            }
        }

        return ['allowed' => true, 'reason' => ''];
    }

    /**
     * Record a submission attempt
     *
     * @param string $ip IP address
     * @param string $email Email address
     */
    public function recordAttempt($ip, $email) {
        $data = $this->loadData();
        $currentTime = time();

        // Record IP attempt
        $ipKey = 'ip_' . md5($ip);
        if (!isset($data[$ipKey])) {
            $data[$ipKey] = [];
        }
        $data[$ipKey][] = $currentTime;

        // Record email attempt
        $emailKey = 'email_' . md5(strtolower($email));
        if (!isset($data[$emailKey])) {
            $data[$emailKey] = [];
        }
        $data[$emailKey][] = $currentTime;

        $this->saveData($data);
    }

    /**
     * Load rate limit data from file
     *
     * @return array
     */
    private function loadData() {
        if (!file_exists($this->dataFile)) {
            return [];
        }

        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Save rate limit data to file
     *
     * @param array $data
     */
    private function saveData($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->dataFile, $json, LOCK_EX);
    }

    /**
     * Clean up old entries to keep file size manageable
     */
    private function cleanupOldEntries() {
        $data = $this->loadData();
        $currentTime = time();
        $modified = false;

        foreach ($data as $key => $timestamps) {
            // Remove entries older than 24 hours
            $filtered = array_filter($timestamps, function($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < $this->emailWindow;
            });

            if (count($filtered) === 0) {
                unset($data[$key]);
                $modified = true;
            } elseif (count($filtered) < count($timestamps)) {
                $data[$key] = array_values($filtered);
                $modified = true;
            }
        }

        if ($modified) {
            $this->saveData($data);
        }
    }

    /**
     * Get client IP address (handles proxies and load balancers)
     *
     * @return string
     */
    public static function getClientIP() {
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',    // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Proxy
            'HTTP_X_REAL_IP',            // Nginx proxy
            'REMOTE_ADDR'                // Direct connection
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                $ip = trim(explode(',', $_SERVER[$header])[0]);

                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
                // Allow private IPs for local development
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0'; // Fallback
    }
}
?>
