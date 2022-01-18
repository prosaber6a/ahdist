@extends('layouts.app')

@if($operation_type === 1)
    @section('title', 'Edit Purchase')
@else
    @section('title', 'Edit Sale')
@endif


@section('foot')
    <script !src="">
        var units = {!!  json_encode(config('constant.unit'))  !!};
        var products = {!!  json_encode($products)  !!};
        console.log(products);
        $(document).ready(function () {
            $('#date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true
            });

            // select product
            $('#product_id').on('change', function (e) {
                const product_id = parseInt($(this).val());
                products.every((e) => {
                    if (parseInt(e.id) === product_id) {
                        $('.product_unit').html(units[parseInt(e.unit)]);
                        return;
                    }
                })
            });


        });

        //on input bag value
        // $('#bag').on('change', calculateBagWeight());

        // on change receive weight

        document.getElementById('bag').addEventListener('change', calculateBagWeight);
        document.getElementById('receive_weight').addEventListener('change', calculateFinalWeight);
        document.getElementById('rate').addEventListener('change', calculateTotalAmount);
        document.getElementById('truck_fare').addEventListener('change', calculateTotalAmount);
        document.getElementById('labour_value').addEventListener('change', calculateLabourBill);
        // document.getElementsByName('truck_fare_operation').addEventListener('click');
        document.getElementById('truck_fare_operation_add').addEventListener('click', calculateTotalAmount);
        document.getElementById('truck_fare_operation_sub').addEventListener('click', calculateTotalAmount);

        function calculateBagWeight() {
            const bag_weight = parseInt($('#bag').val()) * 0.15;
            $('#bag_weight').val(bag_weight);
            $('[name="bag_weight"]').val(bag_weight);
            calculateFinalWeight();
        }

        function calculateFinalWeight() {
            const final_wight = parseFloat($('#receive_weight').val()) - parseFloat($('#bag_weight').val());
            $('#final_weight').val(final_wight);
            $('[name="final_weight"]').val(final_wight);
            calculateLabourBill();
        }

        function calculateLabourBill() {
            const labour_bill = (parseFloat($('#final_weight').val()) / 1000) * parseInt($('#labour_value').val());
            $('#labour_bill').val(labour_bill);
            $('[name="labour_bill"]').val(labour_bill);
            calculateTotalAmount();
        }

        function calculateTotalAmount() {
            let amount = (parseFloat($('#final_weight').val()) * parseFloat($('#rate').val())) - parseFloat($('#labour_bill').val());
            if(document.getElementById('truck_fare_operation_add').checked) {
                amount += parseFloat($('#truck_fare').val());
            } else {
                amount -= parseFloat($('#truck_fare').val());
            }
            $('#amount').val(amount);
            $('[name="amount"]').val(amount);
        }
    </script>
@endsection

