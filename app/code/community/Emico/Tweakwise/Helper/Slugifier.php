<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */
class Emico_Tweakwise_Helper_Slugifier
{
    /**
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        $replaces = [
            '-' => '--to-',
			'/' => '--per-',
			'+' => '--plus-',
			"'" => '--qt-',
			'"' => '--dqt-',
			'%' => '--per-',
            '#' => '--no-',
            '&' => '--and-',
            ' ' => '-',
        ];

        return strtolower(str_replace(array_keys($replaces), array_values($replaces), $string));
    }
}