<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use App\Http\Requests;
use App\Appointment;
use App\Patient;
class AppoinmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
         $hoy = date('Y-m-d');

         $appoinments_old = Appointment::whereDate('date', '<=' ,$hoy )->where('status', '=', true)->get();

         foreach ($appoinments_old as  $old) {
            
            $old->update(['status' => false]);
         }

         $appoinments = Appointment::orderby('date','asc')->simplePaginate(4);
         $patients = Patient::all();
         return view('appoinment.index')->with(compact(['appoinments','patients']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointment($id)
    {
        $appointment = Appointment::where('patient_id', $id)->where('status', '=', true)->first();

        if (isset($appointment->hour)) {

           $hora = date_create($appointment->hour);
           $fecha = date_create($appointment->date);

           $appointment->hour = date_format($hora,'g:i A');
           $appointment->date = date_format($fecha,'d-m-Y');

        }

         return $appointment;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appoinment = Appointment::create([
            'patient_id'=> $request->get('patient_id'),
            'date'=> $request->get('date'),
            'hour'=>$request->get('hour')
        ]);


        $appoinment->save();

        return response()->json(['error' => false, 'message' => 'Cita agendada correctamente']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function appointmentMail($id)
    {
         $appointment = Appointment::find($id);

          Mail::send('emails.test', ['appointment' => $appointment], function ($message) use ($appointment) {

           $message->from('carlosb20052009@gmail.com', 'SISMET');
 
           //asunto
           $message->subject('SISMET | NOTIFICACION CITA MEDICA PACIENTE '.$appointment->patient->name.' '.$appointment->patient->surname);
 
           //receptor
           $message->to($appointment->patient->email, $appointment->patient->name);
        });

        return redirect()->route('pacientes')->with(['mensaje' => 'La notificacion por correo se envio satisfactoriamente']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)  
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    
    {
        $appoinment = Appointment::find($request->get('id'));

        $appoinment->update($request->all());

        return response()->json(['error' => false, 'message' => 'Cita modificada correctamente']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appoinment = Appointment::find($id);

        $appoinment->delete();

        return response()->json(['error' => false, 'message' => 'Cita eliminada correctamente']);
    }
}
