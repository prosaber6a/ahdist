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


        let filter_from = "";
        let filter_to = "";


        $("#daterange").daterangepicker({
            timePicker: false,
            startDate: moment().startOf("hour"),
            endDate: moment().startOf("hour").add(32, "hour"),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: "YYYY-MM-DD"
            }
        }, function (start, end, label) {
            filter_from = start.format('YYYY-MM-DD');
            filter_to = end.format('YYYY-MM-DD');
            table_filter();

        });

        $('#party').on('change', table_filter);

        function table_filter() {
            const party_id = $('#party').val();
            const date_from = filter_from ? filter_from : '-';
            const date_to = filter_to ? filter_to : '-';
            if (party_id || acc_id || date_to || date_from) {
                console.log('/api/operation/filter/{{ $operation_type }}/' + party_id + '/' + date_from + '/' + date_to);
                $.ajax({
                    url: '/api/operation/filter/{{ $operation_type }}/' + party_id + '/' + date_from + '/' + date_to,
                    method: 'GET',
                    success: function (response) {
                        response = JSON.parse(response)
                        if (response.data.length) {

                            let tr = "";
                            let total_debit = 0;
                            let total_credit = 0;
                            let balance = 0;
                            response.data.forEach((e) => {
                                total_credit += parseFloat(e.credit);
                                total_debit += parseFloat(e.debit);
                                balance += (e.credit - e.debit);
                                tr += `
                                <tr>
                                    <td>${e.sl}</td>
                                    <td>${e.date}</td>
                                    <td>${e.party}</td>
                                    <td class="w_no_cell">${e.w_no}</td>
                                    <td>${e.product}</td>
                                    <td class="truck_no_cell">${e.truck_no}</td>
                                    <td>${e.bag}</td>
                                    <td>${e.bag_weight}</td>
                                    <td>${e.send_weight}</td>
                                    <td>${e.receive_weight}</td>
                                    <td>${e.final_weight}</td>
                                    <td class="l_value_cell">${e.labour_value}</td>
                                    <td>${e.labour_bill}</td>
                                    <td>${e.rate}</td>
                                    <td>${e.truck_fare_operation} ${e.truck_fare}</td>
                                    <td>${e.amount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
                                    <td class="note_cell">${e.note}</td>
                                    <td class="action_cell">${e.action}</td>
                                <tr>
                                `;

                            });
                            $('#datatable tbody').html(tr);


                        } else {
                            $('#datatable tbody').html('<tr><th colspan="18" class="text-center bg-danger">No data found</th></tr>');

                        }

                    }
                })
            }
        }


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


        function handlePrintBtn() {
            // $("#datatable").DataTable().destroy();
            const originalContents = document.body.innerHTML;
            let print_content = `<div class="header"><h1>{{ env('app_name') }}</h1><h3>{{ (intval($operation_type) === 1) ? "Purchase" : "Sale" }} History</h3></div>`;
            print_content += document.querySelector('#printArea').innerHTML;


            print_content += "<footer>Pro Coder || https://procoder.ca</footer>";


            myWindow = window.open('', '', 'width=800,height=700');
            let print_style = `
<style>
* {
font-family: Sans-serif;
}
.header {
text-align: center;
margin-bottom: 10px;
}

table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

table td, table th {
    border: 1px solid #ddd;
    padding: 8px;
}

table tr:nth-child(even){background-color: #f2f2f2;}

table tr:hover {background-color: #ddd;}

table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
}


footer {
    font-size: 12px;
    text-align: center;
}

@page {
    size: A4 landscape;
}

@media print {
    footer {
        position: fixed;
        bottom: 0;
    }

    .content-block, p {
        page-break-inside: avoid;
    }


}
</style>
            `;
            myWindow.document.head.innerHTML = myWindow.document.head.innerHTML + print_style;
            myWindow.document.body.innerHTML = print_content;
            myWindow.document.querySelectorAll('.action_cell').forEach(e => e.remove());
            myWindow.document.querySelectorAll('.truck_no_cell').forEach(e => e.remove());
            myWindow.document.querySelectorAll('.w_no_cell').forEach(e => e.remove());
            myWindow.document.querySelectorAll('.note_cell').forEach(e => e.remove());
            myWindow.document.querySelectorAll('.l_value_cell').forEach(e => e.remove());
            myWindow.focus();
            myWindow.print(); //DOES NOT WORK

            // document.body.innerHTML = originalContents;
        }


    </script>
