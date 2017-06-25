<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

 class phpbb_storage_adapter_local_test extends phpbb_test_case
 {
	 protected $adapter;

	public function setUp()
	{
		parent::setUp();

		$config = new \phpbb\config\config(array(
			'test_path'	=> '.',
		));
		$filesystem = new \phpbb\filesystem\filesystem();
		$phpbb_root_path = getcwd().DIRECTORY_SEPARATOR;
		$path_key = 'test_path';
		$this->adapter = new \phpbb\storage\adapter\local($config, $filesystem, $phpbb_root_path, $path_key);
	}

	public function tearDown()
	{
		$this->adapter = null;
	}

	public function test_put_contents()
	{
		$this->adapter->put_contents('file.txt', 'abc');
		$this->assertTrue(file_exists('file.txt'));
		$this->assertEquals(file_get_contents('file.txt'), 'abc');
		unlink('file.txt');
	}

	public function test_get_contents()
	{
		file_put_contents('file.txt', 'abc');
		$this->assertEquals($this->adapter->get_contents('file.txt'), 'abc');
		unlink('file.txt');
	}

	public function test_exists()
	{
		// Exists with files
		$this->assertTrue($this->adapter->exists('README.md'));
		$this->assertFalse($this->adapter->exists('nonexistent_file.php'));
		// exists with directory
		$this->assertTrue($this->adapter->exists('phpBB'));
		$this->assertFalse($this->adapter->exists('nonexistet_folder'));
	}

	public function test_delete()
	{
		// Delete with files
		file_put_contents('file.txt', '');
		$this->assertTrue(file_exists('file.txt'));
		$this->adapter->delete('file.txt');
		$this->assertFalse(file_exists('file.txt'));
		// Delete with directories
		mkdir('path/to/dir', 0777, true);
		$this->assertTrue(file_exists('path/to/dir'));
		$this->adapter->delete('path');
		$this->assertFalse(file_exists('path/to/dir'));
	}

	public function test_rename()
	{
		file_put_contents('file.txt', '');
		$this->adapter->rename('file.txt', 'file2.txt');
		$this->assertFalse(file_exists('file.txt'));
		$this->assertTrue(file_exists('file2.txt'));
		unlink('file2.txt');
	}

	public function test_copy()
	{
		file_put_contents('file.txt', 'abc');
		$this->adapter->copy('file.txt', 'file2.txt');
		$this->assertEquals(file_get_contents('file.txt'), 'abc');
		$this->assertEquals(file_get_contents('file.txt'), 'abc');
		unlink('file.txt');
		unlink('file2.txt');
	}

 }
