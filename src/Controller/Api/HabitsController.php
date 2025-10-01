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

    public function index()
    {
        $habits = $this->habitRepository->findAll();
        $habitsArray = array_map(function($habit) {
            return get_object_vars($habit);
        }, $habits);

        return $this->json([
            'habits' => $habitsArray
        ]);
    }
}