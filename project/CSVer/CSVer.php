<?php

class CSVer
{
    /** @var string $pathOutput */
    private $pathOutput;
    /** @var bool | mixed $fileStream */
    private $fileStream;

    /**
     * CSVer constructor.
     * @param string $filename
     * @param string $path
     */
    public function __construct($filename, $path = './')
    {
        if (is_file($path)) {
            $this->pathOutput = $path;
        }
        else {
            $this->pathOutput = './' . $path . $filename;
        }
        $this->fileStream = false;
    }

    /**
     * createCSV
     * @param array $array
     * @param bool $isRecorsive
     */
    public function createCSV($array, $isRecorsive = false)
    {
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $this->createCSV($item, true);
                if (!$isRecorsive) {
                    ftruncate($this->fileStream, fstat($this->fileStream)['size'] - 2);
                    fwrite($this->fileStream, PHP_EOL);
                }
                continue;
            }
            if (!$this->fileStream) {
                $this->fileStream = fopen($this->pathOutput, 'a+');
                fwrite($this->fileStream, '"' . $item . '", ');
            }
            else {
                fwrite($this->fileStream, '"' . $item . '", ');
            }
        }
    }
}

