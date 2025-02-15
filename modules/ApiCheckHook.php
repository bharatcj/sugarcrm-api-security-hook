<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ApiCheckHook
{
    /**
     * This method is invoked before any API call if the hook is registered.
     * 
     * @param array $event    Contains metadata about the request
     * @param array $arguments Additional arguments (not used here, but available)
     */
    public function beforeApiCheck($event, $arguments)
    {
        global $log;

        // Whitelisted IPs (Update these as per your security requirements)
        $whitelistedIPs = [
            '127.0.0.1',  // Localhost
            'XXX.XXX.XXX.XXX', // Replace with actual allowed IP
            'YYY.YYY.YYY.YYY'  // Replace with another allowed IP
        ];        

        $requestObject = $arguments['request'];
        $requestMethod = $requestObject->method;
        $routeInfo     = $requestObject->route;

        if (!empty($routeInfo['noLoginRequired']) && $routeInfo['noLoginRequired'] === true) {
            if ($requestMethod === 'GET') {
                $remoteAddress = $_SERVER['REMOTE_ADDR'];

                // If the request is not from localhost
                if ($remoteAddress !== '127.0.0.1') {
                    $this->logRequestDetails($remoteAddress, $arguments);
                }

                // Restrict unauthorized IPs
                if (!in_array($remoteAddress, $whitelistedIPs)) {
                    $requestHeaders = $requestObject->getRequestHeaders();

                    $unauthorizedAccessDetails = [
                        'Remote Address' => $remoteAddress,
                        'Request URI' => $_SERVER['REQUEST_URI'],
                        'User Agent' => $_SERVER['HTTP_USER_AGENT'],
                        'Forwarded For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'N/A',
                        'Request Headers' => $requestHeaders,
                    ];

                    $log->fatal('---- Unauthorized access attempt detected ----');
                    $log->fatal(print_r($unauthorizedAccessDetails, true));
                    $log->fatal('---- End of Unauthorized Access Log ----');

                    throw new SugarApiExceptionNotAuthorized('Unauthorized: Access denied.');
                }
            }
        }
    }

    /**
     * Logs unauthorized API access attempts
     */
    private function logRequestDetails($remoteAddress, $arguments)
    {
        $baseLogDir = __DIR__ . '/logs/ApiAccessLogs'; // Adjust log directory
        $currentDate = date('Y-m-d');
        $logDirectory = $baseLogDir . '/' . $currentDate;

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }

        $logFilePath = $logDirectory . '/' . str_replace('.', '_', $remoteAddress) . '.log';

        $logContent = [
            'Timestamp' => date('Y-m-d H:i:s'),
            'Remote Address' => $remoteAddress,
            'Request URI' => $_SERVER['REQUEST_URI'],
            'User Agent' => $_SERVER['HTTP_USER_AGENT'],
            'Forwarded For' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'N/A',
            'Arguments' => array_diff_key($arguments, ['api' => '']),
        ];

        file_put_contents($logFilePath, "---- Unauthorized Request ----\n" . print_r($logContent, true) . "\n", FILE_APPEND);
    }
}
