<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;
use Symfony\Component\HttpFoundation\File\File;

class Document extends Data
{
    public string $type;
    public File $file;

    public function __construct(array $data) {
        parent::__construct($data);
    }
}