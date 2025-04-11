<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Like;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {

            return (new MailMessage)

                ->subject('Verify Email Address')

                ->line('Click the button below to verify your email address.')

                ->action('Verify Email Address', $url);

        });


        Gate::define('view-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        Model::preventLazyLoading();

        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }
}
