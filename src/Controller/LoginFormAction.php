<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction
{

    public function execute()
    {
        if(isset($_SESSION['login'])) {
            header('Location: /');
        }
        require __DIR__ . '/../view/login.phtml';
    }
}