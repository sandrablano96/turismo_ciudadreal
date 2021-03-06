<?php

namespace App\Controller\Museo;
use App\Entity\Museo;
use App\Form\PiezaType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

/**
 * Require ROLE_ADMIN for all the actions of this controller
 *
 * @IsGranted("ROLE_ADMIN")
 */
class MuseoPutController extends AbstractController
{
    #[Route('/museo/put/{uid}', name: 'app_museo_put')]
    public function put(Museo $museo, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $imagen = $museo->getImagen();
        $museo->setImagen(
            new File($this->getParameter('museum_directory').'/'.$museo->getImagen())
        );
        $form = $this->createFormBuilder($museo)
                ->add("nombre", TextType:: class, [
                    'label' => 'Nombre*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el nombre',
                    ])
                    ]
                ])
                ->add("descripcion", TextareaType:: class, [
                    'label' => 'Descripción*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca la descriipción',
                    ])
                    ]
                ])
                ->add("direccion", TextType:: class, [
                    'label' => 'Dirección*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca la dirección',
                    ])
                    ]
                ])
                ->add("telefono", TelType:: class, [
                    'label' => 'Teléfono*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el teléfono',
                    ])
                    ]
                ])
                ->add("email", EmailType:: class, [
                    'label' => 'Email',
                    'required' => false,
                ])
                ->add("horario", TextareaType:: class, [
                    'label' => 'Horario*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el horario',
                    ])
                    ]
                ])
                ->add("precio", TextareaType:: class, [
                    'label' => 'Precio',
                    'required' => false
                ])
                ->add("web", UrlType:: class, [
                    'label' => 'Web',
                    'required' => false
                ])
                ->add("imagen", FileType::class, [
                    'label' => 'Imagen',
                    'data_class' => null,
                    'required' => false,
                    'mapped' => false
                ])
                ->add('enviar', SubmitType::class)
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $museo = $form->getData();
            
            $foto = $form->get('imagen')->getData();
            //subimos la imagen
            if ($foto) {
                $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $foto->move($this->getParameter('museum_directory'),
                        $newFilename
                    );
                    $museo->setImagen($newFilename);
                } catch (FileException $e) {
                    console.log($e);
                }
            } else{
                $museo->setImagen($imagen);
            }    
            
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($museo);
            $entityManager->flush();
    
            $this->addFlash("aviso","Museo actualizado con éxito");
            
            return $this->redirectToRoute('admin_museo_get', [
                'uid' => $museo->getUid()
            ]);
        } else{
            return $this->renderForm("Museo/museo_put/index.html.twig", ['formulario' => $form, 'imagen' => $imagen, 'alt' => $museo->getNombre()]);
        }
    }
}
