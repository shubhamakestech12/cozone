<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enquiry;
use Validator;
use DataTables;
use Str;
class EnquiryController extends Controller
{
    //
    public function index(){
        $data['title'] = "Enquiry";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/enquiry.js');
        
        $data['content'] = view('admin.enquiry')->render();
        return view('template',$data);
    }
    public function showEnquiry(Request $request)
    {
        if ($request->ajax()) {
            $data = Enquiry::where('enquiries.is_deleted', 1)
                    ->join('property_details','enquiries.property_id','=','property_details')
                    ->orWhere('property_details','=','property_details.id')
                    ->orWhere('enquiries','=','enquiries.property_id')
                ->get(['enquiries.id as id', 'enquiries.name as name','enquiries.email as email','enquiries.mobile_no as mobile_no','enquiries.space_type as space_type','enquiries.persons as persons','enquiries.is_active as is_active','enquiries.is_deleted as is_deleted']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                   
                })
              
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']), $request->get('search'))) {
                                return true;
                            }

                            return false;
                        });
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    } //End of Function
}
