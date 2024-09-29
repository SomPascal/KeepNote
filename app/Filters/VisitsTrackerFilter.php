<?php

namespace App\Filters;

use App\Entities\VisitorEntity;
use App\Models\VisitorModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\Auth;
use Config\Services;

class VisitsTrackerFilter implements FilterInterface
{

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

        helper(['text', 'auth']);

        $session = session();

        if (! ($session->has('visitor'))) 
        {
            $data = 
            [
                'ip' => $request->getIPAddress(),
                'ua' => (string) $request->getUserAgent(),
                'accept_lang' => $request->getHeader('Accept-Language'),
                'created_at' => Time::now()->format('Y-m-d H:i:s')
            ];
            $data['id'] = (isSignedIn() === true) ? $session->get('user')['id'] : random_string();
            

            $visitor = VisitorEntity::setAll($data);
            $visitor_model = model(VisitorModel::class);

            $logger = Services::logger();
            $session->set('visitor', $data);

            if ((new Auth)->visitTracker == true)
            {
                try 
                {
                    if ($visitor_model->insert($visitor, false) === false) 
                    {
                        $logger->error('Attempt to records visitor\'s data to the db failed');
                        return;
                    }
                } catch (DatabaseException $de) 
                {
                    $logger->error($de->getMessage());
                    return;
                }
                catch (DataException $de)
                {
                    $logger->error($de->getMessage());
                    return;
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
        //
    }
}
