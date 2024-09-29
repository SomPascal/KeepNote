<?php

namespace App\Filters;

use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\URI;
use Config\Auth as AuthConfig;

/**
 * Check if a visitor is allowed to visit certain 
 * routes. Redirect him if he's not allowed and
 * leave the request otherwise...
 */
class AuthFilter implements FilterInterface
{
    protected AuthConfig $authConfig;

    public function __construct()
    {
        $this->authConfig = new AuthConfig();
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
        $session = session();
        $response = Services::response();
        $disallowedRoutes = [];

        /**
         * @var \Codeigniter\HTTP\IncomingRequest $request The Request object...
         */

        if (isSignedIn())
        {
            $username = $session->get("user")["username"];
            
            $disallowedRoutes = $this->authConfig->userDisallowedRoutes;
            $redirection = route_to("account.home", $username);
        }
        else
        {
            $disallowedRoutes = $this->authConfig->visitorDisallowedRoutes;
            $redirection = (new URI(url_to("signup")))
            ->addQuery("nextPage", current_url());
        }

        foreach ($disallowedRoutes as $method => $routes) 
        {
            if ($request->is($method)) 
            {
                foreach ($routes as $route) 
                {
                    if (url_is($route)) {
                        if ($request->is("GET")) {
                            return redirect()->to($redirection);
                        }
                        else {
                            return $response->setStatusCode(
                                Response::HTTP_FORBIDDEN,
                                "You haven't the right to peform this action"
                            );
                        }
                    }
                }
            }
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

    }
}
