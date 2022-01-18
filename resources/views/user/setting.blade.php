@extends('layouts.app')
@section('title', 'User Setting')

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <form action="{{ route('update_current_user_setting') }}" method="POST">
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
                        <label class="required form-label" for="name">Name</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" required name="name" id="name" class="form-control mb-2" placeholder="Name"
                               value="{{ Auth::user()->name }}">
                        <!--end::Input-->

                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="required form-label" for="email">Email</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" required name="email" id="email" class="form-control mb-2"
                               placeholder="Email" value="{{ Auth::user()->email }}">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->


                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('dashboard') }}" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancel</a>
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


