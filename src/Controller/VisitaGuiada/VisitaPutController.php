<?php


namespace App\Controller\VisitaGuiada;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\GuiaTurismo;
use App\Entity\OficinaTurismo;
use App\Entity\VisitaGuiada;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for all the actions of this controller
 *
 * @IsGranted("ROLE_ADMIN")
 */
class VisitaPutController extends AbstractController
{
    #[Route('/visita/put/{uid}', name: 'app_visita_put')]
    public function put(Request $request, ManagerRegistry $doctrine, VisitaGuiada $visita): Response
    {
        $guias = $doctrine->getRepository(GuiaTurismo::class)->findAll();
        $oficinas = $doctrine->getRepository(OficinaTurismo::class)-> findAll();
        $form = $this->createFormBuilder($visita)
                ->add("titulo", TextType:: class, [
                    'label' => 'Título*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca el titlo de la visita',
                    ])
                    ]
                ])
                ->add("fecha", DateType:: class, [
                    'label' => 'Fecha*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca la fecha',
                    ])
                    ]
                ])
                ->add("descripcion", TextType:: class, [
                    'label' => 'Descripción*',
                    'required' => true,
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca una breve descripción',
                    ])
                    ]
                ])
                ->add("precio", TextType:: class, [
                    'label' => 'Precio',
                    "required" => false
                ])
                ->add('oficinaTurismo', EntityType::class, [
                    'class' => OficinaTurismo::class,
                    'label' => 'Oficina de Turismo',
                    'choice_label' => 'localidad', 
                    'choice_value' => 'uid', 
                    'required' => false, 
                    'placeholder' => 'Oficinas disponibles'
                ])
                ->add('guiaTurismo', EntityType::class, [
                    'class' => GuiaTurismo::class,
                    'choices' => $guias, 
                    'choice_label' => 'nombre', 
                    'choice_value' => 'uid', 
                    'required' => false, 
                    'placeholder' => 'Guias disponibles'
                ])
                ->add('enviar', SubmitType::class)
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $visita = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($visita);
            $entityManager->flush();
            $this->addFlash("aviso","Visita guiada actualizada con éxito");
    
            if($visita->getGuiaTurismo() != null){
                return $this->redirectToRoute("app_guia_visitas_get", ['uid' => $visita->getGuiaTurismo()->getUid()]);
            }
            return $this->redirectToRoute("admin_oficina_get", ['uid' => $visita->getOficinaTurismo()->getUid()]);
        } else{
            return $this->renderForm("Visita/visita_put/index.html.twig", ['formulario' => $form]);
        }
    }
}
