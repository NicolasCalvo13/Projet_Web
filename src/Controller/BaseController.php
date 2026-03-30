<?php

namespace App\Controller;

class BaseController {
    protected function requireAuth(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?uri=login');
            exit;
        }
    }

    protected function requireRole(string $role): void {
        $this->requireAuth();
        if ($_SESSION['role'] !== $role) {
            header('Location: /?uri=login');
            exit;
        }
    }
}