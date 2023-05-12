<?php

namespace App\Exceptions;

use App\Models\Site;
use App\Models\Template;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                $domain = domain();
                //$domain = "hotpaginas.nl";
                $site = Site::get_info($domain);
                try {
                    $template = Template::getTemplateName($site->id);
                    switch ($template->slug) {
                        case 'datingtemplateprofile1':
                            return response()->view('errors.datingTemplateProfile1.404', [], 404);
                            break;
                        
                        default:
                            return response()->view('errors.404', [], 404);
                            break;
                    }
                } catch (\Throwable $th) {
                    return response()->view('errors.404', [], 404);
                }
            }
        }

        return parent::render($request, $exception);
    }
}
