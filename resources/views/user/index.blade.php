@extends('layouts.app')
@section('title', 'Users')

@section('head')
    <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('foot')
    <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>


        $("#datatable").DataTable({
            "language": {
                "lengthMenu": "Show _MENU_",
            },
            "dom":
                "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });

        $('.deleteForm').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                text: "Are you sure to delete?",
                icon: "question",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Yes, I am",
                cancelButtonText: 'Nope, cancel it',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });

    </script>
@endsection

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <!--begin::Form-->
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if(isset($edit_user))
                                    Edit User
                                @else
                                    Add New User
                                @endif

                            </h3>
                        </div>
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <form
                                action="@if(isset($edit_user)){{ route('user_update', $edit_user->id) }}@else{{ route('user_store') }}@endif"
                                method="post">
                            @csrf
                                @method('put')
                            <!--begin::Input group-->
                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="required form-label" for="name">Name</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" required name="name" id="name" class="form-control mb-2"
                                           placeholder="Name"
                                           value="@if(isset($edit_user)){{ $edit_user->name }}@else{{ old('name') }}@endif">
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
                                           placeholder="Email"
                                           value="@if(isset($edit_user)){{ $edit_user->email }}@else{{ old('email') }}@endif">
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="required form-label" for="password">Password</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" @if(!isset($edit_user)) required @endif name="password" id="password" class="form-control mb-2"
                                           placeholder="Create a password" value="{{ old('password') }}">
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="required form-label" for="type">Type</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <div>
                                        <label><input type="radio"
                                                      @if(isset($edit_user) && intval($edit_user->user_type) === 1)
                                                      checked
                                                      @endif name="user_type" value="1"> Admin</label>
                                        <label><input type="radio"
                                                      @if(isset($edit_user)) @if(intval($edit_user->user_type) === 2) checked
                                                      @endif @else checked
                                                      @endif name="user_type" value="2"> Employee</label>
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Form-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!--begin::Table-->
                    <div class="card card-flush">

                        <div class="card-header">
                            <h3 class="card-title">All Users</h3>
                        </div>
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table table-row-bordered gy-5 gs-7 border rounded" id="datatable">
                                <!--begin::Table head-->
                                <thead>
                                <!--begin::Table row-->
                                <tr class="fw-bolder fs-6 text-gray-800 px-7">
                                    <th>
                                        #
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                                <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                @isset($users)
                                    @php($i = 1)
                                    @foreach($users as $user)
                                        <!--begin::Table row-->
                                        <tr>

                                            <td>{{ $i++ }}</td>
                                            <td> {{ $user->name }}</td>
                                            <td> {{ $user->email }}</td>

                                            <td>
                                                @if(intval($user->user_type) === 1)
                                                    <div class="badge badge-light-success">Admin</div>
                                                @else
                                                    <div class="badge badge-light-primary">Employee</div>
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                    <span class="svg-icon svg-icon-5 m-0">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none">
																<path
                                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                    fill="black"/>
															</svg>
														</span>
                                                    <!--end::Svg Icon--></a>
                                                <!--begin::Menu-->
                                                <div
                                                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('user_edit', $user->id) }}"
                                                           class="btn btn-icon btn-primary w-100"><i
                                                                class="bi bi-pencil-square"></i></a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('delete_party', $user->id) }}"
                                                              method="post"
                                                              class="deleteForm">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-icon btn-danger w-100"
                                                                    data-kt-ecommerce-category-filter="delete_row"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </form>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                            <!--end::Action=-->
                                        </tr>
                                        <!--end::Table row-->
                                    @endforeach
                                @endisset

                                </tbody>
                                <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Table-->
                </div>
            </div>

        </div>
        <!--end::Container-->
    </div>

@endsection


