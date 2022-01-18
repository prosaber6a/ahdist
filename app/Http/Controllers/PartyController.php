<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $party = Party::orderBy('id', 'desc')->get();
        return view('party.index', ['parties' => $party]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('party.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'company' => 'nullable|string|max:60',
            'address' => 'nullable|string',
            'mobile' => 'required|numeric|unique:parties',
            'email' => 'nullable|email|unique:parties',
            'type' => 'required|integer|max:2',
            'status' => 'required|integer|max:1',
        ]);

        Party::create([
            'name' => $request->name,
            'company' => $request->company,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('parties')->with('success', 'Successfully party added');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Party $party
     * @return \Illuminate\Http\Response
     */
    public function show(Party $party)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Party $party
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        return view('party.edit', ['party' => $party]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Party $party
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Party $party)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'company' => 'nullable|string|max:60',
            'address' => 'nullable|string',
            'mobile' => 'required|numeric|unique:parties,mobile,'.$party->id,
            'email' => 'nullable|email|unique:parties,email,'.$party->id,
            'type' => 'required|integer|max:2',
            'status' => 'required|integer|max:1',
        ]);


        $party->name = $request->name;
        $party->company = $request->company;
        $party->address = $request->address;
        $party->mobile = $request->mobile;
        $party->email = $request->email;
        $party->type = $request->type;
        $party->status = $request->status;

        try {
            $party->update();
        } catch (\Exception $exception) {
            return redirect()->route('edit_party', $party->id)->withError($exception->getMessage())->withInput();
        }

        return redirect()->route('parties')->with('success', 'Successfully party updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Party $party
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party $party)
    {
        // Delete Other Table Info
        try {
            $party->delete();
        } catch (\Exception $exception) {
            return redirect()->route('parties')->withError($exception->getMessage());
        }

        return redirect()->route('parties')->with('success', 'Successfully party deleted');
    }
}
