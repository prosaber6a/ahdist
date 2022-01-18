@extends('layouts.app')
@section('title', 'Add New Account')

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <form action="{{ route('store_account') }}" method="POST">
                @csrf

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
                            <label class="required form-label" for="bank">Bank Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="bank" id="bank" class="form-control mb-2" placeholder="Bank name"
                                   value="{{ old('bank') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="branch">Branch</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="branch" id="branch" class="form-control mb-2"
                                   placeholder="Branch" value="{{ old('branch') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="name">Acc. Holder Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="name" id="name" class="form-control mb-2"
                                   placeholder="Acc. Holder Name" value="{{ old('name') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="acc_no">Acc. No</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="acc_no" id="acc_no" class="form-control mb-2"
                                   placeholder="Acc. No" value="{{ old('acc_no') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="initial_balance">Initial Balance</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="initial_balance" id="initial_balance" class="form-control mb-2"
                                   placeholder="Initial Balance" value="{{ old('initial_balance') }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="note">Note</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <textarea class="form-control" name="note" id="note" cols="30" rows="5"
                                      placeholder="Address">{{ old('note') }}</textarea>
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->




                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Acc. Status
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                   title="Select a party status that will be applied to this party"></i></label>
                            <!--End::Label-->
                            <!--begin::Row-->
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-1 row-cols-xl-2 g-9"
                                 data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Option-->
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-success @if(old('status', 1) == 1) active @endif d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="status" value="1"
                                                   @if(old('status', 1) == 1) checked="checked" @endif/>
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
                                        class="btn btn-outline btn-outline-dashed btn-outline-danger @if(old('status', 404) == 0) active @endif d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="status" value="0" @if(old('status', 404) == 0) checked="checked" @endif />
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
                            <a href="{{ route('accounts') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                                <span class="indicator-label">Save</span>
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


