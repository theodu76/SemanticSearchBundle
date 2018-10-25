<?php 

namespace iDCity\SemanticSearchBundle\GetArticles;

class GetArticles{

    //Importance des mots selon l'endroit d'où ils proviennent
    //A définir dans le fichier de configuration plus tard
    private const $aKeys = array{
        "name" => 5,
        "topic" => 10,
        "description" => 1,
        "objective" => 3
    }

    //Importance des mots selon leur lien avec le mot originel
    //A définir dans le fichier de configuration plus tard
    private const $aCategories = array{
        "origin" => 1,
        "hyponymes" => 1,
        "troponymes" => 1,
        "antonymes" => 1,
        "synonymes" => 1,
        "quasi-synonymes" => 1

    }


    public function get($listSynonyms){

        $listArticles=array();

        //Requête pour récupérer la liste d'articles, sous la forme d'un tableau 
        


        //appel de la fct classify pour classer les articles par score décroissant
        $listArticlesSorted =  sortArticles($listArticles, $listSynonyms);



        return $listArticlesSorted;
    }

    


    //Fonction privée qui retourne le poids d'un article étant donné un tableau de mots à trouver dans cet article
    private function totalWeight($article, $listSynonyms) {
        return array_reduce($aCategories, function($accumulator, $item){
            return $accumulator + totalWeightByCategory($article, $listSynonyms, $category);
        }, 0);
        
    }

    //Fonction privée qui retourne le poids d'un article étant donné un tableau de mots à trouver dans cet article, par catégorie 
    private function totalWeightByCategory($article, $listSynonyms, $category) {
        return array_reduce($aKeys, function($accumulator, $item){
            return $accumulator + weightByKey($article, $listSynonyms, $category, $key);
        }, 0);
        
    }

    //Fonction privée qui retourne le poids d'un article pour une section donnée, et une liste de mots à trouver dans cette section
    private function weightByKey($article, $listSynonyms, $category, $key) {
        return array_reduce($listSynonyms,function($accumulator, $word){
            return $accumulator + wordImportance($article, $word, $category, $key);
        }, 0);
    }


    //Fonction privée qui retourne le poids d'un article pour une section donnée, et un mot donné à trouver dans cet article
    private function wordImportance($article, $word, $category, $key) {
        const $content = $article[$key];
        if (isset($content)) {
            const $reg = preg_match_all("i",$word);  //i =ne pas prendre en compte la casse
            if (preg_match_all('/'+$reg+'/', $content, $matches)) {
                return count($matches[0])*$aKeys[$key]*$aCategories[$category];
            } 
        }
        return 0;
    }


    private function sortArticles($listArticles, $listSynonyms) {
        return $listArticles.sort(function($article1, $article2){
            return totalWeight($article2, $listSynonyms) - totalWeight($article1, $listSynonyms);
        });
    }


    

}

