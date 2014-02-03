<?php

namespace Acme\ProductBundle\Controller;

use Acme\ProductBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new SearchType());
        $results = array();

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('search')->isClicked()) {
                $qb = $this
                    ->getDoctrine()
                    ->getRepository('AcmeProductBundle:Product')
                    ->createSearchQueryBuilder($form->getData())
                    ->orderBy('p.name', 'ASC')
                    ->setMaxResults(100);
                $results = $qb->getQuery()->getResult();
            } else if ($form->get('elastic_search')->isClicked()) {
                $results = $this->get('fos_elastica.manager')
                    ->getRepository('AcmeProductBundle:Product')
                    ->searchProducts($form->getData());
            }
        }

        return $this->render(
            'AcmeProductBundle:Search:index.html.twig',
            array(
                'form' => $form->createView(),
                'results' => $results
            )
        );
    }
}
