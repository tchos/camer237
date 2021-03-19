<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request, ProductRepository $repo): Response
    {
        // on cree une instance de la classe Search pour le filtre
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        // On recupere le contenu du formulaire
        $form->handleRequest($request);

        // Test si soumission ou validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $repo->findWithSearch($search);
        } else {
            //On va dans la bd rechercher tous les produits avec le repository sur l'entité product
            $products = $this->em->getRepository(Product::class)->findAll();
        }

        // On envoie le formulaire et les produits selectionnes a la page
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {
        //On va dans la bd rechercher un produit par son slug avec le repository sur l'entité product
        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);

        //Si on ne trouve pas de produit, on est redirigé à la page des produits
        if (!$product) {
            return $this->redirectToRoute('products');
        }
        return $this->render('product/show.html.twig', ['product' => $product]);
    }
}
