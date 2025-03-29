<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Parent1;
use App\Policies\Parent1Policy;
use App\Models\Tutor;
use App\Policies\TutorPolicy;
use App\Models\Class1;
use App\Policies\Class1Policy;
use App\Models\Approve;
use App\Policies\ApprovalPolicy;
use App\Models\Rate;
use App\Policies\RatePolicy;
use App\Models\Report;
use App\Policies\ReportPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Class1::class => Class1Policy::class,
        Parent1::class => Parent1Policy::class,
        Approve::class => ApprovalPolicy::class,
        Rate::class => RatePolicy::class,
        Report::class => ReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
