<?php

namespace App\Http\Controllers\Admin;

use Image;
use App\Http\Controllers\Controller;
use App\Http\Validations\Validation;
use App\Repositories\News\NewRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    /**
     * @var NewRepositoryInterface
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    protected $newRepository;

    public function __construct( UserRepositoryInterface $userRepository, NewRepositoryInterface $newRepository)
    {
        $this->userRepository = $userRepository;
        $this->newRepository = $newRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $news = $this->newRepository->getAll(config("const.paginate"), "DESC");
        return view("admin.page.news.index", compact("news"));
    }

    /**
     * Delete news function
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function destroy(Request $request, $id)
    {
        $new = $this->newRepository->find($id);
        if(empty($new)) {
            return redirect()->back()->with("failed", trans("auth.empty"));
        }
        try {
            $this->newRepository->delete($new->id);
            return redirect()->back()->with("success", trans("auth.delete.success"));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with("failed", trans("auth.delele.failed"));
        }
    }

    /**
     * Create News Form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createForm(Request $request)
    {
        return view("admin.page.news.create");
    }

    /**
     * Store news
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store(Request $request)
    {
        Validation::newsValidation($request);
        $file = $request->file("photo");
        if($file->getClientOriginalExtension() != 'png' && $file->getClientOriginalExtension() != 'jpg') {
            return redirect()->back()->with('failed', trans("auth.add.failed"));
        }
        try {
            $fileName = $file->getClientOriginalName();
            //Resize image
            $img = Image::make($file->getRealPath());
            $destinationPath = public_path('thumbnail');
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$fileName);
            //Insert to public
            $file->move(public_path("image/"), $fileName);

            //Insert to db
            $data = $request->all();
            if($request->state == config("const.state")[0]) {
                $data['state'] = 0;
            } else {
                $data['state'] = 1;
            }
            $data["image"] = $fileName;
            $data["thumbnail"] = $fileName;
            $this->newRepository->create($data);
            return redirect("admin/news")->with("success", trans("auth.add.success"));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with("failed", trans("auth.add.failed"));
        }
    }

    /**
     * Show edit form
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id) {
        $new = $this->newRepository->find($id);
        if(empty($new)) {
            return redirect()->back()->with("failed", trans("auth.empty"));
        }
        return view("admin.page.news.edit", compact("new"));
    }

    /**
     * Update news
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, $id) {
        Validation::newsUpdateValidation($request);
        $data = $request->all();
        try {
            $file = $request->file("photo");
            if(!empty($file)) {
                if($file->getClientOriginalExtension() != 'png' && $file->getClientOriginalExtension() != 'jpg') {
                    return redirect()->back()->with('failed', trans("auth.add.failed"));
                }
                //Delete image in public
                $new = $this->newRepository->find($id);
                $imagePath = "image/". $new->image;
                if(file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $thumbnailPath = "thumbnail/". $new->image;
                if(file_exists(public_path($thumbnailPath))) {
                    unlink(public_path($thumbnailPath));
                }
                //Resize image
                $fileName = $file->getClientOriginalName();
                $img = Image::make($file->getRealPath());
                $destinationPath = public_path('thumbnail');
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$fileName);
                //Insert to public
                $file->move(public_path("image/"), $fileName);
                $data["image"] = $fileName;
                $data["thumbnail"] = $fileName;
            }

            if($request->state == config("const.state")[0]) {
                $data['state'] = 0;
            } else {
                $data['state'] = 1;
            }
            $this->newRepository->update($data, $id);
            return redirect("admin/news")->with("success", trans("auth.edit.success"));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with("failed", trans("auth.edit.failed"));
        }
    }
}
