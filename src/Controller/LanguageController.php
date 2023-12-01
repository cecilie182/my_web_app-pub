<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    /**
     * @Route("/switchLocale/{locale}", name="switch_locale")
     */
    public function switchLocale($locale)
    {
        $this->get('session')->set('_locale', $locale);
        return $this->redirectToRoute('cover');
    }
}