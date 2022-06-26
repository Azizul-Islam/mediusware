@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
    </div>
    <section>
        <form action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Product Name</label>
                                <input type="text" name="title" value="{{ old('title', $product->title) }}"
                                    placeholder="Product Name" class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <strong class="invalid-feedback">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Product SKU</label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                    placeholder="Product sku" class="form-control @error('sku') is-invalid @enderror">
                                @error('sku')
                                    <strong class="invalid-feedback">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea id="" cols="30" name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <strong class="invalid-feedback">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Media</h6>
                        </div>
                        <div class="card-body border">
                            <input type="file" multiple name="photos[]" id="">
                            <div class="mt-2">
                                @foreach ($product->images as $image)
                                    <img src="{{ asset('backend/products/' . $image->file_path) }}" width="100"
                                        alt="">
                                    <a onclick="return confirm('Are you sure?')"
                                        href="{{ route('product-photo.delete', $image->id) }}"
                                        class="btn btn-danger btn-sm">Delete</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Variants</h6>
                        </div>
                        <div class="add_item">
                            <div class="card-body">
                                @foreach ($product->productVariants as $pvariant)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Option</label>
                                                <select name="variant_id[]" class="form-control">
                                                    @foreach ($variants as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == $pvariant->variant_id ? 'selected' : '' }}>{{ $item->title }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                @if($loop->first)
                                                <label class="float-right text-primary" style="cursor: pointer;">Variant</label>
                                                @else
                                                <a href="{{ route('product-varian.delete',$pvariant->id) }}" class="float-right text-danger" onclick="return confirm('Are you sure?')">Remove</a>
                                                @endif
                                                <input class="form-control" name="variant[]" value="{{ $pvariant->variant }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="card-footer">
                                    <a class="btn btn-primary addmoreBtn">Add another option</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-header text-uppercase">Preview</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>Variant</td>
                                            <td>Price</td>
                                            <td>Stock</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control" v-model="variant_price.price">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" v-model="variant_price.stock">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-lg btn-primary">Update</button>
            <button type="button" class="btn btn-secondary btn-lg">Cancel</button>
        </form>
    </section>
    <div style="visibility: hidden">
        <div class="extra_item_add" id="extra_item_add">
            <div class="extra_item_delete" id="extra_item_delete">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Option</label>
                            <select name="variant_id[]" class="form-control">
                                @foreach ($variants as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="float-right text-primary removeBtn" style="cursor: pointer;">Remove</label>
                            <input class="form-control" name="variant[]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            var counter = 0;
            $(document).on('click', '.addmoreBtn', function() {
                var total_div = $('#extra_item_add').html();
                $(this).closest('.add_item').append(total_div);
                counter++;
            });

            $(document).on('click', '.removeBtn', function() {
                $(this).closest('#extra_item_delete').remove();
                counter -= 1;
            });
        });
    </script>
@endsection
