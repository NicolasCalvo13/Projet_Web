<?php
namespace App\Core;
class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewPath = ROOT . "/app/Views/$view.php";
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        require ROOT . '/app/Views/layouts/main.php';
    }
    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }
}
