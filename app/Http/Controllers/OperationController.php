<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Party;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;


class OperationController extends Controller
{
    public $operation_type;

    function __construct()
    {
        $this->operation_type = 2;
        if (\Illuminate\Support\Facades\Request::is('purchase*')) {
            $this->operation_type = 1;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->operation_type === 1) {
            //purchase
            $operation = Operation::where('type', 1)
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            //sale
            $operation = Operation::where('type', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('operation.index', ['operation_type' => $this->operation_type, 'operations' => $operation]);
    }

    /**
     * Return required other table data for operation
     */

    private function other_table_data()
    {
        $data = [];
        if ($this->operation_type === 1) {
            //purchase
            $data['party'] = Party::where('type', 1)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        } else {
            //sale
            $data['party'] = Party::where('type', 2)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        }

        $data['product'] = Product::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();


        return $data;

    }


    /**
     * Validate operation input
     */
    private function validation(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'party_id' => 'required|integer',
            'w_no' => 'nullable|string|max:60',
            'truck_no' => 'nullable|string|max:60',
            'product_id' => 'required|integer',
            'bag' => 'required|integer',
            'bag_weight' => 'required|numeric',
            'send_weight' => 'required|numeric',
            'receive_weight' => 'required|numeric',
            'final_weight' => 'required|numeric',
            'labour_value' => 'required|integer',
            'labour_bill' => 'required|numeric',
            'rate' => 'required|numeric',
            'truck_fare_operation' => 'required|integer|max:2',
            'truck_fare' => 'required|numeric',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->other_table_data();


        return view('operation.create', ['operation_type' => $this->operation_type, 'parties' => $data['party'], 'products' => $data['product']]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //validation
        $this->validation($request);

        $query = Operation::create([
            'date' => date('Y-m-d', strtotime($request->date)),
            'party_id' => $request->party_id,
            'w_no' => $request->w_no,
            'truck_no' => $request->truck_no,
            'product_id' => $request->product_id,
            'bag' => $request->bag,
            'bag_weight' => $request->bag_weight,
            'send_weight' => $request->send_weight,
            'receive_weight' => $request->receive_weight,
            'final_weight' => $request->final_weight,
            'labour_value' => $request->labour_value,
            'labour_bill' => $request->labour_bill,
            'rate' => $request->rate,
            'truck_fare_operation' => $request->truck_fare_operation,
            'truck_fare' => $request->truck_fare,
            'amount' => $request->amount,
            'note' => $request->note,
            'type' => $this->operation_type,
        ]);

        // insert transaction history
        Transaction::create([
            'date' => date('Y-m-d', strtotime($request->date)),
            'amount' => $request->amount,
            'party_id' => $request->party_id,
            'operation_id' => $query->id,
            'description' => ($this->operation_type) == 1 ? "Purchase Payable" : "Sale Receivable",
            'debit' => ($this->operation_type == 2) ? $request->amount : 0,
            'credit' => ($this->operation_type == 1) ? $request->amount : 0,
        ]);

        if ($this->operation_type == 1) {
            return redirect()->route('purchases')->with('success', 'Successfully purchase added');
        }

        return redirect()->route('sales')->with('success', 'Successfully sale added');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Operation $operation
     * @return \Illuminate\Http\Response
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Operation $operation
     * @return \Illuminate\Http\Response
     */
    public function edit(Operation $operation)
    {
        $data = $this->other_table_data();

        return view('operation.edit', ['operation_type' => $this->operation_type, 'operation' => $operation, 'parties' => $data['party'], 'products' => $data['product']]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Operation $operation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Operation $operation)
    {
        //validation
        $this->validation($request);

        // update amount and debit credit in transaction table
        if ($this->operation_type === 1) {
            //purchase
            $transaction = Transaction::where('operation_id', $operation->id)->where('debit', $operation->amount)->first();
//            dd($transaction);
            $transaction->debit = $request->amount;
        } else {
            $transaction = Transaction::where('operation_id', $operation->id)->where('credit', $operation->amount)->first();
            $transaction->credit = $request->amount;
        }

        $transaction->amount = $request->amount;
        $transaction->save();


        // update on operation table
        $operation->date = date('Y-m-d', strtotime($request->date));
        $operation->party_id = $request->party_id;
        $operation->w_no = $request->w_no;
        $operation->truck_no = $request->truck_no;
        $operation->product_id = $request->product_id;
        $operation->bag = $request->bag;
        $operation->bag_weight = $request->bag_weight;
        $operation->send_weight = $request->send_weight;
        $operation->receive_weight = $request->receive_weight;
        $operation->final_weight = $request->final_weight;
        $operation->labour_value = $request->labour_value;
        $operation->labour_bill = $request->labour_bill;
        $operation->rate = $request->rate;
        $operation->truck_fare_operation = $request->truck_fare_operation;
        $operation->truck_fare = $request->truck_fare;
        $operation->amount = $request->amount;
        $operation->note = $request->note;
        $operation->type = $this->operation_type;
        $operation->save();


        if ($this->operation_type == 1) {
            return redirect()->route('purchases')->with('success', 'Successfully purchase updated');
        }

        return redirect()->route('sales')->with('success', 'Successfully sale updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Operation $operation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Operation $operation)
    {
        $operation->delete();
        if ($this->operation_type == 1) {
            return redirect()->route('purchases')->with('success', 'Successfully purchase deleted');
        }

        return redirect()->route('sales')->with('success', 'Successfully sale deleted');
    }
}
