<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Lists;
use App\Entity\ListsProducts;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{
    /**
      * @Route("/maliste", name="my_list")
    */
    public function MyList(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $listrepository = $this->getDoctrine()->getRepository(Lists::class);
        $productrepository = $this->getDoctrine()->getRepository(Product::class);
        $listsproductsrepository = $this->getDoctrine()->getRepository(ListsProducts::class);

        //Find current list
        $lists = $listrepository->findOneBy([], ['id' => 'desc']);

        //Form
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('add', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();

        //Processing form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $name = $datas->getName();
            $product_temp = new Product();
            $product_temp = $productrepository->findOneByName($name);
            if(is_null($product_temp)){ //Product doesn't exist in db
              $product_temp = new Product();
              $product_temp->setName($name);
              $entityManager->persist($product_temp);
              $entityManager->flush();

              $listsproduct = new ListsProducts();
              $listsproduct->setLists($lists);
              $listsproduct->setProduct($product_temp);
              $listsproduct->setQuantity(1);
              $entityManager->persist($listsproduct);
              $entityManager->flush();
            }
            else{ //Product exists in db
              $listsproduct = new ListsProducts();
              $listsproduct = $listsproductsrepository->findOneByCoupleId($lists->getId(),$product_temp->getId());
              if(is_null($listsproduct)){ //Product is not in the current list yet
                $listsproduct = new ListsProducts();
                $listsproduct->setLists($lists);
                $listsproduct->setProduct($product_temp);
                $listsproduct->setQuantity(1);
                $entityManager->persist($listsproduct);
                $entityManager->flush();
              }
              else{ //Product is in the current list already
                $listsproduct->setQuantity($listsproduct->getQuantity()+1);
                $entityManager->flush();
              }

            }
        }

        //Find current list content
        $listsproducts = $lists->getStockProducts();

        //Rendering
        return $this->render('list/mylist.html.twig', [
            'form' => $form->createView(),
            'lists' => $lists,
            'listsproducts' => $listsproducts,
        ]);
    }

    /**
      * @Route("/newlist", name="new_list")
    */
    public function NewList()
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listrepository = $this->getDoctrine()->getRepository(Lists::class);

      $new_list = new Lists();
      $new_list->setDate(new \DateTime());
      $entityManager->persist($new_list);
      $entityManager->flush();

      return $this->redirectToRoute('my_list');
    }
}
