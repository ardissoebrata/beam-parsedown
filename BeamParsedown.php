<?php

#
#
# Beam Parsedown
# https://github.com/ardissoebrata/beam-parsedown
#
# (c) Emanuil Rusev
# http://erusev.com
#
# (c) Ardi Soebrata
# https://mybeam.me
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

class BeamParsedown extends ParsedownExtra
{
	const version = '0.0.1';
	
    function __construct()
    {
        parent::__construct();

		if (version_compare(parent::version, '0.8.1') < 0)
		{
			throw new Exception('BeamParsedown requires a later version of ParsedownExtra');
		}

		$this->InlineTypes['i'][] = 'Icon';
        $this->inlineMarkerList .= 'i';

        $this->BlockTypes['i'][] = 'AlertInfo';
        $this->BlockTypes['w'][] = 'AlertWarning';
	}

	protected function InlineIcon($excerpt)
	{
        if (preg_match('/icon\[(.+?)\]/', $excerpt['text'], $matches)) 
        {
            return array(
                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'i',
                    'attributes' => array(
                        'class' => $matches[1],
                    ),
                    'rawHtml' => '',
                ),
            );
        }
    }
    
    protected function BlockAlertInfo($line, $block)
    {
        if (preg_match('/^info```/', $line['text'], $matches))
        {
            return array(
                'char' => $line['text'][0],
                'element' => array(
                    'name' => 'div',
                    'attributes' => array(
                        'class' => 'bg-indigo-100 rounded shadow-sm flex overflow-hidden',
                        'role' => 'alert',
                    ),
                    'handler' => 'elements',
                    'text' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'bg-indigo-500 w-20 flex justify-center items-center'
                            ),
                            'handler' => 'element',
                            'text' => array(
                                'name' => 'i',
                                'attributes' => array(
                                    'class' => 'fa fa-info-circle fa-2x text-white',
                                ),
                                'rawHtml' => ''
                            ),
                        ),
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'flex-1 px-4',
                            ),
                            'handler' => 'lines',
                            'text' => array(),
                        )
                    ),
                ),
            );
        }
    }

    protected function BlockAlertInfoContinue($line, $block)
    {
        if (isset($block['complete']))
        {
            return;
        }

        // A blank newline has occurred.
        if (isset($block['interrupted']))
        {
            unset($block['interrupted']);
        }

        // Check for end of the block. 
        if (preg_match('/^info```/', $line['text']))
        {
            $block['complete'] = true;
            return $block;
        }
        
        $block['element']['text'][1]['text'][] = $line['body'];
        
        return $block;
    }

    protected function BlockAlertInfoComplete($block)
    {
        return $block;
    }
    
    protected function BlockAlertWarning($line, $block)
    {
        if (preg_match('/^warning```/', $line['text'], $matches))
        {
            return array(
                'char' => $line['text'][0],
                'element' => array(
                    'name' => 'div',
                    'attributes' => array(
                        'class' => 'bg-yellow-50 rounded shadow-sm flex overflow-hidden',
                        'role' => 'alert',
                    ),
                    'handler' => 'elements',
                    'text' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'bg-yellow-300 w-20 flex justify-center items-center'
                            ),
                            'handler' => 'element',
                            'text' => array(
                                'name' => 'i',
                                'attributes' => array(
                                    'class' => 'fa fa-exclamation-triangle fa-2x',
                                ),
                                'rawHtml' => '',
                            ),
                        ),
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'flex-1 px-4',
                            ),
                            'handler' => 'lines',
                            'text' => array(),
                        )
                    ),
                ),
            );
        }
    }

    protected function BlockAlertWarningContinue($line, $block)
    {
        if (isset($block['complete']))
        {
            return;
        }

        // A blank newline has occurred.
        if (isset($block['interrupted']))
        {
            unset($block['interrupted']);
        }

        // Check for end of the block. 
        if (preg_match('/^warning```/', $line['text']))
        {
            $block['complete'] = true;
            return $block;
        }
        
        $block['element']['text'][1]['text'][] = $line['body'];
        
        return $block;
    }

    protected function BlockAlertWarningComplete($block)
    {
        return $block;
    }

    protected $baseImagePath = '';

    public function setBaseImagePath($url)
    {
        $this->baseImagePath = $url;
    }

    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if ( ! isset($image))
        {
            return null;
        }

        $image['element']['attributes']['src'] = $this->baseImagePath . '/' . $image['element']['attributes']['src'];

        return $image;
    }
}