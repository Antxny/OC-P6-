<?php 

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Comments;
use App\Repository\TricksRepository;
use App\Repository\CommentsRepository;
use App\Form\TricksNewFormType;
use App\Form\CommentsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TricksController extends AbstractController
{

    /**
    * @var TricksRepository
    */
    private $repository;

    public function __construct(TricksRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
    * @Route("/", name="tricks_index")
    * @param TricksRepository $tricks
    */
	public function index()
	{

        $tricks = $this->repository->findAllTricks();
        
        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]);

	}

	/**
    * @Route("/new-trick", name="tricks_new")
    */
	public function newTricks(Request $request): Response
    {
        $tricks = new Tricks();
        $form = $this->createForm(TricksNewFormType::class, $tricks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tricks);
            $entityManager->flush();

            $uploads_directory = $this->getParameter('upload_directory');

            $files = $request->files->get('tricks_new_form')['images'];

            $images = array();

            foreach ($files as $file) {

            	$filename = $tricks->getId() .'_' .uniqid() .'.' .$file->guessExtension();
            	$file->move(
	            	$uploads_directory,
	            	$filename
	            );

	            array_push($images, $filename);

            }

            $tricks->setImages($images);
            $tricks->setThumbnail($images[0]);
            $entityManager->persist($tricks);
            $entityManager->flush();


            $this->addFlash('success', 'Article Created! Knowledge is power!');

            return $this->redirectToRoute('tricks_index');

            // do anything else you need here, like send an email

        } else {

        	return $this->render('tricks/new.html.twig', [
            	'tricksNewForm' => $form->createView(),
        	]);

        }

    }

    /**
    * @Route("/tricks/details/{id}-{slug}", name="trick_view", requirements={"slug": "[a-z0-9\-]*"})
    */
    public function viewTricks(Request $request, $slug, $id)
    {

        $tricks = $this->repository->find($id);
        $images = $this->repository->findImages($id);

        return $this->render('tricks/view.html.twig', [
            'tricks' => $tricks,
            'images' => $images,
        ]);

    }



    /**
    * @Route("/t/edit", name="trick_edit")
    */
    public function edit()
    {

        

    }

    /**
    * @Route("/t/delete", name="trick_delete")
    */
    public function deleteTricks()
    {

        

    }


}