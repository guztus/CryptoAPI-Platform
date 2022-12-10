<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Template;

class ProfileController
{
    public function showForm()
    {
        if (empty($_SESSION['auth_id'])) {
            return Redirect::to('/login');
        }
        return Template::render('profile/profile.view.twig');
    }
}