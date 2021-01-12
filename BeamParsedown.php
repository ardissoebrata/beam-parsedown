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

        $this->InlineTypes['['][] = 'Icon';
        $this->InlineTypes['['][] = 'Audio';

        // Identify alerts before definition list.
        array_unshift($this->BlockTypes[':'], 'Alert');
    }

    // Base path.

    protected $basePath = '';

    public function setBasePath($url)
    {
        $this->basePath = $url;
    }

    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if ( ! isset($image))
        {
            return null;
        }

        $image['element']['attributes']['src'] = $this->basePath . '/' . $image['element']['attributes']['src'];

        return $image;
    }
    
    // Icon

	protected function InlineIcon($excerpt)
	{
        if (preg_match('/\[icon:(.+?)\]/', $excerpt['text'], $matches)) 
        {
            return array(
                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'i',
                    'attributes' => array(
                        'class' => trim($matches[1]),
                    ),
                    'rawHtml' => '',
                ),
            );
        }
    }

    // Audio

    protected function InlineAudio($excerpt)
    {
        if (preg_match('/\[audio:(.+?)\]/', $excerpt['text'], $matches)) 
        {
            return array(
                // How many characters to advance the Parsedown's
                // cursor after being done processing this tag.
                'extent' => strlen($matches[0]), 
                'element' => array(
                    'name' => 'audio',
                    'attributes' => array(
                        'controls' => '',
                        'preload' => 'none',
                    ),
                    'handler' => 'element',
                    'text' => array(
                        'name' => 'source',
                        'attributes' => array(
                            'src' => $this->basePath . '/' . trim($matches[1]),
                        )
                    ),
                ),
            );
        }
    }

    // Alerts

    protected $alert_types = array(
        'info' => array(
            'container-class' => 'bg-indigo-100 rounded shadow-sm flex overflow-hidden',
            'icon-bg-class' => 'bg-indigo-500 w-20 flex justify-center items-center',
            'icon-class' => 'fa fa-info-circle fa-2x text-white',
        ),
        'warning' => array(
            'container-class' => 'bg-yellow-50 rounded shadow-sm flex overflow-hidden',
            'icon-bg-class' => 'bg-yellow-300 w-20 flex justify-center items-center',
            'icon-class' => 'fa fa-exclamation-triangle fa-2x',
        )
    );

    protected function BlockAlert($line, $block)
    {
        $types = implode('|', array_keys($this->alert_types));
        if (preg_match('/^:::(' . $types . ')/', $line['text'], $matches))
        {
            $type = trim($matches[1]);
            return array(
                'char' => $line['text'][0],
                'element' => array(
                    'name' => 'div',
                    'attributes' => array(
                        'class' => $this->alert_types[$type]['container-class'],
                        'role' => 'alert',
                    ),
                    'handler' => 'elements',
                    'text' => array(
                        array(
                            'name' => 'div',
                            'attributes' => array(
                                'class' => $this->alert_types[$type]['icon-bg-class']
                            ),
                            'handler' => 'element',
                            'text' => array(
                                'name' => 'i',
                                'attributes' => array(
                                    'class' => $this->alert_types[$type]['icon-class'],
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

    protected function BlockAlertContinue($line, $block)
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
        if (preg_match('/^:::/', $line['text']))
        {
            $block['complete'] = true;
            return $block;
        }
        
        $block['element']['text'][1]['text'][] = $line['body'];
        
        return $block;
    }

    protected function BlockAlertComplete($block)
    {
        return $block;
    }
}