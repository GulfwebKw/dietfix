<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Appointment;
use App\Models\Clinic\AppointmentFile;
use App\Models\Clinic\Day;
use App\Models\Clinic\DoctorTime;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AppointmentsController extends MainController
{
    public function getIndex()
    {
        $id = Input::has('user') ? Input::get('user') : false;
        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }

        $days = Day::where('active',1)->orderBy('ordering','asc')->get();
        if($id)
            $user = User::find($id);
        else
            $user = Auth::user();
        $appointments = $user->appointments;

        return View::make('appointments.index')
            ->with('days',$days)
            ->with('user',$user)
            ->with('appointments',$appointments)
            ->withTitle(trans('main.Appointments'));
    }

    public function getManage()
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }

        $confirmed = Appointment::where('clinic_id',Auth::user()->clinic_id)
            ->where('doctor_id', Auth::user()->id)
            // ->where('confirmed', 1)
            ->where('date','>=',date('Y-m-d'))
            ->orderBy('date','asc');

        if (Input::all()>=1) {
            foreach (Input::all() as $k => $v) {
                if ($k == 'client_id') {
                    $confirmed = $confirmed->whereHas('user' , function ($q) use ($v)
                    {
                        return $q->whereRaw("username LIKE '%" . $v . "%'");
                    });
                } else
                    $confirmed = $confirmed->whereRaw($k . " LIKE '%" . $v . "%'");
            }
        }

        $confirmed = $confirmed->get();


        // $unconfirmed = Appointment::where('clinic_id',Auth::user()->clinic_id)
        // 						->where('doctor_id', Auth::user()->id)
        // 						->where('confirmed', 0)
        // 						->where('date','>=',date('Y-m-d'))
        // 						->orderBy('date','asc')
        // 						->get();

        // dd($this->queries());
        return View::make('appointments.doctor')
            // ->with('unconfirmed',$unconfirmed)
            ->with('confirmed',$confirmed)
            ->withTitle(trans('main.Appointments'));

    }

    public function getConfirm($id,$user_id)
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }

        $appointment = Appointment::find($id);
        $appointment->confirmed = 1;
        $appointment->save();

        return Redirect::to('appointments/manage');
    }

    public function postSave($id)
    {
        $data = Input::except(['_token','filess']);
       // dd($data);
        $data['hour'] = date('H:i:s',strtotime($data['hour']));


        // print_r(Input::all());
        // dd($data['hour']);


        $appointment = Appointment::where('doctor_id',$data['doctor_id'])
            ->where('date',$data['date'])
            ->where('hour',$data['hour'])
            ->where('user_id','!=',$data['user_id'])
            ->first();

        if ($appointment) {
            return Redirect::to('appointments/add/'.$id)->withInput()->with('message',[trans('main.Please choose another time')]);
        }

        // if ($this->notDoctor()) {
        // 	$data['confirmed'] = 0;
        // } else {
        // 	$data['confirmed'] = 1;
        // }

        if($data['id'])
            $appointment = Appointment::find($data['id']);
        else
            $appointment = new Appointment;
        $appointment->fill($data);
        $appointment->save();
        $appointment->bmi = @intval($appointment->weight / (($appointment->height/100) * ($appointment->height/100)));
        $appointment->save();

        // Files
        $files = Input::get('filesss');
        if(isset($files)) {
            // dd(Input::all());

            AppointmentFile::where('appointment_id',$appointment->id)->delete();
            foreach ($files as $file) {
                $apFile = new AppointmentFile();
                $apFile->appointment_id = $appointment->id;
                $apFile->file = $file;
                $apFile->save();
            }
        }

        if ($this->notDoctor())
            return Redirect::to('appointments');
        else
            return Redirect::to('appointments?user='.$id);
    }

    public function postCheckHour()
    {
        $doctor_id = Input::get('doctor');
        $date = Input::get('date');
        $hour = Input::get('hour');
        $hour = date('H:i:s',strtotime($hour));

        $appointment = Appointment::where('doctor_id',$doctor_id)
            ->where('date',$date)
            ->where('hour',$hour)
            ->first();

        if($appointment)
            return ['result' => false];
        return ['result' => true];
    }

    public function getDoctorDateTimes($doctor_id,$date)
    {
        $dayOfWeek = date('w',strtotime($date));
        $day = Day::where('day_number',$dayOfWeek)
            ->first();
        if (!$day) {
            return ['result' => false];
        }
        $doctorTimes = DoctorTime::where('doctor_id',$doctor_id)
            ->where('day_id',$day->id)
            ->first();

        if (!$doctorTimes) {
            return ['result' => false];
        }

        $doctorTimes->end = date("H:i:s",strtotime("-15 minutes",strtotime($doctorTimes->end)));
        $times_sorted = ['from' => $doctorTimes->start,'to' => $doctorTimes->end];
        return ['result' => $times_sorted];
    }

    public function getDoctorTimes($doctor_id)
    {
        $doctor = User::with('times')
            ->with('times.day')
            ->find($doctor_id);

        $times_sorted = [];
        foreach ($doctor->times as $time) {
            $to = date("H:i:s",strtotime("-15 minutes",strtotime($time->end)));

            $times_sorted[$time->day->titleEn] = ['from' => $time->start,'to' => $to];
        }

        $doctor->times_sorted = $times_sorted;
        return $doctor;
    }

    public function getAdd($user_id)
    {
        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }
        if ($this->notDoctor() && Auth::user()->id != $user_id) {
            return $this->dontAllow();
        }
        $user = User::find($user_id);

        $doctors = User::where('role_id',2)
            ->where('clinic_id',$user->clinic_id)
            ->select(['username','id'])->get();

        $days = Day::where('active',1)->orderBy('ordering','asc')->select(['title'.LANG,'id']);

        $item = new Appointment;
        return View::make('appointments.add')
            ->with('days',$days)
            ->with('user',$user)
            ->with('doctors',$doctors)
            ->with('item',$item)
            ->withTitle(trans('main.Appointments'));
    }

    public function getEdit($id,$user_id)
    {
        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }
        if ($this->notDoctor() && Auth::user()->id != $user_id) {
            return $this->dontAllow();
        }
        $user = User::find($user_id);

        $doctors = User::where('role_id',2)
            ->where('clinic_id',$user->clinic_id)
            ->select(['username','id'])->get();

        $days = Day::where('active',1)->orderBy('ordering','asc')->select(['title'.LANG,'id'])->get();

        $item = Appointment::find($id);
        return View::make('appointments.add')
            ->with('days',$days)
            ->with('user',$user)
            ->with('doctors',$doctors)
            ->with('item',$item)
            ->withTitle(trans('main.Appointments'));
    }
}
