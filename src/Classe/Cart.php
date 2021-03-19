<?php

namespace App\Classe;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $repo;
    public function __construct(SessionInterface $session, ProductRepository $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }

    // Ajouter une carte à ma session
    public function add($id)
    {
        // Avant d'ajouter un élt à une session on recupère d'abord la session
        $cart = $this->session->get('cart', []);

        //S'il y a deja un produit dans la carte, on incrémente seulement sa quantité
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // session->set('...') met à jour un élt à la session
        $this->session->set('cart', $cart);
    }

    /** Fonction pour récupérer ma carte */
    public function get()
    {
        // session->get('...') recupère un élt de la session
        return $this->session->get('cart');
    }

    /** Fonction pour supprimer ma carte */
    public function remove()
    {
        //session->remove('cart') supprime l'element 'cart' de la session 
        return $this->session->remove('cart');
    }

    /* Fonction pour enlever un produit du panier */
    public function delete($id)
    {
        // On recupère le panier de la session qui est en effet un tableau
        $cart = $this->session->get('cart', []);

        // On supprime l'entrée du tableau contenant le produit en question
        unset($cart[$id]);

        // session->set('...') met à jour l'élt 'cart' qui est dans la session
        return $this->session->set('cart', $cart);
    }

    /** Fonction pour diminuer la quantité d'un produit dans le panier */
    public function decrease($id)
    {
        // On recupère le panier de la session qui est en effet un tableau
        $cart = $this->session->get('cart', []);

        /**On vérifie que la quantité en panier est supérieure à 1 
            sinon on supprime simpliement le produit du panier */
        if ($cart[$id] > 1) {
            // on diminue la quantité
            $cart[$id]--;
        } else {
            // on retire le produit du panier
            unset($cart[$id]);
        }

        // session->set('...') met à jour l'élt 'cart' qui est dans la session
        return $this->session->set('cart', $cart);
    }

    /** Fonction pour récupérer une carte complète: produit et quantité */
    public function getFull()
    {
        $cartComplete = [];

        // On vérifie qu'il y a un produit dans le panier avant de le transmettre
        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product = $this->repo->findOneById($id);

                if (!$product) {
                    $this->delete($id);
                    continue;
                }

                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }
}
