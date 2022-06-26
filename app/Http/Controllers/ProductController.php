<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $totalProducts = Product::count();
        $productVariants = ProductVariant::groupBy('variant')->get();
        $title = '';
        $date = '';
        if($request->has('search')){
            $products = Product::where('title','LIKE','%'.$request->title.'%')->orWhere('created_at',date('Y-m-d',strtotime($request->date)))->latest()->paginate(2);
            $title = $request->title;
            $date = $request->date;
        }
        else{
            $products = Product::with(['productVariants','productVariantPrices'])->latest()->paginate(2);
        }
        return view('products.index',compact('products','totalProducts','productVariants','title','date'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|unique:products,sku,'
        ]);
        
        $product = Product::create($data);
        if ($request->has('photos') && !blank($request->photos)) {
            foreach ($request->photos as $photo) {
                $name_gen = rand() . "." . $photo->getClientOriginalExtension();
                if(!is_dir(public_path('products'))){
                    mkdir(public_path('products'));
                }
                $photo->move(public_path('backend/products'),$name_gen);
                ProductImage::create([
                    'product_id' => $product->id,
                    'file_path' => $name_gen,
                ]);
            }
        }
        if($request->has('variant_id')){
            foreach($request->variant_id as $i=>$id){
                $productVariant = new ProductVariant();
                $productVariant->variant = $request->variant[$i];
                $productVariant->variant_id = $id;
                $productVariant->product_id = $product->id;
                $productVariant->save();
            }
        }
        return back()->with('success','Product created success');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|unique:products,sku,'.$product->id,
        ]);
        
        $product->update($data);
        if ($request->has('photos') && !blank($request->photos)) {
            foreach ($request->photos as $photo) {
                $name_gen = rand() . "." . $photo->getClientOriginalExtension();
                if(!is_dir(public_path('products'))){
                    mkdir(public_path('products'));
                }
                $photo->move(public_path('backend/products'),$name_gen);
                ProductImage::create([
                    'product_id' => $product->id,
                    'file_path' => $name_gen,
                ]);
            }
        }
        if($request->has('variant_id')){
            $product->productVariants()->delete();
            foreach($request->variant_id as $i=>$id){
                $productVariant = new ProductVariant();
                $productVariant->variant = $request->variant[$i];
                $productVariant->variant_id = $id;
                $productVariant->product_id = $product->id;
                $productVariant->save();
            }
        }
        return back()->with('success','Product updated success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function productPhotoDelete($id)
    {
        $image = ProductImage::findOrFail($id);
        $path = public_path('backend/products/'.$image->file_path);
        if(file_exists($path)){
            unlink($path);
        }
        $image->delete();
        return back()->with('success','Product image deleted success');
    }

    public function productVarianDelete($id)
    {
        ProductVariant::where('id',$id)->delete();
        return back()->with('success','Product variant deleted success');
    }

   
}
