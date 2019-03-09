<?php
/**
 * FileGetContents
 * PHP version 7.1
 * 
 * @category  PHP
 * @package   Phpfilemerger
 * @author    Sylvain MARTIN <sylvain.martin-44@laposte.net>
 * @copyright 2019 Sylvain MARTIN
 * @license   https://opensource.org/licenses/mit-license.php MIT
 * @version   GIT: <git_id>
 * @link      _
 */
require __DIR__ . '/../../../autoload.php';

use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Program;
use PhpFileMerger\bin\Adapters\GlobAdapter;

(new Program(new FileGetContentsAdapter(), new GlobAdapter))->run();