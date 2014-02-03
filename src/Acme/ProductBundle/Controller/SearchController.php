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
            $qb = $this
                ->getDoctrine()
                ->getRepository('AcmeProductBundle:Product')
                ->createSearchQueryBuilder($form->getData())
                ->orderBy('p.name', 'ASC')
                ->setMaxResults(100);
            $results = $qb->getQuery()->getResult();
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
