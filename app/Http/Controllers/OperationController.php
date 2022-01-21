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
        $data = [];
        $data['operation_type'] = $this->operation_type;
        if ($this->operation_type === 1) {
            //purchase
            $data['operations'] = Operation::where('type', 1)
                ->orderBy('updated_at', 'desc')
                ->get();
            $data['parties'] = Party::where('type', 1)->get();
        } else {
            //sale
            $data['operations'] = Operation::where('type', 2)
                ->orderBy('updated_at', 'desc')
                ->get();
            $data['parties'] = Party::where('type', 2)->get();
        }

        return view('operation.index', $data);
    }

    /**
     * Return JSON
     */

    public function filter_operation($type = 0, $party = 0, $from = 0, $to = 0)
    {
        $type = intval($type);
        $party = intval($party);
        $from = ($from == 0) ? 0 : date('Y-m-d', strtotime($from));
        $to = ($to == 0) ? 0 : date('Y-m-d', strtotime($to));

        $operation = Operation::where('type', $type)->orderBy('date', 'desc');

        if (!empty($from) && !empty($to) && strtotime($from) && strtotime($to)) {
            $operation = $operation->whereBetween('date', [$from, $to]);
        }

        if ($party > 0) {
            $operation = $operation->where('party_id', $party);
        }

        $operation = $operation->get();

        $data = [];
        $i = 1;
        foreach ($operation as $op) {
            if ($type === 1) {
                $_edit_url = route('edit_purchase', $op->id);
                $_delete_url = route('delete_purchase', $op->id);
            } else {
                $_edit_url = route('edit_sale', $op->id);
                $_delete_url = route('delete_sale', $op->id);
            }

            $row = [];
            $row['sl'] = $i++;
            $row['date'] = date('d M, Y', strtotime($op->date));
            $row['party'] = $op->party->name;
            $row['w_no'] = $op->w_no ? $op->w_no : "";
            $row['truck_no'] = $op->truck_no ? $op->truck_no : "";
            $row['product'] = $op->product->name;
            $row['bag'] = $op->bag;
            $row['bag_weight'] = $op->bag_weight;
            $row['send_weight'] = $op->send_weight;
            $row['receive_weight'] = $op->receive_weight;
            $row['final_weight'] = $op->final_weight;
            $row['labour_value'] = $op->labour_value;
            $row['labour_bill'] = $op->labour_bill;
            $row['rate'] = $op->rate;
            $row['truck_fare_operation'] = (intval($op->truck_fare_operation) === 1) ? "(+)" : "(-)";
            $row['truck_fare'] = $op->truck_fare;
            $row['amount'] = $op->amount;
            $row['note'] = $op->note ? $op->note : "";
            $row['action'] = '
                <a href="' . $_edit_url . '"
                   class="btn btn-sm btn-icon btn-primary"><i
                        class="bi bi-pencil-square"></i></a>
                <form class="d-inline" action="' . $_delete_url . '" method="post"
                      class="deleteForm">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="delete">
                    <button type="submit" class="btn btn-sm btn-icon btn-danger"
                            data-kt-ecommerce-category-filter="delete_row"><i
                            class="bi bi-trash"></i></button>
                </form>
                ';
            $data[] = $row;
        }

        if (count($data) > 0) {

            echo json_encode(['status' => 200, 'data' => $data]);
        } else {
            echo json_encode(['status' => 404, 'data' => '']);
        }

        exit();


    }

    /**
     * Return required other table data for operation
     */

    private function other_table_data()
    {
        $data = [];
        if ($this->operation_type === 1) {
            //purchase
            $data['parties'] = Party::where('type', 1)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        } else {
            //sale
            $data['parties'] = Party::where('type', 2)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        }

        $data['products'] = Product::where('status', 1)
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

        $data['operation_type'] = $this->operation_type;

        if ($this->operation_type === 2 && isset($_GET['purchase']) && $_GET['purchase'] != '') {
            $data['recent_purchase'] = Operation::find(intval($_GET['purchase']));
        }


        return view('operation.create', $data);


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
            return redirect()->route('create_sale', ['purchase' => $query->id])->with('success', 'Successfully purchase added');
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


    /**
     * Return sales vs purchase chart data json
     */


    public function last_month_sales_vs_purchase()
    {

        $operations = Operation::whereMonth('date', date('m'))->whereYear('date', date('Y'))->get();
        $data = [];
        $data[] = ["Day", "Sales", "Purchases"];
        $current_month = date('m');
        $current_year = date('y');


        $total_day_of_current_months = cal_days_in_month(CAL_GREGORIAN, intval($current_month), intval($current_year));
        for ($i = 1; $i <= intval(date('d')); $i++) {
            $_total_purchase = 0;
            $_total_sale = 0;
            foreach ($operations as $operation) {
                if (intval(date('d', strtotime($operation->date))) === $i) {

                    if (intval($operation->type) === 2) {
                        $_total_sale += $operation->amount;
                    }

                    if (intval($operation->type) === 1) {
                        $_total_purchase += $operation->amount;
                    }


                }
            }


            $data[] = [$i . "/" . $current_month . "/" . $current_year, $_total_sale, $_total_purchase];

        }

        echo json_encode($data);
        exit();

    }


}
