<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Template;

class ProfileController
{
    public function showForm()
    {
        if (empty($_SESSION['auth_id'])) {
            return new Redirect('/login');
        }
        return new Template('profile/profile.view.twig');
    }
}