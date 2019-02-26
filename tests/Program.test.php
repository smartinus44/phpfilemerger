<?php
declare(strict_types=1);

namespace PhpFileMerger\tests;

use PHPUnit\Framework\TestCase;
use PhpFileMerger\bin\Program;
use PhpFileMerger\bin\Adapters\FileGetContentsAdapter;
use PhpFileMerger\bin\Adapters\GlobAdapter;

class ProgramTest extends TestCase
{
    /**
     * @var FileGetContentsAdapter
     */
    public $fileGetContentsWrapper;
    
    /**
    * @var GlobAdapter
    */
    public $globWrapper;

    /**
     * @var Program
     */
    public $program;

    protected function setUp()
    {
        $this->fileGetContentsWrapper = $this->createMock(FileGetContentsAdapter::class);
        $this->globWrapper = $this->createMock(GlobAdapter::class);
        $this->program = (new Program($this->fileGetContentsWrapper, $this->globWrapper));

        parent::setUp();
    }
 
    public function testReplaceOpenTag() {

        $someSimulatedJson = '<?php //toto';
        $this->fileGetContentsWrapper->method('fileGetContents')->willReturn($someSimulatedJson);
        $result = $this->program->replaceOpenTag('toto');

        $this->assertEquals(" //toto\n", $result);
    }

    public function testReplaceTabsWork() {

        $string = "\t\t\t\t";
        $result = $this->program->replaceTabs($string);

        $this->assertEquals("                ", $result);
    }

    public function testRemoveCommentWork() {

        $string = "/**
        *
        *
        *
        **/";
        $result = $this->program->removeComments($string);

        $this->assertEquals("", $result);
    }

    public function testRemoveCommentDontWork() {

        $string = "/*sss
        =>*/";
        $result = $this->program->removeComments($string);

        $this->assertNotEquals(" ", $result);
    }

    public function testReplaceTabsDontWork() {

        $string = "\t\t\t";
        $result = $this->program->replaceTabs($string);

        $this->assertNotEquals("                ", $result);
    }

    public function testGetFilesWork() {

        $someSimulatedJson = ['1.php','2.php'];
        $this->globWrapper->method('globDir')->willReturn($someSimulatedJson);
        $result = $this->program->getFiles('toto');

        $this->assertEquals($someSimulatedJson, $result);
    }

    public function testGetFilesDontWork() {

        $someSimulatedJson = [];
        $this->globWrapper->method('globDir')->willReturn($someSimulatedJson);
        $result = $this->program->getFiles('toto');
        $this->assertIsArray($result);
        $this->assertEmpty($result);

    }

}
