<?php
namespace Tests;

use App\Model\OfferModel;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class OfferModelTest extends TestCase
{
    private PDO $pdoMock;
    private OfferModel $offerModel;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->offerModel = new OfferModel($this->pdoMock);
    }

    public function testFindAllReturnsArray(): void
    {
        $fakeOffers = [
            ['id' => 1, 'titre' => 'Stage Dev Web', 'entreprise_nom' => 'CESI'],
            ['id' => 2, 'titre' => 'Stage Réseau',  'entreprise_nom' => 'Airbus'],
        ];
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetchAll')->willReturn($fakeOffers);
        $this->pdoMock->method('query')->willReturn($stmtMock);
        $result = $this->offerModel->findAll();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Stage Dev Web', $result[0]['titre']);
    }

    public function testGetByIdReturnsNullWhenNotFound(): void
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(false);
        $this->pdoMock->method('prepare')->willReturn($stmtMock);
        $result = $this->offerModel->getById(999);
        $this->assertNull($result);
    }

    public function testFindBySecteurReturnsFilteredOffers(): void
    {
        $fakeOffers = [
            ['id' => 1, 'titre' => 'Stage PHP', 'secteur' => 'IT'],
        ];
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetchAll')->willReturn($fakeOffers);
        $this->pdoMock->method('prepare')->willReturn($stmtMock);
        $result = $this->offerModel->findBySecteur('IT');
        $this->assertCount(1, $result);
        $this->assertEquals('IT', $result[0]['secteur']);
    }
}
