@extends('layouts.app')
@if($transaction->type === 1)
    @section('title', 'Edit Deposit')
@elseif($transaction->type === 2)
    @section('title', 'Edit Expense')
@else
    @section('title', 'Edit Transfer')
@endif


@section('foot')
    <script>
        $(document).ready(function () {
            $('#date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true
            });
        })
    </script>
@endsection

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <form action="{{ route('update_transaction', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="type" value="{{ $transaction->type }}">

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
                            <label class="form-label" for="date">Date</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" name="date" id="date" class="form-control mb-2" placeholder="Date"
                                   value="{{ date('m/d/Y', strtotime($transaction->date)) }}">
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="account_id">@if(intval($transaction->type) !== 3) Account @else
                                    From @endif</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-control" name="account_id" id="account_id" data-control="select2"
                                    data-placeholder="Select account">
                                <option></option>
                                @foreach($accounts as $account)
                                    <option @if(intval($transaction->account_id) ===  $account->id) selected="selected"
                                            @endif value="{{ $account->id }}">{{ $account->name }}
                                        - {{ $account->bank }}, {{ $account->branch }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>

                    @if($transaction->type !== 3)
                        <!--end::Input group-->
                        @isset($parties)
                            <!--begin::Input group-->
                                <div class="mb-10 fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label" for="party_id">@if(intval($transaction->type) === 1)
                                            Payer @elseif (intval($transaction->type) === 2) Payee @else Party @endif</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-control" name="party_id" id="party_id" data-control="select2"
                                            data-placeholder="Select party">
                                        <option></option>
                                        @foreach($parties as $party)
                                            <option @if(intval(old('party_id')) ===  $party->id) selected="selected"
                                                    @endif value="{{ $party->id }}">{{ $party->name }}
                                                - {{ $party->company }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                        @endisset



                        <!--begin::Input group-->
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label class="form-label" for="description">Description</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <textarea type="text" name="description" id="description" class="form-control mb-2"
                                          placeholder="Description">{{ $transaction->description }}</textarea>
                                <!--end::Editor-->
                            </div>
                            <!--end::Input group-->
                    @else
                        <!--begin::Input group-->
                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                <!--begin::Label-->
                                <label class="required form-label" for="to_acc_id">To</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-control" name="to_acc_id" id="to_acc_id" data-control="select2"
                                        data-placeholder="Select account">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        <option @if(intval(old('to_acc_id')) ===  $account->id) selected="selected"
                                                @endif value="{{ $account->id }}">{{ $account->name }}
                                            - {{ $account->bank }}, {{ $account->branch }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                    @endif

                    <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="amount">Amount</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" name="amount" id="amount" class="form-control mb-2" placeholder="Amount"
                                   value="{{ $transaction->amount }}">
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->

                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('transactions') }}" id="kt_ecommerce_add_product_cancel"
                               class="btn btn-light me-5">Cancel</a>
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


