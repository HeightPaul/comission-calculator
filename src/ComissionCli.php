<?php

namespace ComissionCli;

use ComissionCli\Lib\ComissionHelper;

/**
 * @brief Commission class, which execution is through CLI
 */
class ComissionCli
{
    public const FILE_ARG_POSITION = 1;
    private const MISSING_COMISSION_RESULT = 'missing';

    private ComissionHelper $comissionHelper;
    private string $inputFile;

    public function __construct(string $inputFile)
    {
        $this->comissionHelper = new ComissionHelper();
        $this->inputFile = $inputFile;
    }

    public function getResult(): string
    {
        $result = '';
        foreach (explode("\n", file_get_contents($this->inputFile)) as $row) {
            if (empty($row)) continue;
            $result .= $this->comissionHelper->getCalculatedComission(json_decode($row, true)) ?: self::MISSING_COMISSION_RESULT;
            $result .= "\n";
        }
        return $result;
    }
}
