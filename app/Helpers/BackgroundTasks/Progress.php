<?php


namespace App\Helpers\BackgroundTasks;


use App\Helpers\SocketIo;
use Illuminate\Support\Str;

class Progress
{
    public $id;
    public $title;
    public $userId;
    public $startTime;
    private $logFile;
    private static $logsDir = 'background-tasks-progress';
    public $maxProgress;
    public $progress = 0;

    public function __construct($title, $maxProgress, $userId = null)
    {
        $this->title = $title;
        $this->userId = $userId;
        $this->maxProgress = $maxProgress;
        $this->id = rand() . time();
        $this->logFile = Str::slug($this->title) . '-' . date('Y-m-d-H-i-s');
        $this->startTime = date('Y-m-d H:i:s');
    }

    public function advance()
    {
        $this->progress ++;
    }

    public function setProgress($value)
    {
        $this->progress = $value;
    }

    public function log($msg, $advance = false)
    {
        if ($advance) $this->advance();
        $fullPath = storage_path("logs/" . static::$logsDir);
        if (!file_exists($fullPath)) mkdir($fullPath);
        logContent($msg, static::$logsDir . "/" . $this->logFile . ".log");

        $data = json_decode(json_encode($this), true);
        $data['msg'] = $msg;
        SocketIo::trigger("bkgProgressUpdated", $data);
    }

}
