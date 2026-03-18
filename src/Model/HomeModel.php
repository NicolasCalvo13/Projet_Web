<?php

declare(strict_types=1);

namespace App\Model;

class HomeModel
{
    public function getSampleOffers(): array
    {
        // Pour l’instant, données en dur (plus tard: BDD)
        return [
            [
                'title'       => 'Stage Développeur PHP',
                'company'     => 'Web4All',
                'location'    => 'Aix-en-Provence',
                'description' => 'Participation au développement d’une plateforme de stages.',
            ],
            [
                'title'       => 'Stage DevOps',
                'company'     => 'TechCorp',
                'location'    => 'Marseille',
                'description' => 'Mise en place de pipelines CI/CD.',
            ],
        ];
    }
}

