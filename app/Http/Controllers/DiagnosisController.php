<?php

namespace App\Http\Controllers;

use App\Disease;
use App\DiseaseMedication;
use App\DiseaseSymptom;
use App\Factor;
use App\History;
use App\Medication;
use App\Patient;
use App\Rule;
use App\RuleFactor;
use App\RuleRecommendation;
use App\Symptom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DiagnosisController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $patientId )
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', new Carbon(), 'UTC');
        $date->setTimezone('America/Lima');
        $date = $date->format('d-m-Y H:i:s');
        $data = ['Inicio de diagnóstico: '.$date];
        File::append('timer/diagnosis.txt', $data);

        $time_start = microtime(true);

        $patient = Patient::find($patientId);
        $patientName = $patient->surname.', '.$patient->name;
        $antecedents = Factor::where('type', 'A')->lists('name')->toJson();
        $symptoms = Factor::where('type', 'S')->lists('name')->toJson();
        $others = Factor::where('type', 'O')->lists('name')->toJson();

        $rules = Rule::all();

        return view('diagnosis.index')->with(compact('patientName','patientId', 'antecedents', 'symptoms', 'others','time_start', 'rules'));
    }

    public function getAll(){
        $sintomas = Symptom::All();
        return $sintomas;
    }

    public function symptomsByDisease($id) {
        $disease = Disease::find($id);
        $symptom_ids = $disease->symptoms()->getRelatedIds();
        $data['disease_id'] = $id;
        $data['symptom_ids'] = $symptom_ids;
        return $data;
    }

    public function diseases(Request $request)
    {
        $patient_symptoms = json_decode( $request->get('symptoms') );

        if( count($patient_symptoms)==0 ) {
            $data['error'] = true;
            $data['message'] = 'Seleccione síntomas';
            return $data;
        }

        $disease_symptoms = DiseaseSymptom::where('symptom_id',$patient_symptoms[0])->get();
        if( count($disease_symptoms)==0 ) {
            $data['error'] = true;
            $data['message'] = 'No existe enfermedad con dichos síntomas';
            return $data;
        }

        // Diseases associated with the first symptom
        $array_diseases = [];
        foreach ($disease_symptoms as $disease_symptom)
            $array_diseases [] = $disease_symptom->disease_id;

        // Filtered diseases with all their symptoms
        $array_disease_symptoms = [];
        foreach ($array_diseases as $array_disease)
            $array_disease_symptoms[$array_disease] = $this->get_symptoms($array_disease);

        // Diseases associated with the selected symptoms
        $answers = [];
        $iterator = 0;
        foreach ($array_disease_symptoms as $array_disease_symptom) {
            if( $this->symptoms_in_disease( $array_disease_symptom,$patient_symptoms ) )
                $answers [] = $array_diseases[$iterator];
            $iterator++;
        }

        if (count($answers) == 0) {
            $data['error'] = true;
            $data['message'] = 'No existe enfemedad con dichos síntomas *';
            return $data;
        } else {
            $ids    = [];
            $names  = [];
            $images = [];
            $videos = [];
            $description = [];

            foreach ($answers as $answer) {
                $disease = Disease::find($answer);
                $ids    [] = $disease->id;
                $names  [] = $disease->name;
                $images [] = $disease->image;
                $videos [] = $disease->video;
                $description [] = $disease->description;
            }
            $data['error'] = false;
            $data['id']    = $ids;
            $data['name']  = $names;
            $data['image'] = $images;
            $data['video'] = $videos;
            $data['description'] = $description;
            return $data;
        }
    }

    public function get_symptoms($disease_id)
    {
        $array_symptoms = [];
        $diseases = DiseaseSymptom::where('disease_id',$disease_id)->get();
        foreach ($diseases as $disease)
            $array_symptoms [] =  $disease->symptom_id;
        return $array_symptoms;
    }

    public function symptoms_in_disease($symptoms,$patient_symptoms)
    {
        for( $i=0;$i<count($patient_symptoms);$i++ )
            if( !$this->inside_array($symptoms,$patient_symptoms[$i]) )
                return false;
        return true;
    }

    public function inside_array($array,$element)
    {
        for( $i=0;$i<count($array);$i++ )
            if( $array[$i] == $element )
                return true;
        return false;
    }

    public function medication( $disease_id )
    {
        $disease_medications = DiseaseMedication::where('disease_id',$disease_id)->get();
        $medic_result = [];

        foreach ($disease_medications as $disease_medication) {
            $medication_test = Medication::find($disease_medication->medication_id);
            $medic_result [] = $medication_test->trade_name.' - '.$medication_test->active_component;
        }

        if (count($medic_result) == 0)
            $data['error'] = true;
        else {
            $data['error'] = false;
            $data['medication'] = $medic_result;
        }

        return $data;
    }

    //  EXPERT SYSTEM MODIFIED
    public function factorNombresId($factorName)
    {
        $factor = Factor::where('name',$factorName)->get(['id','name'])->first();

        if(  $factor== null )
            return ['success'=>'false','message'=>'No existe una factor con ese nombre, no puede ser agregado.'];
        return ['success'=>'true','data'=>$factor];
    }

    public function forwardChaining( Request $request )
    {
        $factors = json_decode($request->factors);
        $timer = json_decode($request->timer);

        $date = Carbon::createFromFormat('Y-m-d H:i:s', new Carbon(), 'UTC');
        $date->setTimezone('America/Lima');
        $date = $date->format('d-m-Y H:i:s');

        if( count($factors)==0 ) {
            return ['success' => 'false', 'message' => 'Seleccione por menos un factor.'];
        }

        $ruleFactors = RuleFactor::where('factor_id',$factors[0])->get();
        if( count($ruleFactors)==0 ) {
            $time_end = microtime(true);
            File::append('timer/diagnosis.txt', '   Término de diagnóstico: '.$date);
            File::append('timer/diagnosis.txt', '   Duración de diagnóstico: '.($time_end -$timer).' segundos');
            return ['success' => 'false', 'message' => 'No existen una enfermedad asociada con los factores seleccionados.'];
        }

        // Getting all rules
        $rules = [];
        foreach ($ruleFactors as $ruleFactor )
            $rules [] = $ruleFactor->rule_id;

        // A rule with all their associated factors
        $ruleWithFactors = [];
        foreach ( $rules as $rule )
            $ruleWithFactors[$rule] = $this->getFactors($rule);

        // Rules associated with the selected factors
        $possibleRules = [];
        $i = 0;
        foreach ( $ruleWithFactors as $ruleWithFactor ) {
            if( $this->areFactorsInRule( $ruleWithFactor,$factors ) )
                $possibleRules [] = $rules[$i];
            $i++;
        }

        if ( count($possibleRules) == 0 ){
            $time_end = microtime(true);
            File::append('timer/diagnosis.txt', '   Término de diagnóstico: '.$date);
            File::append('timer/diagnosis.txt', '   Duración de diagnóstico: '.($time_end - $timer).' segundos');

            return ['success'=>'false','message'=>'No existe una enfermedad asociada a los factores seleccionados...'];
          }
        else {
            $rules = collect();
            foreach ( $possibleRules as $possibleRule ) {
                $rule = Rule::find($possibleRule);
                $rules->push($rule);
            }
            return ['success'=>'true','data'=>$rules];
        }
    }

    public function getFactors( $ruleId )
    {
        $factors = [];
        $ruleFactors = RuleFactor::where('rule_id',$ruleId)->get();
        foreach ( $ruleFactors as $ruleFactor )
            $factors [] =  $ruleFactor->factor_id;
        return $factors;
    }

    public function areFactorsInRule( $ruleWithFactor,$factors )
    {
        for( $i=0;$i<count($factors);$i++ )
            if( !$this->insideArray($ruleWithFactor,$factors[$i]) )
                return false;
        return true;
    }

    public function insideArray( $array,$element )
    {
        for( $i=0;$i<count($array);$i++ )
            if( $array[$i] == $element )
                return true;
        return false;
    }


    public function diseaseFactors($ruleId)
    {
        $ruleFactors = RuleFactor::where('rule_id',$ruleId)->get(['factor_id']);

        return ['rule_id'=>$ruleId,'factorIds'=>$ruleFactors];
    }

    public function allFactors()
    {
        $factors = Factor::all();
        return $factors;
    }

    public function writeTimer( Request $request )
    {
        $timer = json_decode($request->timer);
        $disease_id = json_decode($request->disease_id);

        $date = Carbon::createFromFormat('Y-m-d H:i:s', new Carbon(), 'UTC');
        $date->setTimezone('America/Lima');
        $date = $date->format('d-m-Y H:i:s');

        $time_end = microtime(true);

        File::append('timer/diagnosis.txt', '   Término de diagnóstico: '.$date);
        File::append('timer/diagnosis.txt', '   Duración de diagnóstico: '.($time_end - $timer).' segundos');

        return ['message'=>'Diagnóstico terminado.'];
    }

    public function diseaseRecommendations($ruleId)
    {
        $rules = RuleRecommendation::where('rule_id',$ruleId)->get();

        $recommendations = collect();
        foreach ( $rules as $rule ) {
            $m = Medication::find($rule->medication_id);
            $recommendations->push($m);
        }

        return $recommendations;
    }

    public function saveDiagnostic( $patientId, $ruleId, Request $request )
    {
        $history = History::where('rule_id',$ruleId)->where('patient_id',$patientId)->first();
        if( $history <> null )
            return ['success'=>'false','message'=>'El paciente ya ha sido diagnosticado dicha enfermedad.'];

        $date = new Carbon();
        $date->tz = 'America/Caracas';
        $date = $date->format('Y-m-d');

        if( $request->file('recipe') )
        {
            $path = public_path().'/patient/images';
            $extension = $request->file('recipe')->getClientOriginalExtension();
            $fileName = $patientId . '.' . $extension;
            $request->file('recipe')->move($path, $fileName);
        }else{

            $fileName = null;
        }

        $history = History::create([
            'patient_id'=>$patientId,
            'date'=>$date,
            'rule_id'=>$ruleId,
            'recipe' => $fileName,
            'user_id'=>Auth()->user()->id
        ]);

        $history->save();
        return ['success'=>'true','message'=>'Diagnóstico guardado correctamente.'];
    }
}
