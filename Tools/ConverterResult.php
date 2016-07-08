<?php

namespace Tactics\InvoiceBundle\Tools;

class ConverterResult
{
    private $files = [];

    /**
     * @param string $filename
     * @param string $mimeType
     * @param string $content
     */
    public function add($filename, $mimeType, $content)
    {
        $this->files[] = [
            'filename' => $filename,
            'mimeType' => $mimeType,
            'content' => $content
        ];

        return $this;
    }
    
    public function output() {
        switch (count($this->files)){
            case 0:
                // do nothing?
                break;
            case 1:
                $this->outputSingleFile();
                break;
            default:
                $this->outputAsZipArchive();
        }
    }

    private function outputSingleFile()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->files[0]['mimeType'] . '; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->files[0]['filename']);
        header('Content-Length: ' . strlen($this->files[0]['content']));
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        echo $this->files[0]['content'];
        exit;
    }

    private function outputAsZipArchive()
    {
        $zipname = tempnam(sys_get_temp_dir(), 'FAC');
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($this->files as $fileInfo)
        {
          $zip->addFromString($fileInfo['filename'], $fileInfo['content']);
        }
        $zip->close();

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip; charset=utf-8');
        header('Content-Disposition: attachment; filename=verkopen.zip');
        header('Content-Length: ' . filesize($zipname));
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        readfile($zipname);
        unlink($zipname);
        exit;
    }
}

