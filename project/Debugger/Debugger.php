<?php

/**
 * Class Debugger
 */

/**
 * Class Debugger
 */

class Debugger
{

    /** LEVEL_FATAL is the level for message with die command */
    const LEVEL_FATAL = 100;
    /** LEVEL_ERROR is the level for error message */
    const LEVEL_ERROR = 2;
    /** LEVEL_WARN is the level for error message */
    const LEVEL_WARN = 1;
    /** LEVEL_INFO is the level for info message */
    const LEVEL_INFO = 0;

    /** MODE_REWRITE re-write the file every log */
    const MODE_REWRITE = 'w+';
    /** MODE_APPEND append the file every log */
    const MODE_APPEND = 'a+';

    /** @var SplString $pathOutput Path for the output of the debugger */
    private $pathOutput;
    /** @var SplInt $levelDebug Level of the debug */
    private $levelDebug;
    /** @var resource $fileStream Stream on the file */
    private $fileStream;
    /** @var mixed|SplString|string $modeOfWrite Modality of write style in the log file */
    private $modeOfWrite;
    /** @var string $prefix The prefix of every line in the logger */
    private $prefix;

    /**
     * Debugger constructor.
     * @param mixed | SplString $path
     * @param mixed | SplInt $level
     * @param mixed | SplString $modeOfWrite
     */
    public function __construct($path = './', $level = self::LEVEL_INFO, $modeOfWrite = self::MODE_APPEND)
    {
        if (is_dir($path)) {
            $this->pathOutput = $path . 'system.log';
        }
        elseif (is_file($path)) {
            $this->pathOutput = $path;
        }
        else {
            $this->pathOutput = './' . $path;
        }
        $this->levelDebug = $level;
        $this->modeOfWrite = $modeOfWrite;
        $this->fileStream = false;
        $this->prefix = '"TIME: " . time() . " - LEVEL: " . $level;';
    }

    /**
     * setPrefix
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * setLevel
     * @param $level
     */
    public function setLevel($level)
    {
        $this->levelDebug = $level;
    }

    /**
     * getLevel
     * @return int|mixed|SplInt
     */
    public function getLevel()
    {
        return $this->levelDebug;
    }

    /**
     * getStream
     * @return bool|resource
     */
    public function getStream()
    {
        return $this->fileStream;
    }

    /**
     * write
     * @param mixed $toWrite
     * @param mixed | SplString $level
     */
    public function write($toWrite, $level = self::LEVEL_INFO)
    {
        //control for the level of the log
        if ($level > $this->levelDebug) {
            return;
        }
        //control if stream is open
        if (!$this->fileStream) {
            $this->fileStream = fopen($this->pathOutput, $this->modeOfWrite);
        }

        /** @var $logger */
        eval('$logger = ' . $this->prefix);
        if (is_string($toWrite)) {
            fwrite($this->fileStream, '[ ' . $logger . ' ]' . $toWrite . PHP_EOL);
        }
        else {
            fwrite($this->fileStream, '[ ' . $logger . ' ]' . json_encode($toWrite) . PHP_EOL);
        }
        if ($level == self::LEVEL_FATAL) {
            die;
        }
    }

}

