<?php
/**
 * Program
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
namespace PhpFileMerger\bin;

use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Adapters\GlobAdapter;

/**
 * Program
 * 
 * @category PHP
 * @package  Phpfilemerger
 * @author   Sylvain MARTIN <sylvain.martin-44@laposte.net>
 * @license  https://opensource.org/licenses/mit-license.php MIT
 * @link     _
 */
class Program
{

    const OPEN_TAG = ['<?php', '<?'];
    const DS = '/';
    const PUBLIC_FOLDER = 'public';
    const SRC_FOLDER = 'src';
    const PROJECT_ROOT = __DIR__ . self::DS . '..' .  self::DS . '..' . self::DS . '..' . self::DS . '..' .  self::DS;

    /**
     * An adapter
     * 
     * @var FileGetContentsAdapter
     */
    private $_fileGetContentsAdapter;

    /**
     * An adapter
     * 
     * @var GlobAdapter
     */
    private $_globAdapter;

    /**
     * Constructor
     *
     * @param FileGetContentsAdapter $content An adapter
     * @param GlobAdapter            $glob    An adapter
     */
    public function __construct(FileGetContentsAdapter $content, GlobAdapter $glob)
    {
        $this->_fileGetContentsAdapter = $content;
        $this->_globAdapter = $glob;

        if (!file_exists(self::PROJECT_ROOT . self::PUBLIC_FOLDER)) {
            mkdir(self::PROJECT_ROOT . self::PUBLIC_FOLDER, '0755');
        }

        if (!file_exists(self::PROJECT_ROOT . self::SRC_FOLDER)) {
            mkdir(self::PROJECT_ROOT . self::SRC_FOLDER, '0755');
        }
    }

    /**
     * The dest file.
     * 
     * @var string
     */
    public static $dest;

    /**
     * The data inside files.
     * 
     * @var array
     */
    public static $data;

    /**
     * Run the script.
     * 
     * @return void
     */
    public function run()
    {

        self::$dest =  self::PROJECT_ROOT . self::PUBLIC_FOLDER . self::DS . 'index.php';
        echo "The destination is: " . self::$dest . ".\n";

        $data = $this->getData();

        // Remove comments
        if (isset($argv[1]) && $argv[1] !== 'with_comment') {
            $data = $this->removeComments($data);
        }

        file_put_contents(self::$dest, $data);

        self::$data = $data;
        echo "File merged!\n";
    }

    /**
     * Remove comments.
     * 
     * @param string $data Data to process.
     * 
     * @return string
     */
    public function removeComments($data)
    {
        $re = '/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$/m';
        $subst = '';
        return preg_replace($re, $subst, $data);
    }

    /**
     * Get files
     *
     * @param string $dir Dir to merge.
     * 
     * @return array
     */
    public function getFiles($dir)
    {

        $files = $this->_globAdapter->globDir($dir);

        if (count($files) === 0) {
            echo "There is no file to merge.\n";
        }
        return $files;
    }

    /**
     * Remove PHP open Tag
     *
     * @param string $file File to process.
     * 
     * @return void
     */
    public function replaceOpenTag($file)
    {
        $content = $this->_fileGetContentsAdapter->fileGetContents($file);
        return str_replace(self::OPEN_TAG, '', $content) . PHP_EOL;
    }

    /**
     * Replace tabs by space.
     * 
     * @param string $data Data to replace.
     * 
     * @return string
     */
    public function replaceTabs($data)
    {
        return str_replace("\t", '    ', $data);
    }

    /**
     * Get formated data from files
     * 
     * @return string $data
     */
    public function getData()
    {
        $data = "";
        $dir = self::PROJECT_ROOT . self::SRC_FOLDER . self::DS . '*.{php}';
        $files = $this->getFiles($dir);

        if (empty($files)) {
            exit;
        }

        foreach ($files as $file) {
            echo $file . "\n";
            if (file_exists($file)) {
                $data .= $this->replaceOpenTag($file);
            }
        }

        $data = current(self::OPEN_TAG) . PHP_EOL . $data;
        $path = dirname(__FILE__) . self::DS . '..' . self::DS . 'app.php';
        $data .= $this->replaceOpenTag($path);

        $data = $this->replaceTabs($data);

        return $data;
    }
}
