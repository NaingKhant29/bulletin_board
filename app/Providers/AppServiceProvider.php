<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Interface\Dao\Post\PostDaoInterface;
use App\Dao\Post\PostDao;
use App\Interface\Service\Post\PostServiceInterface;
use App\Service\Post\PostService;
use App\Interface\Dao\User\UserDaoInterface;
use App\Dao\User\UserDao;
use App\Interface\Service\User\UserServiceInterface;
use App\Service\User\UserService;
use App\Interface\Dao\Comment\CommentDaoInterface;
use App\Dao\Comment\CommentDao;
use App\Interface\Service\Comment\CommentServiceInterface;
use App\Service\Comment\CommentService;
use App\Interface\Dao\Reaction\ReactionDaoInterface;
use App\Dao\Reaction\ReactionDao;
use App\Interface\Service\Reaction\ReactionServiceInterface;
use App\Service\Reaction\ReactionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(
            CommentServiceInterface::class, 
            CommentService::class
        );

         $this->app->bind(
            CommentDaoInterface::class, 
            CommentDao::class
        );
        $this->app->bind(
            ReactionServiceInterface::class, 
            ReactionService::class
        );

         $this->app->bind(
            ReactionDaoInterface::class, 
            ReactionDao::class
        );
        $this->app->bind(
            UserDaoInterface::class, 
            UserDao::class
        );
        
        $this->app->bind(
            PostDaoInterface::class, 
            PostDao::class
        );

        // // Service Registration (Interface to Implementation)
        // $this->app->bind(
        //     AuthServiceInterface::class, 
        //     AuthService::class
        // );
        
        $this->app->bind(
            UserServiceInterface::class, 
            UserService::class
        );
        
        $this->app->bind(
            PostServiceInterface::class, 
            PostService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Increase PHP configuration for handling large file uploads
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        
        // Use Bootstrap for pagination
        Paginator::useBootstrap();
    }
}
