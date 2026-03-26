<?php

namespace App\Controller;

use Twig\Environment;

class PiloteController extends BaseController {

    private Environment $twig;

    public function __construct(Environment $twig) {
        $this->requireRole('pilote');
        $this->twig = $twig;
    }

    public function dashboard(): string {
        return $this->twig->render('pilote/dashboard.twig.html', [
            'page_title' => 'Tableau de bord Pilote - Stage-Link',
        ]);
    }
}