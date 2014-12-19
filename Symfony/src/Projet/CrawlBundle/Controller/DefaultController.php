<?php

namespace Projet\CrawlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DomCrawler\Crawler;

/*use Projet\CrawlBundle\Entity\Crawler;
use Projet\CrawlBundle\Entity\Categorie;
use Projet\CrawlBundle\Entity\User;*/

class DefaultController extends Controller
{
    function __construct() {
      
    }
    
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $cats = $this->getDoctrine()
            ->getRepository('ProjetCrawlBundle:Categorie')
            ->findAll();

        $crawlers = $this->getDoctrine()
            ->getRepository('ProjetCrawlBundle:Crawler'); 
        
        foreach($cats as $c){
        }
        
        return array('cats' => $cats, 'crawlers' => $crawlers);
    }
    
    
    /**
     * @Route("/crawl/{cat}", name="crawl")
     * @Template()
     */
    public function crawlAction($cat, Request $request)
    {       
        $site="";
        if($request->request->get('Link')){
            $link = $request->request->get('Link');
            $site = $this->file_get_contents_utf8($link);
        }

        if($request->request->get('stoo')){
            return $this->editAction($request->request->get('stoo'));
        }
        
        $repoCrawler = $this->getDoctrine()
            ->getRepository('ProjetCrawlBundle:Crawler');
        
        $repoCats = $this->getDoctrine()
            ->getRepository('ProjetCrawlBundle:Categorie');
            
                
        $list = $repoCats->createQueryBuilder('c')
            ->where('c.lib = :cat')
            ->setParameter('lib', $cat)
            ->getQuery();
        
        $list2 = $repoCrawler->createQueryBuilder('p')
            ->where('p.lib = :lib')
            ->orderBy('p.id', 'ASC')
            ->getQuery();      
           
        //var_dump($list);
        
        /*
        $test = new Crawl;
        $test->setCategorie("JOBS");
        $test->setNom("Codeur.com");
        $test->setSelector('.small-project');
        $test->setUrl("https://www.codeur.com/");
        
        $var = $this->file_get_contents_utf8($test->getUrl());        
        
        $crawler = new Crawler();
        $crawler->addContent($var);
        
        $noeuds = $crawler->filter($test->getSelector());
        
        $list = [];
        for ($i = 0; $i < sizeof($noeuds); $i++){
            array_push($list, $crawler->filter($test->getSelector())->eq($i)->html());
        }*/
        
        return array('crawl' => $list, 'site' => $site);
    }  
    
    protected function editAction($link) { 
        $elems = explode(" ",$link);
        return $this->render('ProjetCrawlBundle:Default:edit.html.twig', array('elems' => $elems));
    }   
    
    protected function file_get_contents_utf8($link) { 
     $content = file_get_contents($link); 
      return mb_convert_encoding($content, 'UTF-8', 
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
    }

}

class Crawl{
    private $categorie;
    private $url;
    private $selector;
    private $nom;
    
    public function getCategorie() {
        return $this->categorie;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getSelector() {
        return $this->selector;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setSelector($selector) {
        $this->selector = $selector;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }


    
}
