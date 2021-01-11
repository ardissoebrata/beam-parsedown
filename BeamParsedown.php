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
                        'class' => 'm-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-info',
                        'role' => 'alert',
                    ),
                    'handler' => 'elements',
                    'text' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'm-alert__icon'
                            ),
                            'handler' => 'element',
                            'text' => array(
                                'name' => 'i',
                                'attributes' => array(
                                    'class' => 'fa fa-info-circle',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'm-alert__text',
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
                        'class' => 'm-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-warning',
                        'role' => 'alert',
                    ),
                    'handler' => 'elements',
                    'text' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'm-alert__icon'
                            ),
                            'handler' => 'element',
                            'text' => array(
                                'name' => 'i',
                                'attributes' => array(
                                    'class' => 'fa fa-exclamation-triangle',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => 'm-alert__text',
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
}