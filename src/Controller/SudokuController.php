<?php

namespace App\Controller;

use App\Form\SudokuType;
use App\Manager\SudokuManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sudoku")
 */
class SudokuController extends AbstractController
{
    /**
     * @Route("/", name="sudoku")
     */
    public function index(Request $request, SudokuManager $sudokuManager)
    {
        $form = $this->createForm(SudokuType::class, null);
        $form->handleRequest($request);

        $grid = $sudokuManager->getOneGrid();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $grid */
            $file = $form->get('file')->getData();

            if ($file) {
                $grid = $sudokuManager->buildGrid($file);
            }
        }

        if ($grid) {
            $start = microtime(TRUE);
            $res = $sudokuManager->getGridSolution($grid);
            $end = microtime(TRUE);
            echo "The code took " . ($end - $start) . " seconds to complete.";
        }

        return $this->render('Sudoku/index.html.twig', [
            'form' => $form->createView(),
            'grid' => $grid
        ]);
    }
}