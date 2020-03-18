<?php
namespace FrontOffice\Controller\Shopping;

use Core\Entity\Address;
use Core\Form\AddressType;
use Core\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use FrontOffice\Form\Shopping\SelectAddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use FrontOffice\Entity\Basket;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CheckoutController extends AbstractController
{

    private $basket;

    private $session;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->basket = new Basket($objectManager);
        $this->session = new Session();
    }

    /**
     * @Route("checkout/address", name="checkoutAddress")
     * @IsGranted("ROLE_USER")
     * @param                     Request                $request
     * @param                     EntityManagerInterface $em
     * @return                    Response
     */
    public function checkoutAddress(Request $req, AddressRepository $addressRepository): Response
    {
        if (!$this->basket->hasProducts()) {
            return $this->redirectToRoute('basket');
        }
    
        $addresses = $addressRepository->findBy(['user' => $this->getUser()]);
        
        if (!$addresses) {
            $this->session->set('checkout/current-checkout', true);
            $this->addFlash('danger', 'Veuillez renseigner une adresse de livraison avant de continuer');
    
            return $this->render(
                'front_office/shopping/checkout/address.html.twig',
                [
                'address_form' => $this->createForm(AddressType::class)->createView(),
                ]
            );
        }
    
        $this->session->set('checkout/address-current-basket', true);
        
        $form = $this->createForm(SelectAddressType::class, null, ['addresses' => $addresses]);
        
        $form->handleRequest($req);
        
        if ($form->isSubmitted()) {
            $this->session->set('checkout/address', true);
            
            return $this->redirectToRoute('checkoutSummary');
        }

        return $this->render(
            'front_office/shopping/checkout/address.html.twig',
            [
            'address_choice_form' => $form->createView(),
            ]
        );
    }
    
    /**
     * @IsGranted("ROLE_USER")
     * @Route("checkout/shopping", name="checkoutShipping")
     */
    public function shipping()//Request $req)
    {
        /*        if (!$this->session->get('checkout/address')) {
            return $this->redirectToRoute('basket');
        }

        $form = $this->createForm(\FrontOffice\Form\Shopping\ShippingMethodType::class, null);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $shippingMethod = $form->getData()['shippingMethod'];

            $this->basket->addShippingMethod($shippingMethod);

            $this->session->set('checkout/shopping', true);

            return $this->redirectToRoute('checkoutSummary');
        }

        return $this->render('front_office/shopping/checkout/shopping.html.twig', [
            'form' => $form->createView(),
        ]);*/
    }
    
    /**
     * @IsGranted("ROLE_USER")
     * @Route("checkout/summary", name="checkoutSummary")
     */
    public function summary()
    {
        if (!$this->basket->hasProducts()) {
            return $this->redirectToRoute('basket');
        }
        
        if (!$this->session->get('checkout/address')) {
            return $this->redirectToRoute('checkoutAddress');
        }
        
        $this->session->set('checkout/summary', true);
    
        $products = $this->basket->getProducts();
        $productsWithQuantity = [];
        $totalPrice  = 0;
    
        foreach ($products as $product) {
            $qte =$this->basket->getQuantity($product);
            $totalPrice += $this->basket->totalPrice([$product]) * $qte;
        
            $productsWithQuantity[] = [
               'product' => $product,
               'quantity' => $qte,
               'thisPrice' => $this->basket->totalPrice([$product]) * $qte
            ];
        }
        
        //$vatPrice = $this->basket->vatPrice($this->basket->grandTotal());
        //$grandTotal = $this->basket->grandTotal();
        
        return $this->render(
            'front_office/shopping/checkout/summary.html.twig',
            [
            'products' => $productsWithQuantity,
            'totalPrice' => $totalPrice
            ]
        );
    }
    
    /**
     * @IsGranted("ROLE_USER")
     * @Route("checkout/payment", name="checkoutPayment")
     */
    public function payment()
    {
        if (!$this->session->get('checkout/summary')) {
            return $this->redirectToRoute('checkoutSummary');
        }
        
        $this->session->remove('checkout/summary');
        $this->session->set('checkout/payment', true);

        return $this->render(
            'front_office/shopping/checkout/payment.html.twig',
            [
            'total_price' => $this->basket->grandTotal(),
            ]
        );
    }
}
