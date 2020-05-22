<?php

namespace Foris\Easy\Support\Tests;

use Foris\Easy\Support\Exceptions\FileNotFountException;
use Foris\Easy\Support\Filesystem;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Class FileSystemTest
 */
class FileSystemTest extends TestCase
{
    /**
     * vfs instance
     *
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfs = null;

    /**
     * Get vfs instance
     *
     * @return \org\bovigo\vfs\vfsStreamDirectory
     */
    protected function vfs()
    {
        if (empty($this->vfs)) {
            $this->vfs = vfsStream::setup();
        }

        return $this->vfs;
    }

    /**
     * test check if file exist
     */
    public function testCheckIfFileExist()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/text.txt');
        $this->assertFalse(Filesystem::exists($file));

        file_put_contents($file, "The new contents of the file");
        $this->assertTrue(Filesystem::exists($file));
    }

    /**
     * test check if a given path is a file
     */
    public function testCheckIfIsFile()
    {
        $vfs = vfsStream::setup('home');

        $dir = $vfs->url();
        $file = vfsStream::url('home/test.txt');
        file_put_contents($file, "The new contents of the file");

        $this->assertFalse(Filesystem::isFile($dir));
        $this->assertTrue(Filesystem::isFile($file));
    }

    /**
     * test check if a given path is a directory
     */
    public function testCheckIfIsDirectory()
    {
        $vfs = vfsStream::setup('home');

        $dir = $vfs->url();
        $file = vfsStream::url('home/test.txt');
        file_put_contents($file, "The new contents of the file");

        $this->assertTrue(Filesystem::isDirectory($dir));
        $this->assertFalse(Filesystem::isDirectory($file));
    }

    /**
     * test check if a given file is readable
     */
    public function testCheckIfAGivenFileIsReadable()
    {
        $vfs = vfsStream::setup('home');
        vfsStream::newFile('test.txt', 0000)->at($vfs)->setContent("The new contents of the file");

        $file = vfsStream::url('home/test.txt');
        $this->assertTrue(Filesystem::exists($file));
        $this->assertFalse(Filesystem::isReadable($file));

        vfsStream::newFile('test-2.txt', 0777)->at($vfs)->setContent("The new contents of the file");
        $file = vfsStream::url('home/test-2.txt');
        $this->assertTrue(Filesystem::exists($file));
        $this->assertTrue(Filesystem::isReadable($file));
    }

    /**
     * test check if a given file is writable
     */
    public function testCheckIfAGivenFileIsWritable()
    {
        $vfs = vfsStream::setup('home');
        vfsStream::newFile('test.txt', 0000)->at($vfs)->setContent("The new contents of the file");

        $file = vfsStream::url('home/test.txt');
        $this->assertTrue(Filesystem::exists($file));
        $this->assertFalse(Filesystem::isWritable($file));

        vfsStream::newFile('test-2.txt', 0777)->at($vfs)->setContent("The new contents of the file");
        $file = vfsStream::url('home/test-2.txt');
        $this->assertTrue(Filesystem::exists($file));
        $this->assertTrue(Filesystem::isWritable($file));
    }

    /**
     * test get file content
     *
     * @throws \Foris\Easy\Support\Exceptions\FileNotFountException
     */
    public function testGetFileContent()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/text.txt');
        file_put_contents($file, "The new contents of the file");

        $this->assertEquals('The new contents of the file', Filesystem::get($file));

        $nonExistFile = vfsStream::url('home/non-exist-file.txt');
        $this->expectException(FileNotFountException::class);
        $this->expectExceptionMessage("File does not exist at path {$nonExistFile}");
        FileSystem::get($nonExistFile);
    }

    /**
     * test put file content
     */
    public function testPutFileContent()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/text.txt');
        Filesystem::put($file, 'test content');
        $this->assertEquals('test content', file_get_contents($file));
    }

    /**
     * test prepend file content
     *
     * @throws FileNotFountException
     */
    public function testPrependFileContent()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/text.txt');
        Filesystem::prepend($file, 'test content');
        $this->assertEquals('test content', file_get_contents($file));

        Filesystem::prepend($file, 'This is a ');
        $this->assertEquals('This is a test content', file_get_contents($file));
    }

    /**
     * test append file content
     */
    public function testAppendFileContent()
    {
        vfsStream::setup('home');
        $file = vfsStream::url('home/text.txt');
        Filesystem::append($file, 'Hello');
        $this->assertEquals('Hello', file_get_contents($file));

        Filesystem::append($file, ' world!');
        $this->assertEquals('Hello world!', file_get_contents($file));
    }

    /**
     * test Filesystem::makeDirectory
     */
    public function testMakeDirectory()
    {
        vfsStream::setup('home');
        $url = vfsStream::url('home/parent_folder/child_folder');
        $this->assertFalse(Filesystem::exists($url));

        Filesystem::makeDirectory($url, 0755, true);
        $this->assertTrue(Filesystem::exists($url));

        Filesystem::makeDirectory($url, 0755, true, true);
        $this->assertTrue(Filesystem::exists($url));
    }

    /**
     * test Filesystem::scanFiles
     */
    public function testScanFiles()
    {
        $vfs = vfsStream::setup('home');
        $expected = [];

        $expected[] = vfsStream::newFile('test-1.txt', 0000)->at($vfs)->url();
        $expected[] = vfsStream::newFile('test-2.txt', 0000)->at($vfs)->url();
        $expected[] = vfsStream::newFile('test-dir/test-3.txt', 0000)->at($vfs)->url();

        $this->assertEquals($expected, Filesystem::scanFiles($vfs->url()));
    }

    /**
     * test Filesystem::replace
     */
    public function testReplace()
    {
        $file = __DIR__ . '/stubs/test-file.txt';

        file_put_contents($file, 'test content');
        $this->assertEquals('test content', file_get_contents($file));

        Filesystem::replace($file, 'replace content');
        $this->assertEquals('replace content', file_get_contents($file));
    }
}
