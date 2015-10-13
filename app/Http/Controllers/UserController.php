<?php
namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;
use Auth;
use Validator;
use Redirect;
use Image;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\User;

class UserController extends Controller
{

    function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
       $user = new User;
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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

    protected function validator(array $data){
        // $messages (optional), use it to alter laravel's default 
        // error messages for those specific rules.
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

            // return json_encode($return_data);
        }else{
            return redirect('/');
        }

        $return_data['errors'] = $errors;
        return json_encode($return_data);
    }

    /**
     * Update the user's profile photo
     * @param file $photo
     * @return Response
     */
    public function update_photo(){
        if( Input::file() ){
            $image = Input::file('photo');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            // save original image
            $original_imagepath = public_path('images/profile-pictures/' . $filename);
            Image::make($image->getRealPath())->save($original_imagepath);

            // create image 160x160 size
                // $path = public_path('profile-pictures/160x160/' . $filename);
                // Image::make($original_imagepath)->resize(160, 160)->save($path);

            // create image for 128x128 size
                // $path = public_path('profile-pictures/128x128/' . $filename);
                // Image::make($original_imagepath)->resize(128, 128)->save($path);

            $user = Auth::user();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
