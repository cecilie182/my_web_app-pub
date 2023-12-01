<?php

namespace App\Controller;

use App\Document\Challenge\Product;
use App\Document\Challenge\ProductSet;
use App\Form\Challenge\ProductSetType;
use App\Manager\ChallengeManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ChallengeControler extends AbstractController
{
    /**
     * @Route("/challenge", name="challenge")
     */
    public function index()
    {
        return $this->render('Challenge/index.html.twig');
    }

    /**
     * @Route("/pb1", name="pb1")
     */
    public function makeTeams(Request $request, ChallengeManager $manager)
    {
        $levels = array(8,5,6,9,3,8,2,4,6,10,8,5,6,1,7,10,5,3,7,6.);

        $form = $this->get('form.factory')->createNamedBuilder('levels', FormType::class, null, [
            'action' => $this->generateUrl('pb1'),
            'method' => 'POST'
        ])
            ->add('levels', TextType::class, array(
                'help' => 'Exemple: 7,3,2,10'
            ))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $levels = explode(",", $data['levels']);
            }
        }
        //$levels === array_filter($levels,'is_int'); // true

        $result = $manager->makeTeams($levels);
        $team1 = $result[0];
        $team2 = $result[1];

        return $this->render('Challenge/pb1.html.twig', array(
            'levels' => $levels,
            'team1' => $team1,
            'team2' => $team2,
            'level1' => array_sum($team1),
            'level2' => array_sum($team2),
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/pb2", name="pb2")
     */
    public function createImage()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');

        $width = 640;
        $height = 480;
        $image = imagecreate($width, $height);

        $red = imagecolorallocate($image, 255, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);
        //imagearc($image, 100, 100, 200, 200,  0, 360, $white);
        imagefilledpolygon($image, array(
            310, 465, //bas de la tige gauche
            316, 360, //haut de la tige gauche
            220, 375, //pointe feuille basse
            230, 340,
            100, 250, //pointe 1 feuille gauche
            135, 230,
            110, 160, //pointe 2 feuille gauche
            180, 180,
            195, 145, //pointe 3 feuille gauche
            255, 190,
            240, 85, //pointe centrale gauche
            278, 110,
            320, 20, //pointe centrale
            362, 110,
            400 ,85, //pointe centrale droite
            385, 190,
            445, 145, //pointe 1 feuille droite
            460, 180,
            530, 160, //pointe 2 feuille droite
            505, 230,
            540, 250, //pointe 3 feuille droite
            410, 340,
            420, 375,
            324, 360,
            330, 465
        ),
            25,
            $red);

        imagepng($image);
        imagedestroy($image);
        return $response;
    }

    /**
     * @Route("/pb4", name="pb4")
     */
    public function shipProducts(Request $request)
    {
        $set = new ProductSet();

        $fiddle = new Product('Fiddle', 1, 60, 20, 10);
        $dish = new Product('Dish', 0.1, 30, 30, 5);
        $spoon = new Product('Spoon', 0.05, 15, 5, 2);

        $set->setProducts(new ArrayCollection([$fiddle, $dish, $spoon]));

        $form = $this->createForm(ProductSetType::class, $set);
        $form->handleRequest($request);

        return $this->render('Challenge/pb4.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}