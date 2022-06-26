@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}"  class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" value="{{ $title }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach ($productVariants as $item)
                           
                            <option value="{{ $item->variant }}">{{ $item->variant }}</option>
                           
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" name="search"  class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($products as $i=>$product)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $product->title }} <br> Created at : {{ date('d-M-Y',strtotime($product->created_at)) }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant{{ $i }}">

                                <dt class="col-sm-3 pb-0">
                                    @foreach ($product->productVariants as $item)
                                        {{ $item->variant }}/
                                    @endforeach
                                    {{-- SM/ Red/ V-Nick --}}
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        @foreach ($product->productVariantPrices as $vprice)
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($vprice->price,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($vprice->stock,2) }}</dd>
                                        @endforeach
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant{{ $i }}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="5">No Data available in this table</td></tr>
                    @endforelse
                   

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    @if(!blank($products))
                    <p>Showing 1 to 2 out of {{ $totalProducts }}</p>
                    @endif
                </div>
                <div class="col-md-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
