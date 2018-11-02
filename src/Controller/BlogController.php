<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo= $this->getDoctrine()->getRepository(Article::class);
       $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController','articles'=>$articles
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/",name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog/{id}",name="blog_show")
     */
    public function show($id){
        $repo=$this->getDoctrine()->getRepository(Article::class);
        $article=$repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article'=>$article
        ]);

    }
}
