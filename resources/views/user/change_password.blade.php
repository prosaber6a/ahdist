@extends('layouts.app')
@section('title', 'Change Password')

@section('content')

<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <form action="{{ route('update_current_user_password') }}" method="POST">
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
                        <label class="required form-label" for="current_password">Current Password</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password" required name="current_password" id="current_password" class="form-control mb-2" placeholder="Current Password"
                               value="">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="required form-label" for="password">New Password</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password" required name="password" id="password" class="form-control mb-2" placeholder="New Password"
                               value="" autocomplete="new-password">
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                        <!--begin::Label-->
                        <label class="required form-label" for="password_confirmation">Re-type Password</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="password" required name="password_confirmation" id="password_confirmation" class="form-control mb-2" placeholder="Re-type new Password"
                               value="" autocomplete="new-password">
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


