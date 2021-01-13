<?php

namespace ArdiSSoebrata\BeamParsedown\Tests;

use ArdiSSoebrata\BeamParsedown\Facades\BeamParsedown;

class BeamParsedownTest extends TestbenchTestCase
{
    private $dirs;
	
    final function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->dirs = $this->initDirs();

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return array
     */
    protected function initDirs()
    {
        $dirs []= dirname(__FILE__).'/data/';

        return $dirs;
    }
	
    /**
     * @dataProvider data
     * @param $test
     * @param $dir
     */
    function test_($test, $dir)
    {
        $markdown = file_get_contents($dir . $test . '.md');

        $expectedMarkup = file_get_contents($dir . $test . '.html');

        $expectedMarkup = str_replace("\r\n", "\n", $expectedMarkup);
		$expectedMarkup = str_replace("\r", "\n", $expectedMarkup);

        $actualMarkup = BeamParsedown::text($markdown);

        $this->assertEquals($expectedMarkup, $actualMarkup);
	}
	
    function data()
    {
        $data = array();

        foreach ($this->dirs as $dir)
        {
            $Folder = new \DirectoryIterator($dir);

            foreach ($Folder as $File)
            {
                /** @var $File DirectoryIterator */

                if ( ! $File->isFile())
                {
                    continue;
                }

                $filename = $File->getFilename();

                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                if ($extension !== 'md')
                {
                    continue;
                }

                $basename = $File->getBasename('.md');

                if (file_exists($dir . $basename . '.html'))
                {
                    $data []= array($basename, $dir);
                }
            }
        }

        return $data;
    }
}
