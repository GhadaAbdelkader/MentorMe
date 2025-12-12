<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('user.admin.user-management', \Modules\User\Livewire\Admin\UserManagement::class);
        Livewire::component('user.admin.user-profile-viewer', \Modules\User\Livewire\Admin\UserProfileViewer::class);
        Livewire::component('user.admin.admin-profile', \Modules\User\Livewire\Admin\AdminProfile::class);
        Livewire::component('user.profile.profile-photo-manager', \Modules\User\Livewire\Profile\ProfilePhotoManager::class);
        Livewire::component('user.profile.update-password-form', \Modules\User\Livewire\Profile\UpdatePasswordForm::class);
        Livewire::component('user.profile.delete-user-form', \Modules\User\Livewire\Profile\DeleteUserForm::class);
        Livewire::component('user.auth.verify-email', \Modules\User\Livewire\Auth\VerifyEmail::class);
        Livewire::component('user.actions.logout', \Modules\User\Livewire\Actions\Logout::class);
        Livewire::component('user.layout.navigation', \Modules\User\Livewire\Layout\Navigation::class);
        Livewire::component('user.mentee.mentee-profile', \Modules\User\Livewire\Mentee\MenteeProfile::class);
        Livewire::component('user.shared.profile-completion', \Modules\User\Livewire\Shared\ProfileCompletion::class);
        Livewire::component('user.mentor.mentor-profile', \Modules\User\Livewire\Mentor\MentorProfile::class);
        Livewire::component('user.session.create-session', \Modules\User\Livewire\Session\CreateSession::class);
        Livewire::component('user.session.edit-session', \Modules\User\Livewire\Session\ModifySession::class);
        Livewire::component('user.session.session', \Modules\User\Livewire\Session\Sessions::class);
        Livewire::component('user.session.show-session', \Modules\User\Livewire\Session\ShowSession::class);

    }
}
