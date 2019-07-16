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
    * @Route("/", name="tricks_index")
    */
	public function index()
	{

		return $this->render('tricks/index.html.twig');

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


}