<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CsvFile;
use App\Form\CsvUploadType;

class HomeController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var \Doctrine\Persistence\ObjectRepository */
    private $csvFileRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->csvFileRepository = $entityManager->getRepository('App:CsvFile');
    }

    #[Route('/', name: 'index')]
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/upload', name: 'file_upload')]
    public function uploadCsvFile(Request $request) {
        $file = new CsvFile();

        $form = $this->createForm(CsvUploadType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileNaam = basename($_FILES["csv_upload"]["name"]["csvFile"]["file"]);
            $dir = 'csv/files/'.$fileNaam[0].$fileNaam[1].$fileNaam[2].'/'.$fileNaam;
            if(file_exists($dir)) {
                $this->addFlash('info', 'File already exists');
                return $this->redirectToRoute('file_upload');
            }
            $fileType = pathinfo($dir,PATHINFO_EXTENSION);
            // Valideer formaat
            if($fileType != "csv") {
              $this->addFlash('info', 'File must be of type: \'.csv\'');
              return $this->redirectToRoute('file_upload');
            }
            $this->entityManager->persist($file);
            $this->entityManager->flush($file);

            $request->getSession()->set('user_is_author', true);
            $this->addFlash('success', 'file uploaded');
            
            return $this->redirectToRoute('home');
        }

        return $this->render('home/upload.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
