<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Conference;
use App\Entity\Reservation;
use App\Form\ConferenceType;
use App\Form\ReservationType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    public $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/conference/add', name: 'app_conference.add')]
    public function add(Request $request, ConferenceRepository $repo): Response
    {
        $conference = new Conference();
        // 
        $form = $this->createForm(ConferenceType::class, $conference);

        // cette fonction permet d'hydrater l'objet conférence
        $form->handleRequest($request);

        // si le formulaire est soummis et que le formulaire est valide
        if($form->isSubmitted()){

            // le chemin où on doit stocker l'image (nom de l'image)
            //$_SERVER['DOCUMENT_ROOT'] => /Applications/MAMP/htdocs/symf_greta25/public/;
            $chemin = $_SERVER['DOCUMENT_ROOT'].'uploads/images';

            // récupération de l'objet file (pour pouvoir récupérer le nom de l'image par exemple)
            $file = $conference->getImage()->getFile();

            // récuperation du nom de l'image (il faut toujours utiliser la méthode             
            //get_class_methods pour voir toutes les methodes de l'objet file . exemple 
             //dd(get_class_methods($file)))
            $nom_image = $file->getClientOriginalName();

            // il faut hydrater les propriété alt et url
            // le alt contienyt le nom de l'image
            $conference->getImage()->setAlt($nom_image);
            // l'url contient le chemin relatif de l'image + le nom de l'image
            $conference->getImage()->setUrl('uploads/images/'.$nom_image);

            // la methode move permet de mettre l'image dans le repertoire image
            $file->move($chemin, $nom_image);

            $this->em->persist($conference);
            $this->em->flush();
            // redirection vers la page des conferences
            return $this->redirectToRoute('app_conference.conferences');
        }

        return $this->render('conference/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/conferences', name: 'app_conference.conferences')]
    public function conferences(Request $request, ConferenceRepository $repo): Response
    {
        $conferences = $repo->findAll();
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        // $this->em->getRepository(Conference::class)->findAll();
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
            'categories'=>$categories
        ]);
    }
    #[Route('/conference/categorie/{nom}', name: 'app_conference.categorie')]
    public function categorie($nom, Request $request, ConferenceRepository $repo): Response
    {
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        $conferences = $this->em->getRepository(Conference::class)->findByCategorie($nom);
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
            'categories'=>$categories
        ]);
    }
    #[Route('/conferences/details/{id}', name: 'app_conference.details')]
    public function details($id, Request $request, ConferenceRepository $repo): Response
    {
        $conference = $repo->find($id);
        // $this->em->getRepository(Conference::class)->findAll();
        return $this->render('conference/details.html.twig', [
            'conference' => $conference,
        ]);
    }
  
    #[Route('/conferences/edit/{id}', name: 'app_conference.edit')]
    public function edit($id, Request $request, ConferenceRepository $repo): Response
    {
        $conference = $repo->find($id);
        // $this->em->getRepository(conference::class)->find($id);
        // 
        $form = $this->createForm(ConferenceType::class, $conference);

        // cette fonction permet d'hydrater l'objet conférence
        $form->handleRequest($request);

        // si le formulaire est soummis et que le formulaire est valide
        if($form->isSubmitted()){
            // $this->em->getRepository(conference::class);
            $this->em->persist($conference);
            $this->em->flush();
            // redirection vers la page des conferences
            return $this->redirectToRoute('app_conference.conferences');
        }

        return $this->render('conference/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/conferences/supprimer/{id}', name: 'app_conference.supprimer')]
    public function supprimer($id, Request $request, ConferenceRepository $repo): Response
    {
        $conference = $repo->find($id);
        // $this->em->getRepository(Conference::class)->findAll();
        $this->em->remove($conference);
        $this->em->flush();
        return $this->redirectToRoute('app_conference.conferences');
    }
    #[Route('/reservation/{id}', name: 'app_conference.reservation')]
    public function reservation($id, Request $request, ConferenceRepository $repo): Response
    {

        $reservation = new Reservation();
        // 
        $form = $this->createForm(ReservationType::class, $reservation);

        // cette fonction permet d'hydrater l'objet conférence
        $form->handleRequest($request);

        // si le formulaire est soummis et que le formulaire est valide
        if($form->isSubmitted()){
            // dump($id);
            // dd($reservation);
            $conference = $repo->find($id);
            // dd($conference);
            // $this->em->getRepository(Conference::class);
            // $this->em->getRepository(Reservation::class);
            $reservation->setConference($conference);
            $this->em->persist($reservation);
            $this->em->flush();
            // redirection vers la page des conferences
            return $this->redirectToRoute('app_conference.conferences');
        }

        return $this->render('reservation/reservation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}