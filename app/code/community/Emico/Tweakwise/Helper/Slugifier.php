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
        $search = [
            ' '
        ];

        $replace = [
            '-'
        ];

        return strtolower(str_replace($search, $replace, $string));
    }
}