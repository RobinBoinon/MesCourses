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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ListController extends AbstractController
{
    /**
      * @Route("/maliste", name="my_list")
    */
    public function MyList(Request $request): Response
    {
      $encoders = [new JsonEncoder()];
      $normalizers = [new ObjectNormalizer()];
      $serializer = new Serializer($normalizers, $encoders);

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
            $this->AddProductToList($datas, $lists);
        }

        //Find current list content
        $listsproducts = $lists->getStockProducts();

        //Find all product in db
        $allproducts = $productrepository->findAll();
        $return_array = array();
        $i=0;
        foreach($allproducts as $allproduct)
        {
            $return_array[$i]['id'] = $allproduct->getId();
            $return_array[$i]['name'] = $allproduct->getName();
            $return_array[$i]['imagelink'] = $allproduct->getImageLink();
            $i++;
        }

        //Rendering
        return $this->render('list/mylist.html.twig', [
            'form' => $form->createView(),
            'lists' => $lists,
            'listsproducts' => $listsproducts,
            'allproducts' => json_encode($return_array),
        ]);
    }

    /**
      * @Route("/addproducttolist", name="addproducttolist")
    */
    public function AddProductToListRoute()
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listrepository = $this->getDoctrine()->getRepository(Lists::class);
      $productrepository = $this->getDoctrine()->getRepository(Product::class);

      //Find current list
      $lists = $listrepository->findOneBy([], ['id' => 'desc']);

      //Find product
      $product = $productrepository->findOneBy(['id' => $_GET['productId']]);

      $this->AddProductToList($product, $lists);

      return $this->redirectToRoute('my_list');
    }

    public function AddProductToList($datas, $lists)
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listrepository = $this->getDoctrine()->getRepository(Lists::class);
      $productrepository = $this->getDoctrine()->getRepository(Product::class);
      $listsproductsrepository = $this->getDoctrine()->getRepository(ListsProducts::class);

      $name = $datas->getName();
      $product_temp = new Product();
      $product_temp = $productrepository->findOneByName(strtolower($name));
      if(is_null($product_temp)){ //Product doesn't exist in db
        $product_temp = new Product();
        $product_temp->setName(ucfirst($name));
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

    /**
      * @Route("/quantityminus", name="quantity_minus")
    */
    public function QuantityMinus(): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listsproductsrepository = $this->getDoctrine()->getRepository(ListsProducts::class);

      $productId = $_GET['productId'];

      $myrow = $listsproductsrepository->findOneByProductId($productId);
      $newquantity = $myrow->getQuantity()-1;
      $myrow->setQuantity($newquantity);
      $entityManager->flush();

      if($newquantity == 0){
        $entityManager->remove($myrow);
        $entityManager->flush();
      }

      return $this->json(['newquantity' => $newquantity]);
    }

    /**
      * @Route("/quantityplus", name="quantity_plus")
    */
    public function QuantityPlus(): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listsproductsrepository = $this->getDoctrine()->getRepository(ListsProducts::class);

      $productId = $_GET['productId'];

      $myrow = $listsproductsrepository->findOneByProductId($productId);
      $newquantity = $myrow->getQuantity()+1;
      $myrow->setQuantity($newquantity);
      $entityManager->flush();

      return $this->json(['newquantity' => $newquantity]);
    }

    /**
      * @Route("/deleteproductfromcurrentlist", name="deleteproductfromcurrentlist")
    */
    public function DeleteProductFromCurrentList(): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      $listsproductsrepository = $this->getDoctrine()->getRepository(ListsProducts::class);

      $productId = $_GET['productId'];

      $myrow = $listsproductsrepository->findOneByProductId($productId);
      $entityManager->remove($myrow);
      $entityManager->flush();

      return $this->json(['ok' => 1]);
    }

    /**
      * @Route("/findgoogleimage", name="findgoogleimage")
    */
    public function FindGoogleImage(): Response
    {
      $url = 'https://www.google.com/search?q='.$_GET['productName'].'&tbm=isch';
      $googlehtml = file_get_contents($url);
      $srcs = explode("src=\"", $googlehtml);
      $return = array();
      $i = 0;
      foreach ($srcs as $src) {
          $temp = explode("\"", $src);
          $return[$i] = $temp[0];
          $i++;
      }

      return $this->json($return);
    }

    /**
      * @Route("/defineimageforproduct", name="defineimageforproduct")
    */
    public function DefineImageForProduct(): Response
    {
      $entityManager = $this->getDoctrine()->getManager();
      $productsrepository = $this->getDoctrine()->getRepository(Product::class);

      $productId = $_GET['productId'];
      $imageLink = $_GET['imageLink'];

      $myrow = $productsrepository->findOneBy(['id' => $productId]);
      $myrow->setImageLink($imageLink);
      $entityManager->flush();

      return $this->json(['ok' => 1]);
    }
}
