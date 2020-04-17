<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var \Snowdog\DevTest\Model\User
     */
    private $user;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        if(isset($_SESSION['login'])) {
            $this->user = $this->userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        $ip = $_POST['ip'];
        if(!empty($ip)){
            if($this->varnishManager->create($this->user, $ip)){
                $_SESSION['flash'] = 'Varnish ' . $ip . ' added!';
            }
        }else{
            $_SESSION['flash'] = 'Ip is empty!';
        }

        header('Location: /varnish');
    }
}