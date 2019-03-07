<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 07/03/19
 * Time: 09:45
 */

namespace App\Tests\Service;


use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\IndexArticleService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;


class IndexArticleServiceTest extends WebTestCase
{

    public function testDictionnary()
    {

        $indexArticleService = $this->getIndexArticleService();

        $articleArrays = [
            ['Test'],
            ['test'],
            ['test!'],
            ['test!azoieuazouezao'],
            ['l\'test'],
            ['"test"'],
            ['"test,test'],
            ['"test,test"'],
        ];

        foreach ($articleArrays as $articleArray) {
            /**
             * @var IndexArticleService $indexArticleService
             */
            $result = $indexArticleService->createDictonnary($articleArray);

            $this->assertEquals(["test"], $result);
        }

    }

    public function getIndexArticleService()
    {

        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        $container = self::$container;

        $indexArticleService = self::$container->get('indexArticleService');
        return $indexArticleService;
    }
}