<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = Account::orderBy('updated_at', 'desc')->get();
        return view('account.index', ['accounts' => $account]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account.create');
    }


    /**
     * Validate Post Request Data
     */

    private function validation(Request $request)
    {
        $request->validate([
            'bank' => 'required|string|max:60',
            'branch' => 'required|string|max:60',
            'name' => 'required|string|max:60',
            'acc_no' => 'required|integer|max:99999999999999999',
            'initial_balance' => 'required|numeric',
            'note' => 'nullable|string',
            'status' => 'nullable|integer|max:1',
        ]);
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

        try {
            $account = Account::create([
                'bank' => $request->bank,
                'branch' => $request->branch,
                'name' => $request->name,
                'acc_no' => $request->acc_no,
                'initial_balance' => $request->initial_balance,
                'note' => $request->note,
                'status' => $request->status,
            ]);
        } catch (\Exception $exception) {

            return redirect()->route('create_account')->withError($exception->getMessage())->withInput();
        }

        try {
            Transaction::create([
                'date' => date('Y-m-d'),
                'account_id' => $account->id,
                'type' => 0,
                'amount' => $request->initial_balance,
                'description' => 'Initial Balance',
                'debit' => 0,
                'credit' => $request->initial_balance,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('create_account')->withError("An Error occurred while creating initial balance transaction.<br/>" . $exception->getMessage())->withInput();
        }

        return redirect()->route('accounts')->with('success', 'Successfully account added');


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        return view('account.edit', ['account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $this->validation($request);

        // set account new value
        $account->bank = $request->bank;
        $account->branch = $request->branch;
        $account->name = $request->name;
        $account->acc_no = $request->acc_no;
        $account->initial_balance = $request->initial_balance;
        $account->note = $request->note;
        $account->status = $request->status;

        // try to update in database
        try {
            $account->save();
        } catch (\Exception $exception) {

            return redirect()->route('edit_account', $account->id)->withError($exception->getMessage());
        }

        return redirect()->route('accounts')->with('success', 'Successfully account updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        // Delete Other Table Info
        try {
            $account->delete();
        } catch (\Exception $exception) {
            return redirect()->route('accounts')->withError($exception->getMessage());
        }

        return redirect()->route('accounts')->with('success', 'Successfully account deleted');
    }
}
