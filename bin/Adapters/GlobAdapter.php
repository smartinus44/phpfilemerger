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
 * GlobAdapter
 * 
 * @category PHP
 * @package  Phpfilemerger
 * @author   Sylvain MARTIN <sylvain.martin-44@laposte.net>
 * @license  https://opensource.org/licenses/mit-license.php MIT
 * @link     _
 */
class GlobAdapter
{
    
    /**
     * GlobDir
     * 
     * @param string $dir Directory full path.
     *
     * @return array
     */
    public function globDir(string $dir)
    {
        return glob($dir, GLOB_BRACE);
    }
}