@section('content')

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <form action="@if($operation_type === 1){{ route('update_purchase', $operation->id) }}@else{{ route('update_sale', $operation->id) }}@endif" method="POST">
            @csrf
            @method('PUT')

            <!--Hidden:Start-->
                <input type="hidden" name="bag_weight" value="{{ $operation->bag_weight }}">
                <input type="hidden" name="final_weight" value="{{ $operation->final_weight }}">
                <input type="hidden" name="labour_bill" value="{{ $operation->labour_bill }}">
                <input type="hidden" name="amount" value="{{ $operation->amount }}">
                <!--Hidden:End-->
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
                            <label class="required form-label" for="date">Date</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="date" id="date" class="form-control mb-2" placeholder="Date"
                                   value="{{ date('m/d/Y', strtotime($operation->date)) }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="party_id">Supplier</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-control" name="party_id" id="party_id" data-control="select2"
                                    data-placeholder="Select a supplier">
                                <option></option>
                                @foreach($parties as $party)
                                    <option @if(intval($operation->party_id) ===  $party->id) selected="selected"
                                            @endif value="{{ $party->id }}">{{ $party->name }}
                                        - {{ $party->company }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="w_no">W.No</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="w_no" id="w_no" class="form-control mb-2" placeholder="W.No"
                                   value="{{ $operation->w_no }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="truck_no">Truck No</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="truck_no" id="truck_no" class="form-control mb-2"
                                   placeholder="Truck No"
                                   value="{{ $operation->truck_no }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="product_id">Product</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-control" name="product_id" id="product_id" data-control="select2"
                                    data-placeholder="Select a product">
                                <option></option>
                                @foreach($products as $product)
                                    <option @if(intval($operation->product_id) ===  $product->id) selected="selected"
                                            @endif value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="bag">Bag</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" name="bag" id="bag" class="form-control mb-2" placeholder="Bag"
                                   value="{{ $operation->bag }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="bag">Bag Weight</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" disabled id="bag_weight" class="form-control mb-2"
                                   placeholder="Bag Weight"
                                   value="{{ $operation->bag_weight }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="bag">Send Weight</label>
                            <!--end::Label-->
                            <!--begin::Input-->

                            <div class="input-group mb-5">
                                <input type="text" name="send_weight" id="send_weight" class="form-control"
                                       placeholder="Send Weight"
                                       value="{{ $operation->send_weight }}">
                                <span class="input-group-text product_unit">Unit</span>
                            </div>

                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="bag">Receive Weight</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="input-group mb-5">
                                <input type="text" name="receive_weight" id="receive_weight" class="form-control"
                                       placeholder="Receive Weight"
                                       value="{{ $operation->receive_weight }}">
                                <span class="input-group-text product_unit">Unit</span>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label" for="final_weight">Final Weight</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="input-group mb-5">
                                <input type="text" id="final_weight" class="form-control" disabled
                                       placeholder="Final Weight"
                                       value="{{ $operation->final_weight }}">
                                <span class="input-group-text product_unit">Unit</span>
                            </div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="labour_value">L. Value</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" name="labour_value" id="labour_value" class="form-control mb-2"
                                   placeholder="L.Value"
                                   value="{{ $operation->labour_value }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="labour_bill">L. Bill</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" disabled id="labour_bill" class="form-control mb-2"
                                   placeholder="L.Bill"
                                   value="{{ $operation->labour_bill }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="rate">Rate</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="rate" id="rate" class="form-control mb-2" placeholder="Rate"
                                   value="{{ $operation->rate }}">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="truck_fare">Truck Fare</label>
                            <!--end::Label-->
                            <!--begin::Input-->

                            <div class="input-group w-300px">
                                <div class="input-group-text p-0">
                                    <input class="btn-check" name="truck_fare_operation" id="truck_fare_operation_add"
                                           type="radio" value="1"
                                           @if(intval($operation->truck_fare_operation) == 1) checked
                                           @endif aria-label="Checkbox for following text input">

                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-success d-flex align-items-center"
                                        for="truck_fare_operation_add">+</label>
                                </div>
                                <input type="text" name="truck_fare" id="truck_fare" class="form-control"
                                       placeholder="Truck Fare"
                                       value="{{ $operation->truck_fare }}">
                                <div class="input-group-text p-0">
                                    <input class="btn-check" name="truck_fare_operation" id="truck_fare_operation_sub"
                                           type="radio" value="2"
                                           @if(intval($operation->truck_fare_operation) == 2) checked
                                           @endif aria-label="Checkbox for following text input">

                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-danger d-flex align-items-center"
                                        for="truck_fare_operation_sub">-</label>
                                </div>
                            </div>

                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required form-label" for="amount">Total Amount</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="amount" id="amount" class="form-control mb-2" placeholder="Total Amount"
                                   disabled
                                   value="{{ $operation->amount }}">
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
                                      placeholder="Address">{{ $operation->note }}</textarea>
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->


                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="@if(intval($operation_type) === 1) {{ route('purchases') }} @else {{ route('sales') }} @endif"
                               id="kt_ecommerce_add_product_cancel"
                               class="btn btn-light me-5">Cancel</a>
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


