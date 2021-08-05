<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::orderBy('name','asc')->paginate(10);
        return ProductResource::collection($products);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make(
            $request->all(),
            [
                'name'    => 'required',
                'category_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        DB::beginTransaction();
        try {
        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->subcategory_id;
        $product->save();
         if (!empty($request->images)) {
            $pro_image = $request->images;
            foreach ($pro_image as $key => $value) {
                $file=base64_decode($value['path']);
                $file_name = substr(md5(uniqid(rand(1, 6))) . microtime(true), 0, 15) . '.' ."jpg";
                file_put_contents(public_path().'/uploads/'.$file_name, $file);
                $product->media()->create([
                    'name' =>  $file_name,
                ]);
            }
        }
        DB::commit();
        return response(new ProductResource($product), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

     /**
     * Display the collection of resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function searchProduct(Request $request)
    {
       $keyword= $request->keyword;

       $results=Product::where('name', 'like', '%' .$keyword.'%')->get();

       return ProductResource::collection($results);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
    
        if ($request->has('name')) {
            $product->name=$request->name;          
        }
        if ($request->has('category_id')) {
            $product->category_id=$request->category_id;               
        }
        if ($request->has('subcategory_id')) {
            $product->sub_category_id=$request->subcategory_id;
        }

        $product->save();

        return response()->json(["success"=>[
            "message"=>"Product Updated Successfully",
            "data"=>new ProductResource($product)
        ]],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product){
            if(count($product->media)>0) {
               
                foreach($product->media as $media)
                {
                    $path = public_path() . "/uploads/" . $media->name;
                    if($path)
                    {
                        unlink($path);
                    }
                     $product->media()->delete();
                }
            }
            $product->delete();
            return response('deleted', Response::HTTP_NO_CONTENT);
        }else{
            return response('No product found', Response::HTTP_BAD_REQUEST);
        }
    }
}
