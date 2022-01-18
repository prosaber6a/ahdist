@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <form action="{{ route('update_product', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>@yield('title')</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="name">Product Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Product name"
                                   value="{{ $product->name }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="unit">Unit</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-control" name="unit" id="unit" data-control="select2" data-placeholder="Select unit">
                                <option></option>
                                @foreach(config('constant.unit') as $key => $value)
                                    <option @if(intval($product->unit) ===  $key) selected="selected" @endif value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="size">Size</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" name="size" id="size" class="form-control mb-2" placeholder="Product Size"
                                   value="{{ $product->size }}">
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Product Status
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                   title="Select a product that will be applied to this product"></i></label>
                            <!--End::Label-->
                            <!--begin::Row-->
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-1 row-cols-xl-2 g-9"
                                 data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Option-->
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-success @if(intval($product->status) === 1) active @endif d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="status" value="1"
                                                   @if(intval($product->status) === 1) checked="checked" @endif/>
                                        </span>
                                        <!--end::Radio-->
                                        <!--begin::Info-->
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder text-gray-800 d-block">Active</span>
                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Option-->
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-danger @if(intval($product->status) === 0) active @endif d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="status" value="0" @if(intval($product->status) === 0) checked="checked" @endif />
                                        </span>
                                        <!--end::Radio-->
                                        <!--begin::Info-->
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder text-gray-800 d-block">Inactive</span>
                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Input group-->

                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('products') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Save Changes</span>
                            </button>
                            <!--end::Button-->
                        </div>


                    </div>
                </div>





            </form>
        </div>
        <!--end::Container-->
    </div>

@endsection


