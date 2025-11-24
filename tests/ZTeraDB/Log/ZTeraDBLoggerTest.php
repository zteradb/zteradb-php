<?php

use PHPUnit\Framework\TestCase;
use ZTeraDB\Log\ZTeraDBLogger;
use ZTeraDB\Exceptions\ValueError;


class ZTeraDBLoggerTest extends TestCase
{
    private $logFilePath;

    protected function setUp() {
      // Create a temporary log file for testing
      $this->logFilePath = sys_get_temp_dir() . '/zt_db_log_test.log';
    }

    protected function tearDown() {
      // Remove the log file after tests
      if (file_exists($this->logFilePath)) {
          unlink($this->logFilePath);
      }
    }

    public function testInitWithoutLogFile() {
      // Initialize logger with DEBUG level (10)
      ZTeraDBLogger::init(10);
      
      // Get handlers to verify that handlers are set for all levels >= DEBUG
      $handlers = ZTeraDBLogger::getHandlers();

      // Check that all levels >= DEBUG have callable handlers
      $this->assertTrue(is_callable($handlers['DEBUG']));
      $this->assertTrue(is_callable($handlers['INFO']));
      $this->assertTrue(is_callable($handlers['WARN']));
      $this->assertTrue(is_callable($handlers['ERROR']));
      $this->assertTrue(is_callable($handlers['CRITICAL']));
    }

    public function testInitWithLogFile() {
      // Initialize logger with INFO level (20) and specify a log file path
      ZTeraDBLogger::init(20, $this->logFilePath);
      
      // Get handlers to verify that handlers are set for all levels >= INFO
      $handlers = ZTeraDBLogger::getHandlers();

      // Check that only levels >= INFO have callable handlers
      $this->assertTrue(is_callable($handlers['INFO']));
      $this->assertTrue(is_callable($handlers['WARN']));
      $this->assertTrue(is_callable($handlers['ERROR']));
      $this->assertTrue(is_callable($handlers['CRITICAL']));
      // $this->assertFalse(is_callable($handlers['DEBUG']));  // DEBUG should be inactive
    }

    public function testLogMessageToFile() {
      // Initialize logger with INFO level and specify a log file path
      ZTeraDBLogger::init(20, $this->logFilePath);

      // Capture the log message by calling the INFO method
      ZTeraDBLogger::info("Test log message");

      // Verify the log file contains the log message
      $logContents = file_get_contents($this->logFilePath);
      $this->assertContains("Test log message", $logContents);
      $this->assertContains("[INFO]", $logContents);
    }

    public function testLogFileCreationWhenNotExists() {
      // Initialize logger with ERROR level and a new log file path
      $logPath = sys_get_temp_dir() . '/new_log_file.log';
      ZTeraDBLogger::init(40, $logPath);

      // Make sure the file was created
      $this->assertFileExists($logPath);

      // Cleanup after test
      unlink($logPath);
    }

    public function testLogFileCreationFails() {
      // Attempt to create a log file in an invalid directory
      $invalidLogPath = '/invalid/directory/zt_db_log_test.log';

      // Expect an exception to be thrown
      $this->expectException(ValueError::class);
      $this->expectExceptionMessage('Log path does not exists');
      ZTeraDBLogger::init(10, $invalidLogPath);
    }

    public function testDynamicLogging() {
      // Initialize logger with DEBUG level (10)
      ZTeraDBLogger::init(10, $this->logFilePath);

      // Mock the log function for the current test
      $this->expectOutputRegex('/\[DEBUG\].*Test dynamic logging/');

      // Trigger the dynamic log level method
      ZTeraDBLogger::debug("Test dynamic logging");
      echo file_get_contents($this->logFilePath);
      // $this->assertContains("Test dynamic logging", $logContents);
    }
}

?>