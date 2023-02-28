<?php
namespace App\Models\Clinic;
use App\Models\Base as  Model;
class AppointmentFile extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'appointments_files';
	protected $primaryKey = 'id';
	// public $timestamps = true;

	protected $fillable = array('file','appointment_id');

	public static $rules = array(
		'file' => 'required',
		'appointment_id' => 'required|integer',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
	{
		return $this->belongsTo(Appointment::class);
	}




}