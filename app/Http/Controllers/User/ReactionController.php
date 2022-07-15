<?php

namespace App\Http\Controllers\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\News\NewRepositoryInterface;
use App\Repositories\Reactions\ReactionRepositoryInterface;
use App\Repositories\User_Reactions\UserReactionRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReactionController extends Controller
{
    /**
     * @var ReactionRepositoryInterface
     */
    protected $reactionRepository;
    protected $userRepository;
    protected $newRepository;
    protected $commentRepository;
    protected $userReactionRepository;


    /**
     * Constructor Repository
     *
     * @param ReactionRepositoryInterface $reactionRepository
     */
    public function __construct(ReactionRepositoryInterface $reactionRepository, UserRepositoryInterface $userRepository, NewRepositoryInterface $newRepository, CommentRepositoryInterface $commentRepository, UserReactionRepositoryInterface $userReactionRepository) {
        $this->reactionRepository = $reactionRepository;
        $this->newRepository = $newRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->userReactionRepository = $userReactionRepository;
    }

    /**
     * Show all user's reaction
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function showAllReaction(Request $request) {
        try {
            $reactions = $this->reactionRepository->getAll(config("const.paginate"), 'DESC');
            for($i = 0; $i < sizeof($reactions); $i++) {
                $reactions[$i]->avatar = asset('reaction/'.$reactions[$i]->icon);
            }
            return app()->make(ResponseHelper::class)->success($reactions);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Push reaction comment
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function pushReaction(Request $request, $id) {
        $news = $this->newRepository->find($id);
        if(empty($news)) {
            return app()->make(ResponseHelper::class)->notFound(trans("auth.empty"));
        } else {
            try {
                $reaction = $request->all();
                if($this->userReactionRepository->checkExists($reaction)) {
                    $this->userReactionRepository->create($reaction);
                    return app()->make(ResponseHelper::class)->success($reaction);
                }
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return app()->make(ResponseHelper::class)->error();
            }
        }
    }

    /**
     * Show all reaction comment
     *
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function showAllReactComment(Request $request, $id) {
        $news = $this->newRepository->find($id);
        if(empty($news)) {
            return app()->make(ResponseHelper::class)->notFound(trans("auth.empty"));
        } else {
            try {
                $reactions = $this->userReactionRepository->getAllReaction();
                for($i = 0; $i < sizeof($reactions); $i++) {
                    $reactions[$i]->avatar = asset('reaction/'.$reactions[$i]->icon);
                }
                return app()->make(ResponseHelper::class)->success($reactions);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return app()->make(ResponseHelper::class)->error();
            }
        }
    }
}