@endsection

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            <div class="card card-flush mb-10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-7">
                                <input type="text" class="form-control" id="daterange" placeholder="Date Range"/>
                                <label for="daterange">Date Range</label>
                            </div>
                        </div>
                        @isset($parties)
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="form-floating">
                                    <select class="form-select" id="party" aria-label="Select a party">
                                        <option selected value="0">All</option>
                                        @foreach($parties as $party)
                                            <option value="{{ $party->id }}">{{ $party->name }}
                                                - {{ $party->company }}</option>
                                        @endforeach
                                    </select>
                                    <label for="party">Party</label>
                                </div>
                            </div>
                        @endisset

                    </div>
                </div>
            </div>

            <!--begin::Category-->
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">
                        @if(intval($operation_type) === 1)
                            Purchases
                        @else
                            Sales
                        @endif
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light" onclick="handlePrintBtn()">
                            Print
                        </button>
                    </div>
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-0 overflow-scroll" id="printArea">
                    <!--begin::Table-->
                    <table
                        class="table table-responsive table-striped table-hover table-bordered gy-5 gs-7 border rounded"
                        id="datatable">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="fw-bolder fs-6 text-gray-800 px-7">
                            <th>#</th>
                            <th style="min-width: 101px;">Date</th>
                            <th>Party</th>
                            <th class="w_no_cell">W. No.</th>
                            <th>Product</th>
                            <th class="truck_no_cell">Truck No</th>
                            <th>Bag</th>
                            <th>Bag Weight</th>
                            <th>Send Weight</th>
                            <th>Receive Weight</th>
                            <th>Final Weight</th>
                            <th class="l_value_cell">L. Value</th>
                            <th>L. Bill</th>
                            <th>Rate</th>
                            <th>Truck Fare</th>
                            <th>Total Amount</th>
                            <th class="note_cell">Note</th>
                            <th class="w-100px action_cell">Actions</th>
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
                                    <td class="w_no_cell">{{ $operation->w_no }}</td>
                                    <td>{{ $operation->product->name }}</td>
                                    <td class="truck_no_cell">{{ $operation->truck_no }}</td>
                                    <td>{{ $operation->bag }}</td>
                                    <td>{{ $operation->bag_weight }}</td>
                                    <td>{{ $operation->send_weight }}</td>
                                    <td>{{ $operation->receive_weight }}</td>
                                    <td>{{ $operation->receive_weight - $operation->bag_weight }}</td>
                                    <td class="l_value_cell">{{ $operation->labour_value }}</td>
                                    <td>{{ $operation->labour_bill }}</td>
                                    <td>{{ $operation->rate }}</td>
                                    <td>@if(intval($operation->truck_fare_operation) === 1)
                                            (+) @else (-) @endif {{ $operation->truck_fare }}</td>
                                    <td>{{ $operation->amount }}</td>

                                    <td class="note_cell">
                                        {{ $operation->note }}
                                    </td>
                                    <td class="text-end action_cell">
                                        @if(intval(Auth::user()->user_type) === 1)
                                            <a href="@if($operation_type === 1){{ route('edit_purchase', $operation->id) }}@else{{ route('edit_sale', $operation->id) }}@endif"
                                               class="btn btn-icon btn-primary btn-sm"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <a href="#" onclick="$('#delete_operation_{{$operation->id}}').submit()"
                                               class="btn btn-icon btn-danger btn-sm"
                                               data-kt-ecommerce-category-filter="delete_row"><i
                                                    class="bi bi-trash"></i></a>
                                            <form id="delete_operation_{{$operation->id}}" class="d-inline"
                                                  action="@if($operation_type === 1){{ route('delete_purchase', $operation->id) }}@else{{ route('delete_sale', $operation->id) }}@endif"
                                                  method="post"
                                                  class="deleteForm">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @else - @endif

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
    <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
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


