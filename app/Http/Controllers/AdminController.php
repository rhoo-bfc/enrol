<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    //

    public function index()
    {
        $attendants = \App\Models\Attendant::paginate(10);
        return \View::make( 'admin.attendants', [ 'attendants' => $attendants ] );
    }

    public function getAttendant( $id ) {

        $attendant = \App\Models\Attendant::FindOrFail($id);
        return response()->json( $attendant );
    }

    public function addAttendant( Request $request ) {

        if ($request->get('att_id') <> '') {
            $attendant = \App\Models\Attendant::FindOrFail( $request->get('att_id') );
            $status = 'UPDATED';
        } else {
            $attendant = new \App\Models\Attendant();
            $status = 'INSERT';
        }

        $attendant->att_first_name  = $request->get('att_first_name');
        $attendant->att_second_name = $request->get('att_second_name');
        $attendant->att_email       = $request->get('att_email');
        $attendant->att_active      = $request->get('att_active');
        $attendant->save();

        return \Redirect::back()->with('success', 'The attendant has been ' .
                                                   ($status === 'UPDATED' ? 'updated' : 'added' ) );

    }

    public function getServiceDesk( $id ) {

        $serviceDesk = \App\Models\ServiceDesk::FindOrFail($id);
        return response()->json(  $serviceDesk );
    }

    public function addServiceDesk( Request $request ) {

        if ($request->get('src_id') <> '') {
            $serviceDesk = \App\Models\ServiceDesk::FindOrFail( $request->get('src_id') );
            $status = 'UPDATED';
        } else {
            $serviceDesk = new \App\Models\ServiceDesk();
            $status = 'INSERT';
        }

        $serviceDesk->src_centre_name = $request->get('src_centre_name');
        $serviceDesk->src_centre_desc = $request->get('src_centre_desc');
        $serviceDesk->src_active      = $request->get('src_active');
        $serviceDesk->save();

        return \Redirect::back()->with('success', 'The service desk has been ' .
                                                    ($status === 'UPDATED' ? 'updated' : 'added' ));

    }

    public function getRollingMessage( $id ) {

        $rollingMessage = \App\Models\RollingMessage::FindOrFail($id);
        return response()->json( $rollingMessage );
    }

    public function addRollingMessage( Request $request ) {

        if ($request->get('rmg_id') <> '') {
            $rollingMessage = \App\Models\RollingMessage::FindOrFail( $request->get('rmg_id') );
            $status = 'UPDATED';
        } else {
            $rollingMessage = new \App\Models\RollingMessage();
            $status = 'INSERT';
        }

        $rollingMessage->rmg_que_id  = $request->get('rmg_que_id');
        $rollingMessage->rmg_message = $request->get('rmg_message');
        $rollingMessage->rmg_active  = $request->get('rmg_active');
        $rollingMessage->save();

        return \Redirect::back()->with('success', 'The message has been ' .
            ($status === 'UPDATED' ? 'updated' : 'added' ));
    }

    public function deleteRollingMessage( $id ) {

        $rollingMessage = \App\Models\RollingMessage::FindOrFail($id);
        $rollingMessage->delete();
        return \Redirect::back()->with('success', 'The message has been deleted');

    }
}
