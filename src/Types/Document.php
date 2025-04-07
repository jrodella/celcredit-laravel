<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;
use Symfony\Component\HttpFoundation\File\File;

class Document extends Data
{
    public function __construct(
        public string $type,
        public File $file
    ) {}
}