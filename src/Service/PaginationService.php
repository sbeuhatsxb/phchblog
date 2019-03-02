<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/03/19
 * Time: 18:40
 */

namespace App\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService extends Controller
{

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    /**
     * @param $qbQueryArticles
     * @param $pageNumber
     * @param $pageLimit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function paginate($qbQueryArticles, $pageNumber, $pageLimit) {

        $request = $this->requestStack->getCurrentRequest();
        
        $articles = $this->paginator->paginate(
            $qbQueryArticles,
            $request->query->getInt('page', $pageNumber),
            $pageLimit
        );

        return $articles;
    }
}