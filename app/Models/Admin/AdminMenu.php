<?phpnamespace App\Models\Admin;use App\Models\Base as  Model;use Illuminate\Support\Facades\Session;class AdminMenu extends Model {	/**	 * The database table used by the model.	 *	 * @var string	 */	protected $table = 'admin_menu';	protected $primaryKey = 'id';	public $timestamps = false;	protected $fillable = array('menuTitleEn','menuTitleAr','menuLink','menuIco','menu_id','ordering','visible');	public static $rules = array(		'menuTitleEn' => 'required|max:50',		'menuTitleAr' => 'required|max:50',		'menuLink' => 'max:100',		'menuIco' => 'max:30',		'menu_id' => 'integer',		'ordering' => 'integer',		'visible' => 'integer',	);	public function menus()	{		return $this->hasMany(AdminMenu::class,'menu_id')				->where('visible','1')				->orderBy('ordering','asc');	}	public function activemenus()	{		return $this->hasMany(AdminMenu::class,'menu_id')				->where('visible','1')				->orderBy('ordering','asc');	}	public function parentMenu()	{		return $this->belongsTo(AdminMenu::class,'menu_id');	}	public static function parentMenuData()	{		if (Session::get('lang')) {			$lang = Session::get('lang');		} else {			$lang = config('settings.defaultLang.value');		}		$menu = AdminMenu::all();		$data = array();		$data[0] = trans('main.Choose');		foreach ($menu as $link) {			$data[$link->id] = $link->{'menuTitle'.ucfirst($lang)};		}		return $data;	}}