<?php

enum LogType: string
{
    case TextLog = 'TextLog';
    case FileLog = 'FileLog';
    case DatabaseLog = 'DatabaseLog';
}

/**
 * Abstract class Log
 */
abstract class Log {
    /** Abstract methods */
    abstract public function log($message);

    abstract public function showLog();

    /**
     * Function formatMessage
     * @param string $message
     * @return string
     */
    static function formatMessage($message): string {
        return "[" . date("Y-m-d H:i:s") . "] ==> " . $message;
    }
}

/**
 * Class TextLog
 * Outputs Log text on screen
 */
class TextLog extends Log {
    protected $messages;
    /** Constructor */
    public function __construct() {
        $this->messages = array();
    }
    /**
     * function log
     * @param string $message
     * @return void
     */
    public function log($message): void {
        array_push($this->messages, Log::formatMessage($message));
        print_r(Log::formatMessage($message) . "\n");
    }

    /**
     * function showLog
     * @return string
     */
    public function showLog(): string {
        return print_r($this->messages);
    }

    public function __destruct() {
        print_r("TextLog Destructor called\n");
        // fclose($this->file);
    }
}

/**
 * Class FileLog
 * Writes Log to a text file
 */
class FileLog extends Log {
    protected $file_path;
    // protected $file;

    /** Constructor */
    public function __construct($file_path=null) {
        /** Parent constructor */
        // parent::__construct();
        $file_path = $file_path ?? "./logs/" . date('Y') . "/" . date('m') . "/" . date('d') . "/log_" . date("YmdHis") . ".txt";
        self::prepareFilePath($file_path);
        $this->file_path = $file_path;
    }

    /** Public Methods */
    public function log($message) {
        $file = fopen($this->file_path, "a");
        fwrite($file, Log::formatMessage($message) . "\n");
        fclose($file);
    }

    public function showLog(): void {
        print_r(file_get_contents($this->file_path));
    }

    /** Private Methods */
    private function prepareFilePath($file_path): void{
        $dirname = dirname($file_path);
        if (!is_dir($dirname))
        {
            mkdir($dirname, 0755, true);
        }
    }
    /** Destructor */
    public function __destruct() {
        print_r("FileLog Destructor called\n");
    }
}

/**
 * Class DatabaseLog
 * Writes Log to DDBB
 */
// @TODO: Implement DatabaseLog. This is a base class for the DatabaseLog implementation. Inherited classes should implement different database connections and queries.
class DatabaseLog extends Log {
    public function log($message) {
        print_r("DatabaseLog: " . Log::formatMessage($message) . "\n");
    //     array_push($this->messages, Log::formatMessage($message));
    }

    // public function log() {
    //     // Database connection and query to save the message
    //     // Replace the placeholders with your actual database credentials
    //     $host = 'your_host';
    //     $username = 'your_username';
    //     $password = 'your_password';
    //     $database = 'your_database';

    //     $conn = new mysqli($host, $username, $password, $database);

    //     if ($conn->connect_error) {
    //         die("Connection failed: " . $conn->connect_error);
    //     }

    //     $sql = "INSERT INTO logs (message) VALUES ('$this->messages')";

    //     if ($conn->query($sql) === TRUE) {
    //         echo "Message logged to database successfully";
    //     } else {
    //         echo "Error logging message to database: " . $conn->error;
    //     }

    //     $conn->close();
    // }

    public function showLog() {
        NULL;
    }

    public function __destruct() {
        print_r("DatabaseLog Destructor called\n");
    }
}