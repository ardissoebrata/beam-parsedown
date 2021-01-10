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
		if (version_compare(parent::version, '0.8.1') < 0)
		{
			throw new Exception('BeamParsedown requires a later version of ParsedownExtra');
		}

		$this->InlineTypes['i'][] = 'Icon';
		$this->inlineMarkerList .= 'i';
	}

	protected function InlineIcon($excerpt)
	{
		if (preg_match('/icon\[(.+?)\]/', $excerpt['text'], $matches)) {
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
}