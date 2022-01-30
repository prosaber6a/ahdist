@extends('layouts.app')
@section('title', 'All Transaction')

@section('head')
    <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/print.css" rel="stylesheet" type="text/css" media="print"/>
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

        $('#account').on('change', table_filter);
        $('#party').on('change', table_filter);


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


        function table_filter() {
            const party_id = $('#party').val();
            const acc_id = $('#account').val();
            const date_from = filter_from ? filter_from : '-';
            const date_to = filter_to ? filter_to : '-';
            if (party_id || acc_id || date_to || date_from) {
                console.log('/api/transaction/filter/' + party_id + '/' + acc_id + '/' + date_from + '/' + date_to)
                $.ajax({
                    url: '/api/transaction/filter/' + party_id + '/' + acc_id + '/' + date_from + '/' + date_to,
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
                                    <td>${e.account}</td>
                                    <td>${e.party}</td>
                                    <td>${e.description}</td>
                                    <td>${e.debit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
                                    <td>${e.credit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
                                    <td>${balance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
                                    <td class="action_cell">${e.action}</td>
                                <tr>
                                `;

                            });
                            $('#datatable tbody').html(tr);
                            $('#total_credit_cell').html(total_credit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                            $('#total_debit_cell').html(total_debit.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                            $('#total_balance_cell').html(balance.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));


                        } else {
                            $('#datatable tbody').html('<tr><th colspan="9" class="text-center bg-danger">No data found</th></tr>');
                            $('#total_credit_cell').html(0);
                            $('#total_debit_cell').html(0);
                            $('#total_balance_cell').html(0);
                        }

                    }
                })
            }
        }

        function handlePrintBtn() {
            const originalContents = document.body.innerHTML;
            let print_content = `<div class="header"><h1>{{ env('app_name') }}</h1><h3>Transaction History</h3></div>`;
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
    size: A4;
    margin: 11mm 17mm 17mm 17mm;
}

@media print {
    footer {
        position: fixed;
        bottom: 0;
    }

    .content-block, p {
        page-break-inside: avoid;
    }

    html, body {
        width: 210mm;
        height: 297mm;
    }
}
</style>
            `;
            myWindow.document.head.innerHTML = myWindow.document.head.innerHTML + print_style;
            myWindow.document.body.innerHTML = print_content;
            myWindow.document.querySelectorAll('.action_cell').forEach(e => e.remove());
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
                        @if($account)
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="form-floating">
                                    <select class="form-select" id="account" aria-label="Select a account">
                                        <option selected value="0">All</option>
                                        @foreach($account as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}, {{ $acc->bank }}
                                                , {{ $acc->branch }}</option>
                                        @endforeach
                                    </select>
                                    <label for="account">Account</label>
                                </div>
                            </div>
                        @endif
                        @if($parties)
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
                        @endif

                    </div>
                </div>
            </div>
            <!--begin::Category-->
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Transactions</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light" onclick="handlePrintBtn()">
                            Print
                        </button>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body" id="printArea">
                    <!--begin::Table-->
                    <table class="table table-row-bordered gy-5 gs-7 border rounded" id="datatable">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="fw-bolder fs-6 text-gray-800 px-7">
                            <th>#</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Party</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                            <th class="w-100px action_cell">Actions</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        @if(isset($transactions) && count($transactions))
                            @php($i = 1)
                            @php($total_debit = 0)
                            @php($total_credit = 0)
                            @php($balance = 0)

                            @foreach($transactions as $transaction)
                                @php($total_debit += $transaction->debit)
                                @php($total_credit += $transaction->credit)
                                @php($balance += ($transaction->credit - $transaction->debit))
                                <!--begin::Table row-->
                                <tr>

                                    <td>{{ $i++ }}</td>
                                    <td> {{ date('d M, Y', strtotime($transaction->date)) }} </td>
                                    <td> @if($transaction->account_id){{ $transaction->account->bank }}@endif</td>
                                    <td> @if($transaction->party_id){{ $transaction->party->name }} @endif</td>
                                    <td> {{ $transaction->description }}
                                        @if($transaction->operation_id != 0 && isset($transaction->operation->type))
                                            <a href="@if(intval($transaction->operation->type) === 1)
                                            {{ route('show_purchase', $transaction->operation_id) }}
                                            @else
                                            {{ route('show_sale', $transaction->operation_id) }}
                                            @endif">#{{ $transaction->operation_id }}</a> @endif</td>

                                    <td> {{ number_format($transaction->debit, 2, ".", ",") }} </td>
                                    <td> {{ number_format($transaction->credit, 2, ".", ",") }} </td>
                                    <td> {{ number_format($balance, 2, ".", ",") }} </td>

                                    <td class="text-end flex-center d-flex gap-2 action_cell">
                                        @if(intval(Auth::user()->user_type) === 1)
                                            <a href="{{ route('edit_transaction', $transaction->id) }}"
                                               class="btn btn-sm btn-icon btn-primary"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <a href="#" onclick="$('#transaction_delete_{{$transaction->id}}').submit()"
                                               class="btn btn-sm btn-icon btn-danger"
                                               data-kt-ecommerce-category-filter="delete_row"><i
                                                    class="bi bi-trash"></i></a>
                                            <form id="transaction_delete_{{$transaction->id}}"
                                                  action="{{ route('delete_transaction', $transaction->id) }}"
                                                  method="post"
                                                  class="deleteForm">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @else - @endif


                                </tr>
                                <!--end::Table row-->
                            @endforeach
                        @else
                            <tr>
                                <th colspan="9" class="text-center bg-danger">No data found</th>
                            </tr>
                        @endif

                        </tbody>
                        <!--end::Table body-->
                        <tfoot>
                        <tr class="bg-secondary">
                            <th colspan="5" class="text-end bold">Total</th>
                            <td id="total_debit_cell">@if(isset($total_debit)){{ number_format($total_debit, 2, ".", ",") }}@else
                                    0 @endif</td>
                            <td id="total_credit_cell">@if(isset($total_credit)){{ number_format($total_credit, 2, ".", ",") }}@else
                                    0 @endif</td>
                            <td id="total_balance_cell">@if(isset($balance)){{ number_format($balance, 2, ".", ",") }}@else
                                    0 @endif</td>
                            <td class="action_cell"></td>

                        </tr>
                        </tfoot>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Container-->
    </div>

@endsection


