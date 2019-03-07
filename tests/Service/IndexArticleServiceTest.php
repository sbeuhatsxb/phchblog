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


class IndexArticleServiceTest extends WebTestCase
{

//    public $indexArticleService;
//
//    public function __construct(IndexArticleService $indexArticleService)
//    {
//        $this->indexArticleService = $indexArticleService;
//    }

    public function testDictionnary()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        //get the DI container
        self::$container = $kernel->getContainer();

        //now we can instantiate our service (if you want a fresh one for
        //each test method, do this in setUp() instead
        $indexArticleService = self::$container->get('indexArticleService');

        $article = ['sebastien, sebastien'];


        $result = $this->indexArticleService->createDictonnary($article);

        $this->assertEquals("sebastien", $result);
    }
}