<?php

declare(strict_types=1);

namespace PhpFileMerger\bin\Adapters;

class FileGetContentsAdapter {
    public function fileGetContents(string $filename) {
        return file_get_contents( $filename );
    }
}