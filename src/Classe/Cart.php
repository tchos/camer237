<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
        // session->add('...') ajoute un élt à la session
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
}
