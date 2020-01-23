<?php

namespace App\Controller;

use League\Csv\Reader;
use App\Entity\Company;
use App\Entity\Session;
use App\Entity\Trainee;
use App\Form\SessionType;
use Doctrine\ORM\EntityManager;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;

/**
 * @Route("/session")
 */
class SessionController extends AbstractController
{
    /**
     * @Route("/", name="session_index", methods={"GET"})
     */
    public function index(SessionRepository $sessionRepository): Response
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="session_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $upload = dump($request->query->get('upload'));

        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('session_index');
        }

        return $this->render('session/new.html.twig', [
            'session' => $session,
            'upload' => $upload,
            'form' => $form->createView(),
        ]);
    }
  
    /**
    * @Route("/test", name="session_test", methods={"GET"})
    */
   public function test(EntityManagerInterface $em)   
   {

        $reader = Reader::createFromPath('../public/data_formiris.csv', 'r');

        $results = $reader->fetchAssoc();

        $this->em = $em;

        foreach ($results as $row) {

            $trainee = (new Trainee())
                ->setLastName($row["Nom de l'enseignant"])
                ->setFirstName($row["Nom de l'enseignant"])
                ->setEmail($row["Email de l'établissement"])          
            ;

            $this->em->persist($trainee);
            
            $company = (new Company())
                ->setCorporateName($row['UP'])
                // ->setStreet($row[''])
                // ->setPostalCode($row[''])
                // ->setCity($row[''])
                // ->setSiretNumber($row[''])
                // ->setPhoneNumber($row[''])
            ;
                
            if ($row['UP'] == "Immaculee Conception ST JAMES 0501367P" ) {
                
            }else{
                $this->em->persist($company);
            }
            


            // $this->em->persist($company);
            
            $trainee->setCompany($company);
        }
        
        $this->em->flush();

        return $this->render('session/test.html.twig',[
            'row' => $row
        ]); 
   }

    /**
     * @Route("/{id}", name="session_show", methods={"GET"})
     */
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="session_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Session $session): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('session_index');
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="session_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Session $session): Response
    {
        if ($this->isCsrfTokenValid('delete'.$session->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($session);
            $entityManager->flush();
        }

        return $this->redirectToRoute('session_index');
    }

    // /**
    //  * @Route("/test", name="session_test")
    //  */
    // public function execute()
    // {
    //     // $io = new SymfonyStyle($input, $output);

    //     $reader = Reader::createFromPath('../public/data_formiris.csv');

    //     $results = $reader->fetchAssoc();

    //     $this->em = $em;

    //     foreach ($results as $row) {

    //         $trainee = (new Trainee())
    //             ->setLastName($row["Nom de l'enseignant"])
    //             ->setFirstName($row["Nom de l'enseignant"])
    //             ->setEmail($row["Email de l'établissement"])          
    //         ;

    //         $this->em->persist($trainee);
            
    //         // $sql = '
    //         //     SELECT corporate_name FROM company c
    //         //     WHERE c.id = 12 ;';
            

    //         // $query = $qb->getQuery();

    //         // return $query->execute();



    //         $company = (new Company())
    //             ->setCorporateName($row['UP'])
    //             // ->setStreet($row[''])
    //             // ->setPostalCode($row[''])
    //             // ->setCity($row[''])
    //             // ->setSiretNumber($row[''])
    //             // ->setPhoneNumber($row[''])
    //         ;
                
    //         // if ($row['UP'] != $sql ) {
    //         //     $this->em->persist($company);
    //         // }
            
    //         // $output->writeln($qb);

    //         $this->em->persist($company);
            
    //         $trainee->setCompany($company);
    //     }
        
    //     $this->em->flush();

    //     // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    // }

    

   


}
