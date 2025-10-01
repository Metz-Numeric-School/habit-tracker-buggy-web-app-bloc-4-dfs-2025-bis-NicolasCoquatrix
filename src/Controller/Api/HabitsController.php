<?php

namespace App\Controller\Api;

use App\Repository\HabitRepository;
use Mns\Buggy\Core\AbstractController;

// Correction : Erreur sur le nom de la classe (HabitController au lieu de HabitsController)
class HabitsController extends AbstractController
{
    private HabitRepository $habitRepository;

    public function __construct()
    {
        $this->habitRepository = new HabitRepository();
    }

    // Correction : Renvoi des données
    public function index()
    {
        $habits = $this->habitRepository->findAll();
        $habitsArray = array_map(function($habit) {
            return [
                'id' => $habit->getId(),
                'name' => $habit->getName(),
                'description' => $habit->getDescription(),
            ];
        }, $habits);

        return $this->json([
            'habits' => $habitsArray
        ]);
    }
}