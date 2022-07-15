<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\News\NewRepositoryInterface;
use App\Repositories\User_Reactions\UserReactionRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class NewsController extends Controller
{
    /**
     * @var UserRepositoryInterface
     * @var NewRepositoryInterface
     */
    protected $userRepository;
    protected $newRepository;
    protected $commentRepository;
    protected $userReactionRepository;

    /**
     * Constructor repository
     *
     * @param UserRepositoryInterface $userRepository
     * @param NewRepositoryInterface $newRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, NewRepositoryInterface $newRepository, CommentRepositoryInterface $commentRepository, UserReactionRepositoryInterface $userReactionRepository)
    {
        $this->newRepository = $newRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->userReactionRepository = $userReactionRepository;
    }

    /**
     * Show all news
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showNews(Request $request) {
        $news = $this->newRepository->getAll(config("const.paginate"), 'DESC');
        return view("user.news", compact('news'));
    }

    /**
     * Detail news page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function newsDetail(Request $request) {
        return view("user.detail");
    }

    /**
     * Show detail news
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function showNewsDetail(Request $request, $id) {
        $news = $this->newRepository->find($id);
        $data["user"] = Auth::user();
        $data["user"]->avatar = asset('avatar/'.$data["user"]->avatar);
        if(empty($news)) {
            return app()->make(ResponseHelper::class)->notFound(trans("auth.empty"));
        } else {
            $comments = $this->commentRepository->getAllParentCommentByNewsId($id);
            for($i = 0; $i < sizeof($comments); $i++) {
                $comments[$i]->avatar = asset('avatar/'.$comments[$i]->avatar);
                $comments[$i]->total_childrent_comment = $this->commentRepository->getTotalChildrenCommentByParentCommentId($comments[$i]->id);
            }
            $carbon = Carbon::create($news->created_at);
            $news->publish_at = $carbon->format("d-m-Y");
            $data["news"] = $news;
            $data["totalComment"] = $this->newRepository->getTotalComment($id);
            $data["comment"] = $comments;
            return app()->make(ResponseHelper::class)->success($data);
        }
    }

    /**
     * Post comment
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function postComment(Request $request, $id) {
        $data["user_id"] = $request["user"]["id"];
        $data["new_id"] = $id;
        $data["parent_id"] = 0;
        $data["content"] = $request["comment"];
        try {
            $this->commentRepository->create($data);
            return app()->make(ResponseHelper::class)->success($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Update comment
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function editComment(Request $request, $id) {
        try {
            $this->commentRepository->updateComment($request["comment"], $id);
            return app()->make(ResponseHelper::class)->success($request["comment"]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Reply comment
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function replyComment(Request $request, $id) {
        $data["user_id"] = $request["user"]["id"];
        $data["new_id"] = $request["news_id"];
        $data["parent_id"] = $id;
        $data["content"] = $request["comment"];
        try {
            $this->commentRepository->create($data);
            return app()->make(ResponseHelper::class)->success($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Show all reply comment by comment id
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */

    public function showAllReplyComment(Request $request, $id) {
        $parentComment = $this->commentRepository->find($id);
        try {
            $replyComments = $this->commentRepository->getAllReplyCommentByParentId($parentComment->id);
            foreach ($replyComments as $replyComment) {
                $carbon = Carbon::create($replyComment->created_at);
                $replyComment->pushlish_at = $carbon->format("d-m-Y");
                $replyComment->avatar = asset('avatar/'.$replyComment->avatar);
            }
            return app()->make(ResponseHelper::class)->success($replyComments);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }
}
