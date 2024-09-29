<?php

namespace App\Filters;

use App\Entities\RememberMe;
use App\Entities\User;
use App\Entities\VisitorEntity;
use App\Models\RememberMeModel;
use App\Models\VisitorModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\UserAgent;
use Config\RememberMeCookie;

class RememberMeFilter implements FilterInterface
{
    public function areSameUserAgent(UserAgent $ua_1, UserAgent $ua_2): bool
    {
        return 
        $ua_1->getPlatform() == $ua_2->getPlatform() &&
        $ua_1->getBrowser() == $ua_2->getBrowser();
    }
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        /**
         * @var \Codeigniter\HTTP\IncomingRequest $request The Request object...
         */
        helper("cookie");

        if (isSignedIn()) return;
        
        
        $rememberMeConfig = new RememberMeCookie();
        $rememberMe = new RememberMe();
        $rememberMeModel = new RememberMeModel();
        $visitorModel = new VisitorModel();
        $rememberMe->token = get_cookie($rememberMeConfig->name, true);
        
        if (isset($rememberMe->token) && $rememberMeModel->exist(["token" => $rememberMe->token]))
        {
            $user = User::setAll($rememberMeModel->getUserFromToken($rememberMe->token));
            login($user);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
