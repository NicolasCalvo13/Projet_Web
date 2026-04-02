<?php
namespace Tests\Controller;

use App\Controller\OfferController;
use App\Model\OfferModel;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use ReflectionClass;

class OfferControllerTest extends TestCase
{
    private $twigMock;
    private $modelMock;
    private OfferController $controller;

    protected function setUp(): void
    {
        // 1. On vide les variables globales avant chaque test pour éviter les interférences
        $_GET = [];
        $_POST = [];
        $_SESSION = [];

        // 2. On crée un Mock (un faux objet) de l'environnement Twig
        $this->twigMock = $this->createMock(Environment::class);

        // 3. On crée un Mock du OfferModel
        $this->modelMock = $this->createMock(OfferModel::class);

        // 4. On instancie le contrôleur avec le faux Twig
        $this->controller = new OfferController($this->twigMock);

        // 5. ASTUCE : On utilise la Reflection pour forcer l'injection de notre faux modèle 
        // (car le contrôleur fait un "new OfferModel()" en dur dans son constructeur)
        $reflection = new ReflectionClass($this->controller);
        $property = $reflection->getProperty('model');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->modelMock);
    }

    public function testIndexRendersOffersList(): void
    {
        // Données fictives que le modèle est censé renvoyer
        $fakeOffers = [
            ['id' => 1, 'titre' => 'Stage Dev Web', 'entreprise_nom' => 'CESI']
        ];

        // On dit au faux modèle : "Quand on t'appelle sur findAll(), renvoie $fakeOffers"
        $this->modelMock->expects($this->once())
            ->method('findAll')
            ->willReturn($fakeOffers);

        // On dit au faux Twig : "Tu dois être appelé une fois avec la vue 'offers/list.twig.html'"
        $this->twigMock->expects($this->once())
            ->method('render')
            ->with('offers/list.twig.html', [
                'page_title' => 'Toutes les offres - Stage-Link',
                'offers' => $fakeOffers,
            ])
            ->willReturn('<html>Fake Rendered List</html>');

        $result = $this->controller->index();
        $this->assertEquals('<html>Fake Rendered List</html>', $result);
    }

    public function testShowReturnsBadRequestWhenIdIsMissing(): void
    {
        // On ne définit pas $_GET['id'] délibérément
        $result = $this->controller->show();
        
        $this->assertEquals('Bad request', $result);
        $this->assertEquals(400, http_response_code());
    }

    public function testShowRendersOfferDetailWhenIdIsValid(): void
    {
        // On simule l'URL /?uri=offer_detail&id=5
        $_GET['id'] = '5';
        $fakeOffer = ['id' => 5, 'titre' => 'Stage Cloud'];

        // Le modèle doit être appelé pour récupérer l'offre 5
        $this->modelMock->expects($this->once())
            ->method('getById')
            ->with(5)
            ->willReturn($fakeOffer);

        // Le Twig doit être appelé pour rendre la page détail
        $this->twigMock->expects($this->once())
            ->method('render')
            ->with('offers/detail.twig.html', $this->anything())
            ->willReturn('<html>Fake Detail Page</html>');

        $result = $this->controller->show();
        $this->assertEquals('<html>Fake Detail Page</html>', $result);
    }

    public function testSearchRendersResultsForValidKeyword(): void
    {
        // On simule une recherche
        $_GET['q'] = 'PHP';
        $fakeOffers = [['id' => 1, 'titre' => 'Stage PHP']];

        $this->modelMock->expects($this->once())
            ->method('searchOffers')
            ->with('PHP')
            ->willReturn($fakeOffers);

        $this->twigMock->expects($this->once())
            ->method('render')
            ->with('offers/search_results.twig.html', $this->anything())
            ->willReturn('<html>Fake Search Results</html>');

        $result = $this->controller->search();
        $this->assertEquals('<html>Fake Search Results</html>', $result);
    }
}

/*Pour tester lancer cette commande : C:\xampp\php\php.exe vendor\bin\phpunit tests\OfferControllerTest.php*/