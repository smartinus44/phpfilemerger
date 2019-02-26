<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Program;
use PhpFileMerger\bin\Adapters\GlobAdapter;

(new Program(new FileGetContentsAdapter(), new GlobAdapter))->run();