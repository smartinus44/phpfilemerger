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
namespace PhpFileMerger\bin\Adapters;
/**
 * Adapter
 * 
 * @category PHP
 * @package  Phpfilemerger
 * @author   Sylvain MARTIN <sylvain.martin-44@laposte.net>
 * @license  https://opensource.org/licenses/mit-license.php MIT
 * @link     _
 */
class FileGetContentsAdapter
{
    /**
     * FileGetContents
     * 
     * @param string $filename Full path of a file.
     * 
     * @return string
     */
    public function fileGetContents(string $filename)
    {
        return file_get_contents($filename);
    }
}
