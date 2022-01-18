@extends('layouts.app')
@if(intval($operation_type) === 1)
    @section('title', 'All Purchase')
@else
    @section('title', 'All Sale')
@endif

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
                ">",
            responsive: true
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


        function handleShowBtn(e) {
            const myModal = new bootstrap.Modal(document.getElementById('modal'))
            myModal.show();
        }



    </script>
@endsection

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Category-->
            <div class="card card-flush">

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table nowrap table-bordered gy-5 gs-7 border rounded" id="datatable">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="fw-bolder fs-6 text-gray-800 px-7">
                            <th>#</th>
                            <th>Date</th>
                            <th>Party</th>
                            <th>W. No.</th>
                            <th>Product</th>
                            <th>Truck No</th>
                            <th>Bag</th>
                            <th>Bag Weight</th>
                            <th>Send Weight</th>
                            <th>Receive Weight</th>
                            <th>Final Weight</th>
                            <th>L. Value</th>
                            <th>L. Bill</th>
                            <th>Rate</th>
                            <th>Truck Fare</th>
                            <th>Total Amount</th>
                            <th>Note</th>
                            <th class="w-100px">Actions</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        @isset($operations)
                            @php($i = 1)
                            @foreach($operations as $operation)
                                <!--begin::Table row-->
                                <tr>

                                    <td>{{ $i++ }}</td>
                                    <td> {{ date('d-M-Y', strtotime($operation->date)) }}</td>
                                    <td>{{ $operation->party->name }} - {{ $operation->party->company }}</td>
                                    <td>{{ $operation->w_no }}</td>
                                    <td>{{ $operation->product->name }}</td>
                                    <td>{{ $operation->truck_no }}</td>
                                    <td>{{ $operation->bag }}</td>
                                    <td>{{ $operation->bag_weight }}</td>
                                    <td>{{ $operation->send_weight }}</td>
                                    <td>{{ $operation->receive_weight }}</td>
                                    <td>{{ $operation->receive_weight - $operation->bag_weight }}</td>
                                    <td>{{ $operation->labour_value }}</td>
                                    <td>{{ $operation->labour_bill }}</td>
                                    <td>{{ $operation->rate }}</td>
                                    <td>@if(intval($operation->truck_fare_operation) === 1) (+) @else (-) @endif {{ $operation->truck_fare }}</td>
                                    <td>{{ $operation->amount }}</td>

                                    <td>
                                        {{ $operation->note }}
                                    </td>
                                    <td class="text-end">
                                        <a href="@if($operation_type === 1){{ route('edit_purchase', $operation->id) }}@else{{ route('edit_sale', $operation->id) }}@endif"
                                           class="btn btn-icon btn-primary btn-sm"><i
                                                class="bi bi-pencil-square"></i></a>
                                        <a href="#" onclick="$('#delete_operation_{{$operation->id}}').submit()" class="btn btn-icon btn-danger btn-sm"
                                           data-kt-ecommerce-category-filter="delete_row"><i
                                                class="bi bi-trash"></i></a>
                                        <form id="delete_operation_{{$operation->id}}" action="@if($operation_type === 1){{ route('delete_purchase', $operation->id) }}@else{{ route('delete_sale', $operation->id) }}@endif"
                                              method="post"
                                              class="deleteForm">
                                            @csrf
                                            @method('delete')
                                        </form>


                                        {{--<a href="#" class="btn btn-sm btn-light btn-active-light-primary"
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
                                            <!--end::Svg Icon-->
                                        </a>
                                        <!--begin::Menu-->
                                        <div
                                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <button onclick="handleShowBtn({{$operation->id}})" class="btn btn-icon btn-success w-100"><i
                                                        class="bi bi-eye"></i></button>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="@if($operation_type === 1){{ route('edit_purchase', $operation->id) }}@else{{ route('edit_sale', $operation->id) }}@endif"
                                                   class="btn btn-icon btn-primary w-100"><i
                                                        class="bi bi-pencil-square"></i></a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <form action="@if($operation_type === 1){{ route('delete_purchase', $operation->id) }}@else{{ route('delete_sale', $operation->id) }}@endif"
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
                                        <!--end::Menu-->--}}
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
            <!--end::Category-->
        </div>
        <!--end::Container-->
    </div>



    <!-- Modal:start -->
    <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        @if($operation_type === 1)
                            Purchase
                        @else
                            Sale
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td>01 Jan, 2022</td>
                        </tr>
                        <tr>
                            <td>Party Name</td>
                            <td>:</td>
                            <td>Saber Hossen Rabbani</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal:end -->

@endsection


