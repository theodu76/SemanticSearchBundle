<?php

namespace iDCity\SemanticSearchBundle\ReshapeResponse;

class ReshapeResponse {

    public function flatten($words){
        $flatWords = array();
        foreach ($words as $word) {
            array_push($flatWords, $word['word']);
            if (array_key_exists('categories', $word)) {
                foreach ($word['categories'] as $category) {
                    array_push($flatWords, implode('|', $category));
                }
            }
        }
        return implode('|', $flatWords);
    }

}