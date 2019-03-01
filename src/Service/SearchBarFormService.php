<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 01/03/19
 * Time: 14:32
 */

namespace App\Service;


use App\Form\SearchFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SearchBarFormService
{
    private $form;

    private $router;

    private $formFactory;

    public function __construct(UrlGeneratorInterface $router, FormFactoryInterface $formFactory)
    {

        $this->router = $router;

        $this->formFactory = $formFactory;

        $this->form = $this->formFactory->create(
            SearchFormType::class, null, ['attr' => [
            'action' => $this->router->generate('searchbar')
        ]]);
    }

    public function getForm()
    {
        return $this->form;
    }
}