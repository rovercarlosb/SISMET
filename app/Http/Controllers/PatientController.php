<?php

namespace App\Http\Controllers;

use Mail;
use App\History;
use App\Http\Requests;
use App\Patient;
use App\Rule;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function historialMail($id){

        $historia = History::find($id);

        $message = true;

        $path = public_path().'/patient/images';


        Mail::send('pdf.diagnostico', ['historia' => $historia, 'message' => $message], function ($message) use ($historia, $path) {

           $message->from('carlosb20052009@gmail.com', 'SISMET');
 
           //asunto
           $message->subject('SISMET | HISTORIAL MEDICO');

           if ($historia->recipe != null) {

               $message->attach($path.'/'.$historia->recipe);
           }

           //receptor
           $message->to($historia->patients[0]->email, $historia->patients[0]->name);
        });

        return redirect()->route('pacientes')->with(['mensaje' => 'El correo se envio satisfactoriamente']);
    }

    public function profile($id){

        $patient = Patient::find($id);

        $appointment = Appointment::where('patient_id','=' ,$patient->id)->where('status','=', true)->first();

        return view('profile.index',['patient' => $patient, 'appointment' => $appointment]);
    }

    public function citasMail(){

        $appointments = Appointment::where('status', '=', true)->get();

        if(count($appointments) < 1){

            return response()->json(['error' => true, 'message' => 'Actualmente no hay citas activas para ningun paciente']);

        }else{  

            foreach ($appointments as $appointment) {

                 Mail::send('emails.test', ['appointment' => $appointment], function ($message) use ($appointment) {

                    $message->from('carlosb20052009@gmail.com', 'SISMET');
 
                    //asunto
                     $message->subject('SISMET | NOTIFICACION CITA MEDICA PACIENTE '.$appointment->patient->name.' '.$appointment->patient->surname);
 
                    //receptor
                    $message->to($appointment->patient->email, $appointment->patient->name);
                 });

            }

             return response()->json(['error' => false, 'message' => 'Todas las citas notificadas correctamente']);
        }
        // $path = public_path().'/patient/images';
        // $extension = $request->file('image')->getClientOriginalExtension();
        // $fileName = $paciente->id . '.' . $extension;
        // $request->file('image')->move($path, $fileName);

        // Mail::send('emails.recipe', ['paciente' => $paciente], function ($message) use ($path,$paciente,$fileName) {

        //    $message->from('carlosb20052009@gmail.com', 'SISMET');
 
        //    //asunto
        //    $message->subject('SISMET | RECIPE MEDICO');

    
        //    $message->attach($path.'/'.$fileName);

        //    //receptor
        //    $message->to($paciente->email, $paciente->name.' '.$paciente->surname);
        // });

        

    }

    public function index()
    {
        $patients = Patient::orderBy('name', 'asc')->paginate(5);
        //dd($patients);
        return view('patient.index')->with(compact('patients'));;
    }

    public function getDiagnosis($id){
        $histories = History::where('patient_id', $id)->with('rules')->with('patients')->with('users')->get();
        //dd($histories[);

        return $histories;
    }

    public function reportePDF($id){
       
        $historia = History::find($id); 

        $message = false;

        $pdf = \PDF::loadView('pdf.diagnostico', compact('historia','message'));

        return $pdf->stream('diagnostico.pdf');
    }

    public function store( Request $request )
    {
        $validator = Validator::make($request->all(), [ 'image'=>'image' ]);

        if ( $validator->fails() )
            return response()->json(['error' => true, 'message' => 'Solo se permiten imágenes']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del paciente']);

        if ($request->get('surname') == null OR $request->get('surname') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el apellido del paciente']);

        if($request->get('address') == null OR $request->get('address') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la dirección del paciente']);

        if($request->get('email') == null OR $request->get('email') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el correo del paciente']);

        if($request->get('city') == null OR $request->get('city') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la ciudad del paciente']);

        if($request->get('country') == null OR $request->get('country') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el país del paciente']);

        if ( strlen($request->get('name'))<3 )
            return response()->json(['error' => true, 'message' => 'El nombre del paciente debe tener como mínimo 3 caracteres']);

        if ( strlen($request->get('surname'))<4 )
            return response()->json(['error' => true, 'message' => 'El apellido del paciente debe tener como mínimo 3 caracteres']);

        if ( strlen($request->get('address'))<4 )
            return response()->json(['error' => true, 'message' => 'La dirección del paciente debe tener como mínimo 3 caracteres']);

        if ( strlen($request->get('city'))<3 )
            return response()->json(['error' => true, 'message' => 'La ciudad del paciente debe tener como mínimo 2 caracteres']);

        if ( strlen($request->get('country'))<3 )
            return response()->json(['error' => true, 'message' => 'El país del paciente debe tener como mínimo 2 caracteres']);


        $patient = Patient::create([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'comment' => $request->get('comment'),
            'birthdate' => $request->get('birthdate')
        ]);

        if( $request->file('image') )
        {
            $path = public_path().'/patient/images';
            if($request->get('oldImage') !='0.png' )
                File::delete($path.'/'.$request->get('oldImage'));
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $patient->id . '.' . $extension;
            $request->file('image')->move($path, $fileName);
            $patient->image = $fileName;
        }
        else
            $patient->image = '0.jpg';

        $patient->save();

        return response()->json(['error' => false, 'message' => 'Paciente registrado correctamente']);
    }

    public function edit( Request $request )
    {
        $validator = Validator::make($request->all(), [ 'image'=>'image' ]);

        if ( $validator->fails() )
            return response()->json(['error' => true, 'message' => 'Solo se permiten imágenes']);

        if ($request->get('name') == null OR $request->get('name') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el nombre del paciente']);

        if ($request->get('surname') == null OR $request->get('surname') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el apellido del paciente']);

        if($request->get('address') == null OR $request->get('address') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la dirección del paciente']);

        if($request->get('city') == null OR $request->get('city') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar la ciudad del paciente']);

        if($request->get('country') == null OR $request->get('country') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario ingresar el país del paciente']);

        if ( strlen($request->get('name'))<3 )
            return response()->json(['error' => true, 'message' => 'El nombre del paciente debe tener como mínimo 2 caracteres']);

        if ( strlen($request->get('surname'))<4 )
            return response()->json(['error' => true, 'message' => 'El apellido del paciente debe tener como mínimo 3 caracteres']);

        if ( strlen($request->get('address'))<4 )
            return response()->json(['error' => true, 'message' => 'La dirección del paciente debe tener como mínimo 3 caracteres']);

        if ( strlen($request->get('city'))<3 )
            return response()->json(['error' => true, 'message' => 'La ciudad del paciente debe tener como mínimo 2 caracteres']);

        if ( strlen($request->get('country'))<3 )
            return response()->json(['error' => true, 'message' => 'El país del paciente debe tener como mínimo 2 caracteres']);

        $patient = Patient::find( $request->get('id') );
        $patient->name = $request->get('name');
        $patient->surname = $request->get('surname');
        $patient->address = $request->get('address');
        $patient->email = $request->get('email');        
        $patient->city = $request->get('city');
        $patient->country = $request->get('country');
        $patient->comment = $request->get('comment');
        $patient->birthdate = $request->get('birthdate');

        if( $request->file('image') )
        {
            $path = public_path().'/patient/images';
            if($request->get('oldImage') !='0.png' )
                File::delete($path.'/'.$request->get('oldImage'));
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = $patient->id . '.' . $extension;
            $request->file('image')->move($path, $fileName);
            $patient->image = $fileName;
        }

        $patient->save();

        return response()->json(['error' => false, 'message' => 'Paciente modificado correctamente']);
    }

    public function delete( Request $request )
    {
        $paciente = Patient::find($request->get('id'));

        if($paciente == null)
            return response()->json(['error' => true, 'message' => 'No existe el paciente especificado.']);

        $patient = Patient::find($request->get('id'));
        $patient->delete();

        return response()->json(['error' => false, 'message' => 'Paciente eliminado correctamente.']);
        
    }

    public function deactivate( Request $request )
    {
        $paciente = Patient::find($request->get('id'));

        $paciente->update([
            'status' => 0,
        ]);

        return response()->json(['error' => false, 'message' => 'Paciente desactivado correctamente.']);
        
    }

     public function activate( Request $request )
    {
        $paciente = Patient::find($request->get('id'));

        $paciente->update([
            'status' => 1,
        ]);

        return response()->json(['error' => false, 'message' => 'Paciente reactivado correctamente.']);
        
    }
}
