<?php

declare(strict_types=1);

namespace App\Model;

class OfferModel
{
    private array $offers = [];

    public function __construct()
    {
        // Données en dur pour l’instant
        $this->offers = [
            1 => [
                'id'          => 1,
                'title'       => 'Stage Développeur PHP',
                'company'     => 'Web4All',
                'location'    => 'Aix-en-Provence',
                'skills'      => ['PHP', 'MVC', 'Git'],
                'salary'      => '1000 € / mois',
                'date'        => '2026-03-01',
                'description' => 'Participation au développement d’une plateforme de stages CESI.',
            ],
            2 => [
                'id'          => 2,
                'title'       => 'Stage Frontend JS',
                'company'     => 'TechCorp',
                'location'    => 'Marseille',
                'skills'      => ['HTML', 'CSS', 'JavaScript'],
                'salary'      => '900 € / mois',
                'date'        => '2026-02-15',
                'description' => 'Intégration de maquettes et amélioration de l’UX d’un portail étudiant.',
            ],
        ];
    }

    public function getAll(): array
    {
        return array_values($this->offers);
    }

    public function getById(int $id): ?array
    {
        return $this->offers[$id] ?? null;
    }
}

