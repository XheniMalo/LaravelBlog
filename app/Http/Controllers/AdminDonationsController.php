<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Yajra\DataTables\Facades\DataTables;

class AdminDonationsController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDonationsDataTable($request);
        }
        return view('admin.donations');
    }

    public function getDonationsDataTable(Request $request)
    {
        $donations = Donation::with('user') 
            ->select('donations.*', 'users.name as user_name') 
            ->join('users', 'donations.user_id', '=', 'users.id'); 

        if ($request->has('search') && $request->search['value']) {
            $donations->where('users.name', 'like', '%' . $request->search['value'] . '%');
        }

        $donations = $donations->paginate(10);

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $donations->total(),
            'recordsFiltered' => $donations->total(),
            'data' => $donations->items(), 
        ]);
    }

}
