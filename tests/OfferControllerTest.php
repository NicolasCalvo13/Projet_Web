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
        $_GET = [];
        $_POST = [];
        $_SESSION = [];

        $this->twigMock = $this->createMock(Environment::class);

        $this->modelMock = $this->createMock(OfferModel::class);

        $this->controller = new OfferController($this->twigMock);

        $reflection = new ReflectionClass($this->controller);
        $property = $reflection->getProperty('model');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->modelMock);
    }

    public function testIndexRendersOffersList(): void
    {
        $fakeOffers = [
            ['id' => 1, 'titre' => 'Stage Dev Web', 'entreprise_nom' => 'CESI']
        ];

        $this->modelMock->expects($this->once())
            ->method('findAll')
            ->willReturn($fakeOffers);

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
        $result = $this->controller->show();
        
        $this->assertEquals('Bad request', $result);
        $this->assertEquals(400, http_response_code());
    }

    public function testShowRendersOfferDetailWhenIdIsValid(): void
    {
        $_GET['id'] = '5';
        $fakeOffer = ['id' => 5, 'titre' => 'Stage Cloud'];

        $this->modelMock->expects($this->once())
            ->method('getById')
            ->with(5)
            ->willReturn($fakeOffer);

        $this->twigMock->expects($this->once())
            ->method('render')
            ->with('offers/detail.twig.html', $this->anything())
            ->willReturn('<html>Fake Detail Page</html>');

        $result = $this->controller->show();
        $this->assertEquals('<html>Fake Detail Page</html>', $result);
    }

    public function testSearchRendersResultsForValidKeyword(): void
    {
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