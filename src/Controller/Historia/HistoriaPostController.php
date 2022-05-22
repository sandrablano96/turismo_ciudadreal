<?php

namespace App\Controller\Historia;

use App\Entity\Historia;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use ramsey\Uuid\Uuid;
class HistoriaPostController extends AbstractController
{
    #[Route('/historia/post', name: 'app_historia_post')]
    public function post(ManagerRegistry $doctrine, Request $request): Response
    {
        $historia = new Historia();
        $form = $this->createFormBuilder($historia)
                ->add("historia", TextType:: class, [
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el texto que desea mostrar en la página de historia',
                    ])
                    ]
                ])
                ->add("imagen", FileType:: class, [
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca al menos una imagen',
                    ])
                    ]
                ])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $historia = $form->getData();
            $uuid = Uuid::uuid4();
            $historia->setUuid($uuid->toString());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($historia);
            $entityManager->flush();
            $this->addFlash("aviso","Historia de la localidad guardada con éxito");

            return $this->redirectToRoute("");
        } else{
            return $this->renderForm("Historia/historia_post/index.html.twig", ['formulario' => $form]);
        }
    
    }
}