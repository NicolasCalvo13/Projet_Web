<?php

namespace App\Controller;

use App\Model\UserModel;
use Twig\Environment;

class StudentController extends BaseController {

    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig) {
        $this->requireRole('student'); 
        $this->twig      = $twig;
        $this->userModel = new UserModel();
    }

    

}