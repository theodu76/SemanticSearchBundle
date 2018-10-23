<?php 

namespace iDCity\SemanticSearchBundle\GetArticles;

class GetArticles{

    public function get($listSynonyms){

        foreach ($listSynonyms as $synonym){

            //Requête permettant de récupérer la liste d'articles contenant synonym
            //(on prend les articles en entier)


            //ajout de la liste d'articles concernés dans le tableau liste article 

        }
        //appel de la fct classify pour classer les articles par score décroissant
            //on stocke le résultat dans le tableau $arrayArticles = [for i [ article[i], score[article[i]]]
            $arrayArticles =  classify($listArticles);


            //fonction de tri des articles pour renvoyer le mm tableau mais trié
            $arrayArticlesSorted = sort($arrayArticles);


        return $arrayArticlesSorted;
    }


    public function classify($listArticles){


        return $arrayArticles;
    }

    public function sort($arrayArticles){


        return $arrayArticlesSorted;
    }

}

