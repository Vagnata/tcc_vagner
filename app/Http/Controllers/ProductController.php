<?php

namespace App\Http\Controllers;

use App\Domain\Services\ProductService;
use App\Enums\UnityTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    private $productService;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'delete']);
        $this->productService = new ProductService();
    }

    public function list(Request $request)
    {
        if (Auth::check()) {
            $products = $this->productService->findBy($request->all());

            return view('product.list')->with(['products' => $products, 'buscar' => $request->get('buscar', '')]);
        }

        return view('user.login')->with('message', trans('message.login.unauthorized'));
    }

    public function showForm(Request $request)
    {
        if (Auth::check()) {
            $unityTypes = UnityTypeEnum::getToForm();
            $return     = [
                'unityTypes' => $unityTypes
            ];

            if ($request->route('id')) {
                $product           = $this->productService->findById($request->route('id'));
                $return['product'] = $product->toArray();
            }

            return view('product.form')->with($return);
        }

        return view('user.login')->with('message', trans('message.login.unauthorized'));

    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            if ($request->has('id')) {
                $product = $this->productService->findById((int)$request->get('id'));
                $this->productService->update($product, $request->all());
            } else {
                $this->productService->create($request->all());
            }


            $products = $this->productService->findBy([]);

            return view('product.list')->with('products', $products);
        }

        return view('user.login')->with('message', trans('message.login.unauthorized'));
    }

    public function delete($id)
    {
        try {
            $product = $this->productService->findById($id);
            $product = $this->productService->inactive($product);

            $this->response = [
                'id' => $product->id
            ];
        } catch (NotFoundHttpException $exception) {
            return Response::json(['data' => 'Produto não encontrado ou inativo'], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponse::HTTP_OK);
    }
}
