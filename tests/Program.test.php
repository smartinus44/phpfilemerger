<?php
/**
 * ProgramTest
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
declare (strict_types = 1);

namespace PhpFileMerger\tests;

use PHPUnit\Framework\TestCase;
use PhpFileMerger\bin\Program;
use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Adapters\GlobAdapter;
/**
 * ProgramTest
 * 
 * @category PHP
 * @package  Phpfilemerger
 * @author   Sylvain MARTIN <sylvain.martin-44@laposte.net>
 * @license  https://opensource.org/licenses/mit-license.php MIT
 * @link     _
 */
class ProgramTest extends TestCase
{
    /**
     * An adapter.
     * 
     * @var FileGetContentsAdapter
     */
    public $contentWrapper;

    /**
     * An adapter.
     * 
     * @var GlobAdapter
     */
    public $globWrapper;

    /**
     * The program to test.
     * 
     * @var Program
     */
    public $program;

    /**
     * Setup
     * 
     * @return void
     */
    protected function setUp()
    {
        $this->contentWrapper = $this->createMock(FileGetContentsAdapter::class);
        $this->globWrapper = $this->createMock(GlobAdapter::class);
        $this->program = (new Program($this->contentWrapper, $this->globWrapper));

        parent::setUp();
    }

    /**
     * Test testReplaceOpenTag 
     * 
     * @return void
     */
    public function testReplaceOpenTag()
    {
        $someSimulatedJson = '<?php //toto';
        $this->contentWrapper
            ->method('fileGetContents')
            ->willReturn($someSimulatedJson);
        $result = $this->program->replaceOpenTag('toto');

        $this->assertEquals(" //toto\n", $result);
    }

    /**
     * Test testReplaceTabsWork
     * 
     * @return void
     */
    public function testReplaceTabsWork()
    {

        $string = "\t\t\t\t";
        $result = $this->program->replaceTabs($string);

        $this->assertEquals("                ", $result);
    }

    /**
     * Test testRemoveCommentWork
     * 
     * @return void
     */
    public function testRemoveCommentWork()
    {

        $string = "/**
        *
        *
        *
        **/";
        $result = $this->program->removeComments($string);

        $this->assertEquals("", $result);
    }

    /**
     * Test testRemoveCommentDontWork
     * 
     * @return void
     */
    public function testRemoveCommentDontWork()
    {

        $string = "/*sss
        =>*/";
        $result = $this->program->removeComments($string);

        $this->assertNotEquals(" ", $result);
    }

    /**
     * Test testReplaceTabsDontWork
     * 
     * @return void
     */
    public function testReplaceTabsDontWork()
    {

        $string = "\t\t\t";
        $result = $this->program->replaceTabs($string);

        $this->assertNotEquals("                ", $result);
    }

    /**
     * Test testGetFilesWork
     * 
     * @return void
     */
    public function testGetFilesWork()
    {

        $someSimulatedJson = ['1.php', '2.php'];
        $this->globWrapper->method('globDir')->willReturn($someSimulatedJson);
        $result = $this->program->getFiles('toto');

        $this->assertEquals($someSimulatedJson, $result);
    }

    /**
     * Test testGetFilesDontWork
     * 
     * @return void
     */
    public function testGetFilesDontWork()
    {

        $someSimulatedJson = [];
        $this->globWrapper->method('globDir')->willReturn($someSimulatedJson);
        $result = $this->program->getFiles('toto');
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
