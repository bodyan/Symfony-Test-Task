<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ProductsService;
use Firebase\JWT\JWT;

/**
 * @Route("/api")
 */
class ProductsController extends Controller
{
    /**
     * @Route(path="/show", methods={"GET"}, name="api_homepage")
     *
     */
    public function index()
    {

        return new Response("Hello User");
    }

    /**
     * @Route(path="/products", methods={"POST"}, name="products")
     *
     */
    public function getProducts(Request $request, ProductsService $productsService)
    {
        $secret = "kjVwrM6BozliauiKKinpJ1X5oo1aeeLgHX6pd9bbUb36cmzWl9m2zzHMWjNLPzTc";

        $jwt_token = explode( ' ', $request->headers->all()["authorization"][0] )[1];
        $decoded = JWT::decode($jwt_token, $secret, ['HS256']);

        $repository = $productsService->handleParameters((array)$decoded);
        $result = $this->get('jms_serializer')->serialize((array)$repository, 'json');

        $response = new Response();
        $response->setContent($result);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}

