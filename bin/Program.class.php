<?php
namespace PhpFileMerger\bin;

use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Adapters\GlobAdapter;

class Program {
	
	const OPEN_TAG = ['<?php', '<?'];
	const DS = '/';
	
	/**
	 * @var FileGetContentsAdapter
	 */
	private $fileGetContentsAdapter;

	/**
	 * @var GlobAdapter
	 */
	private $globAdapter;

	public function __construct(FileGetContentsAdapter $fileGetContentsAdapter, GlobAdapter $globAdapter)
	{
		$this->fileGetContentsAdapter = $fileGetContentsAdapter;
		$this->globAdapter = $globAdapter;
	}

	/**
	 * @var string
	 */
	public static $dest;

	/**
	 * @var array
	 */
	public static $data;

	public function run() {

		self::$dest = dirname(__FILE__) . self::DS . '..'. self::DS . 'prod'. self::DS . 'index.php';
		echo "The destination is: " . self::$dest . ".\n";
	
		$data = $this->getData();

		// Remove comments
		if(isset($argv[1]) && $argv[1] !== 'with_comment') {
			$data = $this->removeComments($data);
		}

		file_put_contents(self::$dest, $data);

		self::$data = $data;
		echo "File merged!\n";
	}

	/**
	 * @var string $data
	 */
	public function removeComments($data) {
		$re = '/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*$/m';
		$subst = '';
		return preg_replace($re, $subst, $data);
	}

	/**
	 * Get files
	 * @param string $dir
	 * @return array
	 */
	public function getFiles($dir) {

		$files = $this->globAdapter->globDir($dir);

		if(count($files) === 0) {
			echo "There is no file to merge.\n";
		}
		return $files;
	}

	/**
	 * Remove PHP open Tag
	 *
	 * @param string $file
	 * @return void
	 */
	public function replaceOpenTag($file) {
		return str_replace(self::OPEN_TAG, '', $this->fileGetContentsAdapter->fileGetContents($file)) . PHP_EOL;
	}

	/**
	 * @param string $data
	 */
	public function replaceTabs($data) {
		return str_replace("\t", '    ', $data);
	}

	/**
	 * Get formated data from files
	 * 
	 * @return string $data
	 */
	public function getData() {
		$data = "";
		$dir = dirname(__FILE__) . self::DS . '..' . self::DS . 'src' . self::DS . '*.{php}';		
		$files = $this->getFiles($dir);

		if(empty($files)) {
			exit;
		}

		foreach ($files as $file) {
			echo $file . "\n";
			if (file_exists($file)) {
				$data .= $this->replaceOpenTag($file);
			}
		}

		$data = self::OPEN_TAG . PHP_EOL . $data;
		$data .= $this->replaceOpenTag(dirname(__FILE__) . self::DS . '..' . self::DS . 'app.php');

		$data = $this->replaceTabs($data);
		
		return $data;
	}
}
