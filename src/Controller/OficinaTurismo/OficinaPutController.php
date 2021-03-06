<?php

namespace App\Controller\OficinaTurismo;

use App\Entity\OficinaTurismo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for all the actions of this controller
 *
 * @IsGranted("ROLE_ADMIN")
 */
class OficinaPutController extends AbstractController
{
    #[Route('/oficina/put/{uid}', name: 'app_oficina_put')]
    public function put(ManagerRegistry $doctrine, OficinaTurismo $oficina, Request $request): Response
    {
        $form = $this->createFormBuilder($oficina)
                ->add("direccion", TextType:: class, [
                    'label' => 'Dirección*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca la direccion',
                    ])
                    ]
                ])
                ->add("telefono", TextType:: class, [
                    'label' => 'Teléfono*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el télefono de contacto',
                    ])
                    ]
                ])
                ->add("email", TextType:: class, [
                    'label' => 'Email*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el email',
                    ])
                    ]
                ])
                ->add("horario", TextareaType:: class, [
                    'label' => 'Horario*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el horario de la oficina',
                    ])
                    ]
                ])
                ->add("localidad", TextType:: class, [
                    'label' => 'Localidad*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca la localidad de la oficina',
                    ])
                    ]
                ])
                ->add('enviar', SubmitType::class)
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oficina = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($oficina);
            $entityManager->flush();
            $this->addFlash("aviso","Datos de la oficina actualizados con éxito");

            return $this->redirectToRoute("admin_oficina_get");
        } else{
            return $this->renderForm("Oficina/oficina_put/index.html.twig", ['formulario' => $form]);
        }
    }
}
