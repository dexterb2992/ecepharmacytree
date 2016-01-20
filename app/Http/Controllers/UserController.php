<?php
namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;
use Auth;
use Validator;
use Redirect;
use Image;
use Illuminate\Mail\Mailer;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\User;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Branch;

class UserController extends Controller
{

    function __construct(Mailer $mailer) {
        $this->middleware('auth');
        $this->mailer = $mailer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        if( Auth::user()->isBranchAdmin() ){
            $employees = User::where('id', '!=', Auth::user()->id)
                ->where('branch_id', '=', Auth::user()->branch->id)->get();
            $branches = Branch::where('id', '=', Auth::user()->branch->id)->get();
        }else{
            $employees = User::where('id', '!=', Auth::user()->id)->get();
            $branches = Branch::all();
        }

        return view('admin.employees')->withTitle('Employees')->withEmployees($employees)
            ->withBranches($branches);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create()
    {
        $return_data = [];
        
        $input = Input::all();
        //dd($input);
        $step1_validator = $this->step1_validator($input);
        if( $step1_validator->fails() ){
            $return_data['status_code'] = '500';
            $errors = $validator->errors()->toArray();
        }

        $email = $input['email'];
        $password = generate_random_string(6);
        $role = get_role($input['access_level']);
        $branch = Branch::findOrFail($input['branch_id']);
        $branch_name = $branch->name;

        $user = new User;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->branch_id = $branch->id;
        $user->access_level = $input['access_level'];

        if( $user->save() )
            $res = $this->mailer->send( 'emails.register', 
                compact('email', 'password', 'role', 'branch_name'), function ($m) use ($email, $password, $role, $branch_name) {
                    $m->subject('Pharmacy Tree Registration');
                    $m->to($email);
            }); 

        return redirect(route('employees'))->withFlash_message([
            'msg' => 'An email has been sent to '.$email.'. Please tell this person to check his/her email.',
            'type' => 'success'
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        return view('admin.profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        if( Request::ajax() ){
            $input = Input::all();
            $errors = [];
            $return_data = [];

            $validator = $this->validator($input);
            if( $validator->fails() ){
                $return_data['status_code'] = '500';
                $errors = $validator->errors()->toArray();
            }

            if( empty($errors) ){
                $user = Auth::user();
                $user->fname = $input['fname'];
                $user->mname = $input['mname'];
                $user->lname = $input['lname'];
                $user->email = $input['email'];
                
                if( $user->save() )
                    $return_data['status_code'] = '200';
            }

            $return_data['errors'] = $errors;
            return json_encode($return_data);

        }else{
            return redirect('/');
        }
    }

    public function update_branch(){
        $input = Input::all();
        $branch = Branch::findOrFail($input['branch_id']);
        if( !empty($branch) )
            $user = User::findOrFail($input['id']);
            $user->branch_id = $branch->id;
            if( $user->save() )
                // let's send some emailnotificaion here
                return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );

            
    }

    protected function validator(array $data){
        // $messages (optional), use it to alter laravel's default 
        //      error messages for those specific rules.
        $messages = [
            'fname.required' => 'What is your First name?',
            'lname.required' => 'We need to know your Last name.',
            'email.required' => 'We need your valid email address.',
        ];

        $rules = [
            'fname' => 'required|max:255|min:3',
            'lname' => 'required|max:255|min:3',
            'email' => 'required|email|max:255|unique:users,id',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function password_validator(array $data){

        $messages = [
            'old_password.required' => 'Please enter your old password.',
            'new_password.required' => 'Enter your desired password.',
            'new_password_confirmation.required' => 'Please re-enter your desired password.',
            'new_password_confirmation.same' => 'Please confirm your password.'
        ];

        $rules = [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password|min:6',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function step1_validator(array $data){
        $rules = ['email' => 'required'];
        return Validator::make($data, $rules);
    }

    public function update_password(){
        if( Request::ajax() ){

            $errors = [];
            $input = Input::all();

            $password_validator = $this->password_validator($input);
            if( $password_validator->fails() ){
                $errors =  $password_validator->errors()->toArray()  ;
            }

            $return_data = [];  // structure for this variable is array( $status_code, $errors )
 
            // let's try to validate if the old password entered matches the logged in user's old password
            $credentials = [
                'email' => Auth::user()->email,
                'password' => $input['old_password']
            ];

            if( !Auth::attempt($credentials, Request::has('remember')) ){
                $errors['old_password'] = ['Your old password is not valid.'];
                $return_data['status_code']= '500';
            }
            
            if(empty($errors)){
                $user = Auth::user();
                $user->password = bcrypt($input['new_password_confirmation']);
                if( $user->save() )
                    $return_data['status_code']= '200';
            }

        }else{
            return redirect('/');
        }

        $return_data['errors'] = $errors;
        return json_encode($return_data);
    }

    /**
     * Update the user's profile photo
     * @param file $photo
     *
     * @return Response
     */
    public function update_photo(){
        if( Input::file() ){
            $image = Input::file('photo');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('images/profile-pictures/');

            // save original image
            $original_imagepath = $path.$filename; 
            Image::make($image->getRealPath())->save($original_imagepath);

            $user = Auth::user();
            $old_photo = $user->photo;

            if( !empty($old_photo) && file_exists($path.$old_photo) )
                unlink($path.$old_photo);
            

            $user->photo = $filename;
            if( $user->save() )
                return redirect()->back()->withFlash_message([
                    "msg" => "Your profile picture has been updated.", 
                    "type" => "success"
                ]);
            
            return redirect()->back()->withFlash_message([
                "msg" => "Sorry, we can't process your request right now. Please try again later.",
                "type" => "danger"
            ]);
        }
    }

    /**
     * Sets the selected branch on Session 
     *
     * @param $branch_id
     * @return Illuminate/Http/Response
     */
    public function setBranchToLogin(){
        $input = Input::all();
        session()->put('selected_branch', $input['branch_id']); 
        if (session()->get('selected_branch') > 0)
            return Redirect::to( route('dashboard') );
        return Redirect::to( '/auth/logout' );
    }

    public function dashboard(){
        $recently_added_products = Product::latest()->limit(4)->get();
        return view('dashboard')->withRecently_added_products($recently_added_products)->withTitle("Dashboard");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $id = Input::get('id');
        $user = User::findOrFail($id);

        if($user->delete())
            return json_encode( array("status" => "success") );
         
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function reactivate(){
        $id = Input::get('id');
        $user = User::withTrashed()->findOrFail($id);

        if($user->restore())
            return json_encode( array("status" => "success") );
         
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
