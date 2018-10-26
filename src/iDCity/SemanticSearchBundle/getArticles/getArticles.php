<?php 

namespace iDCity\SemanticSearchBundle\GetArticles;

/**
 * Service ayant pour objectif de retourner la liste triée des articles à afficher. 
 * 
 * - Il récupère une liste de mots contenant les mots originels, et les mots en lien avec ces derniers.
 * 
 * - Il récupère dans la base de données la liste d'articles contenant au moins un des mots de la liste.
 * 
 * - Il calcule le poids de chaque article en fonction de la détection des mots, de leur section dans l'article
 * et de leur lien avec les mots originels.
 * 
 * - Il trie la liste d'article selon leur poids total, par ordre décroissant
 * 
 * On définit 2 attributs pour permettre de réaliser ces tâches :
 * 
 *  - $aKeys : tableau associatif définissant l'importance (poids) des mots selon l'endroit où
 * ils se trouvent dans l'article (titre, objectif, corps)
 * 
 *  - $aCategories : tableau associatif définissant l'importance (poids) des mots selon leur lien
 * avec les mots rentrés à l'origine par l'utilisateur
 */
class GetArticles{
 
    //Importance des mots selon l'endroit d'où ils proviennent
    //A définir dans le fichier de configuration plus tard
    private $aKeys = array{
        "name" => 5,
        "topic" => 10,
        "description" => 1,
        "objective" => 3
    }

    //Importance des mots selon leur lien avec le mot originel
    //A définir dans le fichier de configuration plus tard
    private $aCategories = array{
        "origin" => 1,
        "hyponymes" => 1,
        "troponymes" => 1,
        "antonymes" => 1,
        "synonymes" => 1,
        "quasi-synonymes" => 1

    }

    /**
     * Fonction publique permettant avec une liste de mots donnée, de renvoyer la liste des articles en lien
     * triés par proximité décroissante par rapport à la liste de mots
     * @param $listSynonyms tableau associatif contenant les mots d'origine rentrés par l'utilisateur, 
     * leurs synonymes, antonymes,  troponymes, hyponymes et quasi-synonymes.
     * @return $listArticlesSorted tableau contenant la liste d'articles à afficher par la vue html, 
     * triés par ordre décroissant de proximité sémantique avec la liste de mots en entrée
     */
    public function get($listSynonyms){

        $listArticles=array();

        //Requête pour récupérer la liste d'articles, sous la forme d'un tableau 
        


        //appel de la fct classify pour classer les articles par score décroissant
        $listArticlesSorted =  sortArticles($listArticles, $listSynonyms);



        return $listArticlesSorted;
    }

    

    /**
     * Fonction privée qui retourne le poids d'un article étant donné un tableau de mots
     * à trouver dans cet article
     * @param $article tableau contenant l'article dont on doit calculer le poids total
     * @param $listSynonyms liste de mots à détecter dans l'article pour pouvoir calculer le poids
     * @return poids total de l'article
     */
    private function totalWeight($article, $listSynonyms) {
        return array_reduce($aCategories, function($accumulator, $item){
            return $accumulator + totalWeightByCategory($article, $listSynonyms, $category);
        }, 0);
        
    }

    /**
     * Fonction privée qui retourne le poids d'un article étant donné un tableau de mots
     * à trouver dans cet article, pour une catégorie de mots donnée de $listSynonyms
     * @param $article tableau contenant l'article dont on doit calculer le poids total
     * @param $listSynonyms liste de mots à détecter dans l'article pour pouvoir calculer le poids
     * @param $category catégorie de mots de $listSynonyms (mot d'origine, synonyme, antonyme, etc)
     * par rapport à laquelle on va calculer le poids de l'article
     * @return poids total de l'article pour la catégorie donnée en entrée
     */
     
    private function totalWeightByCategory($article, $listSynonyms, $category) {
        return array_reduce($aKeys, function($accumulator, $item){
            return $accumulator + weightByKey($article, $listSynonyms, $category, $key);
        }, 0);
        
    }

    /**
     * Fonction privée qui retourne le poids d'un article pour une section donnée de l'article, 
     * une liste de mots à trouver dans cette section, et la catégorie de cette liste de mots
     * @param $article tableau contenant l'article dont on doit calculer le poids total
     * @param $listSynonyms liste de mots à détecter dans la section de l'article pour pouvoir calculer le poids
     * @param $category catégorie de mots de $listSynonyms (mot d'origine, synonyme, antonyme, etc)
     * par rapport à laquelle on va calculer le poids de l'article
     * @param $key section de l'article dont on va calculer le poids
     * @return poids total de la section de l'article pour la catégorie donnée en entrée 
     */
    private function weightByKey($article, $listSynonyms, $category, $key) {
        return array_reduce($listSynonyms,function($accumulator, $word){
            return $accumulator + wordImportance($article, $word, $category, $key);
        }, 0);
    }

    /**
     * Fonction privée qui retourne le poids d'un article pour une section donnée, 
     * un mot donné à trouver dans cet article et la catégorie de ce mot
     * @param $article tableau contenant l'article dont on doit calculer le poids total
     * @param $word mot à détecter dans la section de l'article pour pouvoir calculer le poids
     * @param $category catégorie de mots de $listSynonyms (mot d'origine, synonyme, antonyme, etc)
     * par rapport à laquelle on va calculer le poids de l'article
     * @param $key section de l'article dont on va calculer le poids
     * @return poids de la section de l'article pour la catégorie donnée en entrée, et le mot donné
     * à détecter
     */
    private function wordImportance($article, $word, $category, $key) {
        $content = $article[$key];
        $nbWords = array_reduce($article, str_word_count($item), 0);
        if (isset($content)) {
            $reg = preg_match_all("i",$word);  //i =ne pas prendre en compte la casse
            if (preg_match_all('/'+$reg+'/', $content, $matches)) {
                return count($matches[0])*$aKeys[$key]*$aCategories[$category]/$nbWords;  //ou *(-log(nbWords)+3)
            } 
        }
        return 0;
    }

    /**
     * Fonction privée qui, pour une liste d'articles et une liste de mots données, trie les articles
     * par proximité sémantique avec la liste de mots
     * @param $listArticles liste d'articles à trier
     * @param $listSynonyms liste de mots à détecter dans les articles pour pouvoir les trier
     * @return liste d'articles triée par ordre de proximité sémantique avec la liste de mots donnée
     * en entrée
     */
    private function sortArticles($listArticles, $listSynonyms) {
        return $listArticles.sort(function($article1, $article2){
            return totalWeight($article2, $listSynonyms) - totalWeight($article1, $listSynonyms);
        });
    }

    


    

}

