<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     *
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * View all user function
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $data = $this->userRepository->getAll(config("const.paginate"), "DESC");
        return view("admin.page.users.index", compact("data"));
    }

    public function destroy(Request $request)
    {
        $user = $this->userRepository->find($request->id);
        if(empty($user)) {
            return redirect()->back()->with("failed", trans("auth.empty"));
        }

        try {
            $email = $this->userRepository->splitUserEmail($request->email);
            if(Storage::exists($email)) {
                Storage::deleteDirectory($email);
            } else {
                Log::error(trans("auth.empty"));
            }
            $this->userRepository->delete($user->id);
            return redirect()->back()->with("success", trans("auth.delete.success"));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with("failed", trans("auth.delete.failed"));
        }
    }
}
