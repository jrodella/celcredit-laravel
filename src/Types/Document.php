<?php

namespace Celcredit\Types;

use Celcredit\Interfaces\Attachable;
use Celcredit\Types\Data;
use Illuminate\Http\File;

class Document extends Data implements Attachable
{
    public string $type;
    public ?File $file;

    public function __construct(?File $file, string $type)
    {
        $data = [];
        if (! empty($file)) {
            $data['contents'] = $file->getContent();
            $data['fileName'] = $file->getFilename();
        }
        $this->field = 'file';
        $this->file = $file;
        $this->type = $type;

        parent::__construct($data);
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getHeaders(): array
    {
        return ['Content-Type' => $this->file->getMimeType()];
    }
}
