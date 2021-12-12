<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use DB, Log, Auth;

class MemberController extends Controller
{

    public function index(Request $request) {
        $members = Member::all();
        return view('members.index', compact('members'));
    }
    
    public function store(Request $request) {
        try {
            $data = $request->validate([
                "first_name" => 'required',
                "last_name" => 'required',
                "father_name" => 'required',
                "dob" => 'required|date',
                "document" => 'required',
                "phone_number" => 'required'
            ]);

            if($request->hasfile('document')) {
                $file = $request->document;
                $name = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/documents/', $name, 'public');
                $data['document'] = $name;
            }

            DB::beginTransaction();
            Member::create($data);
            DB::commit();
            if(Auth::check())
                return redirect()->route('members.index')->with('success', 'Member Added Successfully');
            else 
                return redirect()->back()->with('success', 'Member Added Successfully');
        }
        catch(\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function documentDownload($id) {
        $member = Member::find($id);
    	$filePath = public_path('storage/uploads/documents/' . $member->document);
    	$headers = ['Content-Type: application/pdf'];
    	$fileName = $member->document;
        // dd($fileName);

    	// return response()->download($filePath, $fileName, $headers);
    	return response()->file($filePath, $headers);
    }
}
