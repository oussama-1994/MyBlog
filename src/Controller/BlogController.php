<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormView;

use App\Entity\Article;
use App\Entity\Comment;

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
     * @Route("/blog/new",name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null,Request $request,ObjectManager $manager){
        if(!$article){
            $article=new Article(); //creation d'un article vide
        }

        /**
$form=$this->createFormBuilder($article)
    ->add('title')
    ->add('content')
    ->add('image')
    ->getForm();
         **/

        $form =$this->createForm(ArticleType::class , $article);


$form->handleRequest($request);
if($form->isSubmitted()  && $form->isValid()){
    if (!$article->getId()){

        $article->setCreatedAt(new \DateTime());

    }

    $manager->persist($article);
    $manager->flush();
    return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);

}



        return $this->render('blog/create.html.twig',[
            'form'=>$form->createView(),
            'edit'=>$article->getId() !==null
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog/{id}",name="blog_show")
     */
    public function show($id , Request $request, ObjectManager $manager){
        $repo=$this->getDoctrine()->getRepository(Article::class);
        $article=$repo->find($id);
        $comment=new Comment();
        $form=$this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);
$manager->persist($comment);
$manager->flush();
return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }
        return $this->render('blog/show.html.twig',[
            'article'=>$article,
            'commentForm'=>$form->createView()
        ]);



    }

    /**
     * @Route("/delete/{id}", name="blog_delete")
     */
    public function delete($id)
    {
        $em= $this->getDoctrine()->getManager();
        $article =$em->getRepository('App:Article')->find($id);
        $em->remove($article);
        $em->flush();


        $repo= $this->getDoctrine()->getRepository(Article::class);
        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController','articles'=>$articles
        ]);
    }


}
