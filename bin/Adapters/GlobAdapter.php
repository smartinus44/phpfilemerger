<?php
namespace PhpFileMerger\bin\Adapters;

class GlobAdapter
{
    public function globDir(string $dir)
    {
        return glob($dir, GLOB_BRACE);
    }
}
