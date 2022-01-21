<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Party;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * 1 = Income
     * 2 = Expense
     * 3 = Transfer
     * 0 = Null
     */
    public $transaction_type;

    function __construct()
    {
        if (\Illuminate\Support\Facades\Request::is('transaction/deposit*')) {
            $this->transaction_type = 1;
        } elseif (\Illuminate\Support\Facades\Request::is('transaction/expense*')) {
            $this->transaction_type = 2;
        } else {
            $this->transaction_type = 3;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('date', 'asc')->orderBy('created_at', 'asc')->get();
        $account = Account::all();
        $party = Party::all();

        return view('transaction.index', ['transactions' => $transaction, 'account' => $account, 'parties' => $party]);
    }

    /**
     * Transaction filter by party, account and date range
     */
    public function filter_transaction($party_id = 0, $acc_id = 0, $from = "", $to = "")
    {


        $party_id = intval($party_id);
        $acc_id = intval($acc_id);
        $from = ($from == 0) ? 0 : date('Y-m-d', strtotime($from));
        $to = ($to == 0) ? 0 : date('Y-m-d', strtotime($to));

        $transactions = Transaction::orderBy('date', 'asc')->orderBy('created_at', 'asc');
        if (!empty($from) && !empty($to) && strtotime($from) && strtotime($to)) {
            $transactions = $transactions->whereBetween('date', [$from, $to]);
        }

        if ($party_id > 0) {
            $transactions = $transactions->where('party_id', $party_id);
        }

        if ($acc_id > 0) {
            $transactions = $transactions->where('account_id', $acc_id);
        }

        $transactions = $transactions->get();

        $data = [];
        $i = 1;
        foreach ($transactions as $transaction) {

            $_edit_url = route('edit_transaction', $transaction->id);
            $_delete_url = route('delete_transaction', $transaction->id);
            $row = [];
            $row['sl'] = $i++;
            $row['date'] = date('d M, Y', strtotime($transaction->date));
            $row['account'] = $transaction->account_id ? $transaction->account->name.' - '.$transaction->account->bank : '-';
            $row['party'] = $transaction->party_id ? $transaction->party->name : '-';
            $row['description'] = $transaction->description;
            $row['debit'] = $transaction->debit;
            $row['credit'] = $transaction->credit;
            $row['action'] = '
                <a href="' . $_edit_url . '"
                   class="btn btn-sm btn-icon btn-primary"><i
                        class="bi bi-pencil-square"></i></a>
                <form action="' . $_delete_url . '" method="post"
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = [];
        $data['type'] = $this->transaction_type;
        $data['accounts'] = Account::where('status', 1)->get();

        if ($this->transaction_type === 1) {
            $data['parties'] = Party::where('type', 2)->get();
        }

        if ($this->transaction_type === 2) {
            $data['parties'] = Party::where('type', 1)->get();
        }


        return view('transaction.create', $data);
    }


    private function validation(Request $request)
    {

        if (intval($request->type) !== 3) {
            // income or expense
            $request->validate([
                'date' => 'required|date',
                'account_id' => 'required|integer',
                'amount' => 'required|numeric|min:1|max:999999999',
                'party_id' => 'nullable|integer',
                'description' => 'nullable|string',
                'type' => 'required|integer|max:3|min:1',
            ]);

        } else {
            // transfer
            $request->validate([
                'date' => 'required|date',
                'account_id' => 'required|integer',
                'to_acc_id' => 'required|integer',
                'amount' => 'required|numeric|min:1|max:999999999',
                'type' => 'required|integer|max:3|min:1',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validation($request);

        if (intval($request->type) !== 3) {

            Transaction::create([
                'account_id' => $request->account_id,
                'party_id' => ($request->party_id > 0) ? $request->party_id : 0,
                'date' => date('Y-m-d', strtotime($request->date)),
                'description' => $request->description,
                'amount' => $request->amount,
                'type' => $request->type,
                'debit' => (intval($request->type) === 2) ? $request->amount : 0,
                'credit' => (intval($request->type) === 1) ? $request->amount : 0
            ]);

        } else {

            $to_acc = Account::find(intval($request->to_acc_id))->first();

            $description = <<<EOD
Transfer to $to_acc->name, $to_acc->bank, $to_acc->branch. A/C - $to_acc->acc_no
EOD;


            // insert debit
            Transaction::create([
                'account_id' => $request->account_id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'description' => $description,
                'amount' => $request->amount,
                'type' => $request->type,
                'debit' => $request->amount
            ]);

            //insert credit
            $from_acc = Account::find(intval($request->account_id))->first();
            $description = <<<EOD
Receive from $from_acc->name, $from_acc->bank, $from_acc->branch. A/C - $from_acc->acc_no
EOD;
            // insert debit
            Transaction::create([
                'account_id' => $request->to_acc_id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'description' => $description,
                'amount' => $request->amount,
                'type' => $request->type,
                'credit' => $request->amount
            ]);
        }


        if (intval($request->type) === 1) {
            $type = "deposit";
        } elseif (intval($request->type) === 2) {
            $type = "expense";
        } else {
            $type = "transfer";
        }

        $msg = sprintf("Successfully %s added", $type);
        return redirect()->route('transactions')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {

        $data = [];
        $data['transaction'] = $transaction;
        $data['accounts'] = Account::where('status', 1)->get();

        if ($this->transaction_type === 1) {
            $data['parties'] = Party::where('type', 2)->get();
        }

        if ($this->transaction_type === 2) {
            $data['parties'] = Party::where('type', 1)->get();
        }
        return view('transaction.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validation($request);

        if (intval($request->type) !== 3) {
            $transaction->account_id = $request->account_id;
            $transaction->party_id = ($request->party_id > 0) ? $request->party_id : 0;
            $transaction->date = date('Y-m-d', strtotime($request->date));
            $transaction->description = $request->description;
            $transaction->amount = $request->amount;
            $transaction->type = $request->type;
            $transaction->debit = (intval($request->type) === 2) ? $request->amount : 0;
            $transaction->credit = (intval($request->type) === 1) ? $request->amount : 0;
            $transaction->save();

        } else {

            $to_acc = Account::find(intval($request->to_acc_id))->first();

            if (floatval($transaction->debit) > 0) {

                $description = <<<EOD
Transfer to $to_acc->name, $to_acc->bank, $to_acc->branch. A/C - $to_acc->acc_no
EOD;
            } else {
                $description = <<<EOD
Receive from $to_acc->name, $to_acc->bank, $to_acc->branch. A/C - $to_acc->acc_no
EOD;
            }


            // update debit
            $transaction->account_id = $request->account_id;
            $transaction->date = date('Y-m-d', strtotime($request->date));
            $transaction->description = $description;
            $transaction->amount = $request->amount;
            $transaction->debit = (floatval($transaction->debit) > 0) ? $request->amount : 0;
            $transaction->credit = (floatval($transaction->credit) > 0) ? $request->amount : 0;
            $transaction->save();


        }


        if (intval($request->type) === 1) {
            $type = "deposit";
        } elseif (intval($request->type) === 2) {
            $type = "expense";
        } else {
            $type = "transfer";
        }

        $msg = sprintf("Successfully %s updated", $type);
        return redirect()->route('transactions')->with('success', $msg);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
        } catch (\Exception $exception) {
            return redirect()->route('transactions')->withError($exception->getMessage());
        }

        return redirect()->route('transactions')->with('success', 'Successfully transaction deleted');
    }


    /**
     * return last 10 transaction json
     */
    public function last_10_transaction () {
        $transactions = Transaction::orderBy('updated_at', 'desc')->take(10)->get();
        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                date('d M, Y', strtotime($transaction->date)),
                $transaction->description ? $transaction->description: '',
                number_format($transaction->debit, 2, ".", ","),
                number_format($transaction->credit, 2, ".", ",")
            ];
        }
        echo json_encode($data);
        exit();
    }
}
