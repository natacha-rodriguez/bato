<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SpanishInflector
 *
 * @author naty
 */
class SpanishInflector implements InflectorInterface {

    //TODO: manejar frases con mas de una palabra

    public static function singularize($phrase) {
        //TODO: mejorar las reglas para que sean independientes de la secuencia de evaluacion
        $singularizeEsRule = "/([^bcd][rlndszjxyíú]|ch)es\b/i";
        $singularizeSRule = "/([aeiougb])s\b/i";

        if (!is_array($phrase)) {
            $phrase = array($phrase);
        }
        $results = array();
        foreach ($phrase as $word) {
            if (preg_match($singularizeEsRule, $word)) {
                $results[] = preg_replace($singularizeEsRule, '$1', $word);
            } else if (preg_match($singularizeSRule, $word)) {
                $results[] = preg_replace($singularizeSRule, "$1", $word);
            } else {
                $results[] = $word;
            }
        }
        return $results;
    }

    public static function pluralize($phrase) {
        //$pluralizeSRule = "/([aeiou&&[^lndszjx]])$/i";
        $pluralizeSRule = "/([aeiougb])$/i";
        $pluralizeEsRule = "/([rlndszjxy]|ch)\b/i";
        if (!is_array($phrase)) {
            $phrase = array($phrase);
        }
        
        $restuls = array();
        foreach ($phrase as $word) {
            $word = strtolower($word);
            if (preg_match($pluralizeSRule, $word)) {
                $results[] = "{$word}s";
            } else if (preg_match($pluralizeEsRule, $word)) {
                $results[] = "{$word}es";
            } else {
                $results[] = $word;
            }
        }
        return $results;
    }

}

?>
