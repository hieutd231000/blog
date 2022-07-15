<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Check repository function
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->userRepository->listAll();
    }

    /**
     * Show signup form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function signupForm(Request $request)
    {
        return view("auth.user.signup");
    }

    /**
     * Process signup form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function processSignup(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'avatar' => ['required', 'image'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('signup')
                ->withInput()
                ->withErrors($validator);
        } else {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $file = $request->file("avatar");
            $fileName = $file->getClientOriginalName();
            //Resize image and insert to public
            $img = Image::make($file->getRealPath());
            $destinationPath = public_path('avatar');
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$fileName);
            //Insert to db
            try {
                $data['avatar'] = $fileName;
                $this->userRepository->create($data);
                return redirect('/login')->with('status', trans("auth.signup_success"));
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return redirect('/signup')->with('error', trans("auth.signup_failed"));
            }
        }
    }

    /**
     * Show users login form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loginForm(Request $request)
    {
        return view('auth.user.login');
    }

    /**
     * Process users login form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user_data = array(
            'email' => $request->get("email"),
            'password' => $request->get("password"),
        );

        if(Auth::attempt($user_data)) {
            if($this->userRepository->checkRole($user_data["email"]))
                return redirect("/news");
            return redirect("/login")->with("error", trans("auth.login_failed"));
        } else {
            return redirect("/login")->with("error", trans("auth.login_failed"));
        }
    }

    /**
     * Show admin login form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loginAdminForm(Request $request)
    {
        return view('auth.admin.login');
    }

    /**
     * Process admin login form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function processAdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user_data = array(
            'email' => $request->get("email"),
            'password' => $request->get("password"),
        );

        if(Auth::attempt($user_data)) {
            if(!$this->userRepository->checkRole($user_data["email"]))
                return redirect('/admin/dashboard');
            return back()->with("error", trans("auth.login_failed"));
        } else {
            return back()->with("error", trans("auth.login_failed"));
        }
    }
}
