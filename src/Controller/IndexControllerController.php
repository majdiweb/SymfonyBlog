<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Bulletin;
use App\Form\BulletinType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexControllerController extends AbstractController
{
    #[Route('/', name: 'app_index_controller')]
    public function index(ManagerRegistry $doctrine): Response
    {
       $entityManager = $doctrine->getManager();
       $bulletinRepository = $entityManager->getRepository(Bulletin::class);
       $categories= $bulletinRepository->findEachCategory();
       //dd($categories); dd() (dump) est une fonction symfony twig permettant une analyse approfondie des variables palcées en parametre 
       $bulletins = $bulletinRepository->findAll();
       $bulletins = array_reverse($bulletins);

        return $this->render('index_controller/index.html.twig', [
           'categories'=>$categories,
           'bulletins'=>$bulletins, 
        ]);
    }
    #[Route('/category/{categoryName}', name: 'index_category')]
    public function indexCategory(ManagerRegistry $doctrine, string $categoryName): Response
    {
        $entityManager= $doctrine->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        $categories= $bulletinRepository->findEachCategory();
        $bulletins = $bulletinRepository->findBy([
            'category'=>$categoryName,
        ],[
            'id'=> 'DESC',
        ]);
        return $this->render('index_controller/index.html.twig', [
            'categories'=>$categories,
            'bulletins'=>$bulletins, 
         ]);

    }
    #[Route('/tag/{tagId}', name: 'index_tag')]
    public function indexTag(ManagerRegistry $doctrine, int $tagId): Response
    {
        $entityManager= $doctrine->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        
        $tag= $tagRepository->find($tagId);
        if(!$tag)
        {
            return $this->redirectToRoute('app_index_controller');
        }
        $bulletins=$tag->getBulletins();
        return $this->render('index_controller/index.html.twig', [
            
            'bulletins'=>$bulletins, 
         ]);

    }
    #[Route('/bulletin/newForm', name: 'bulletin_create')]
    public function createBulletin(Request $request,ManagerRegistry $doctrine): Response
    {  
        $entityManager= $doctrine->getManager();
        $bulletin = new Bulletin();
        $bulletin->clearFields();
        $bulletinForm =$this->createForm(BulletinType::class, $bulletin);
        $bulletinForm->handleRequest($request);
        if($bulletinForm->isSubmitted() && $bulletinForm->isValid())
        {
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirectToRoute("app_index_controller");
            
        }
        return $this->render('index_controller/dataForm.html.twig', [
            'formName'=>'Formulaire de Création de Bulletin',
            'dataForm' => $bulletinForm->createView(),

        ]);
    }
    #[Route('/bulletin/update/{bulletinId}', name: 'bulletin_update')]
    public function updateBulletin(ManagerRegistry $doctrine, int $bulletinId, Request $request): Response
    {  
        $entityManager = $doctrine->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        $bulletin=$bulletinRepository->find($bulletinId);
        if(!$bulletin)
        {
            return $this->redirectToRoute('app_index_controller');
        }
        
        $bulletinForm =$this->createForm(BulletinType::class, $bulletin);
        $bulletinForm->handleRequest($request);
        if($bulletinForm->isSubmitted() && $bulletinForm->isValid())
        {
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirectToRoute("app_index_controller");
            
        }
        return $this->render('index_controller/dataForm.html.twig', [
            'formName'=>'Formulaire de Modification de Bulletin',
            'dataForm' => $bulletinForm->createView(),

        ]);
    }
    #[Route('/bulletin/delete/{bulletinId}', name: 'bulletin_delete')]
    public function deleteBulletin(ManagerRegistry $doctrine, int $bulletinId): Response
    {  
        $entityManager = $doctrine->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        $bulletin=$bulletinRepository->find($bulletinId);
        if(!$bulletin)
        {
            return $this->redirectToRoute('app_index_controller');
        }
        $entityManager->remove($bulletin);
        $entityManager->flush();
        return $this->redirectToRoute('app_index_controller');
    }
    #[Route('/bulletin/desplay/{bulletinId}', name: 'bulletin_consult')]
    public function desplayBulletin(ManagerRegistry $doctrine, int $bulletinId): Response
    {  
        $entityManager = $doctrine->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        $bulletin=$bulletinRepository->find($bulletinId);
        if(!$bulletin)
        {
            return $this->redirectToRoute('app_index_controller');
        }
        
        return $this->render('index_controller/index.html.twig',[
            'bulletins'=>[$bulletin],
        ]);
    }
    #[Route('/generate', name: 'bulletin_generate')]
    public function generateBulletin(ManagerRegistry $doctrine): Response
    {  
        $entityManager = $doctrine->getManager();
        $bulletin = new Bulletin();
        $entityManager->persist($bulletin);
        $entityManager->flush();

      return $this->redirectToRoute('app_index_controller');
    }
    #[Route('/cheatsheet', name: 'index_cheatsheet')]
    public function cheatsheet(): Response
    {    
        return $this->render('index_controller/cheatsheet.html.twig');
    }
    #[Route('/fake', name: 'bulletin_fake')]
    public function fakeBulletin(): Response
    {
        $bulletins = [];
        for($i=0; $i<rand(5,15); $i++)
        {
            $bulletin = [
            'title' => 'Bulletin #'. rand (1000,9999),
            "category" => 'Général',
            "content" => 'lorem ipsum etc',
            "date" => (new \DateTime("now")),
            ];

            array_push($bulletins, $bulletin);
        }
        return $this->render('index_controller/index.html.twig', [
           'bulletins'=>$bulletins, 
        ]);
    }
    // #[Route('/square/{text}', name: 'index_square')]
    // public function displaysquare(string $text): Response
    // {
        // $var = rand(1,5);
        // if ($var == 1)
        // $color = 'red';
        // elseif ($var == 2)
        // $color = 'green';
        // elseif($var == 3)
        // $color = 'black';
        // elseif ($var == 4)
        // $color = 'yellow';
        // else
        // $color ='blue';
    //     $colors=['red', 'green', 'yellow', 'blue','black', 'orange', 'pink', 'grey'];
    //     $selectColors=$colors[rand(0, (count($colors)-1))];


    //     return new Response("<div style='width: 500px; height: 500px; text-align:center; background-color:$selectColors;color:white;'><h1>$text</h1></div>");    
    // }
    #[Route('/square/{SquareValue}', name: 'index_square')]
   
    public function displaySquare(string $SquareValue = ''): Response
    {
       switch (strtolower($SquareValue))
       {
         case 'rouge':
            $color = 'red';
            break;
        case 'vert':
            $color = 'green';
            break;
        case 'bleu':
            $color = 'blue';
            break;
        case 'jaune':
            $color = 'yellow';
            break;
        case '':
            $SquareValue = 'couleur non définie, veuillez donner un couleur';   
            $color ='gray';
            break;
        default:
            $color = 'black';
       }
        
        // $colors=['red', 'green', 'yellow', 'blue','black', 'orange', 'pink', 'grey'];
        // $selectColors=$colors[rand(0, (count($colors)-1))];
        return new Response("<div style='width: 500px; height: 500px; text-align:center; background-color:$color;color:white;'><h1>$SquareValue</h1></div>");    
    }
    
    
}
