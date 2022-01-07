<?php

namespace App\Exceptions;

use Closure;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ValidationException::class,
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Record Log on Exception
        $recordExceptionLog = true;
        if($recordExceptionLog){
            $domain = $request->getHost();
            $url = preg_replace("(^https?://)", "", url()->current());
            
            \Log::alert("Debug on Exception Render (Gathering Information) - ".date("d F, Y / H:i:s"), [
                'env' => env('APP_ENV'),
                'domain' => $domain,
                'url' => $url,
                'request' => [
                    $request->url(), // Current request URL
                    $request->fullUrl(), // With query parameters
                    // $request->route() // Get the route closure for this request path
                ],
                'exception' => class_basename($e),
            ]);
        }

        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceOf Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($this->mapException($e));

        foreach ($this->renderCallbacks as $renderCallback) {
            if (is_a($e, $this->firstClosureParameterType($renderCallback))) {
                $response = $renderCallback($e, $request);

                if (! is_null($response)) {
                    return $response;
                }
            }
        }

        $fullUrl = $request->fullUrl();
        if(strpos($fullUrl, 'assets') === false){
            // Check Route Name
            if($request->route()){
                $routeName = $request->route()->getName();
                if (strpos($routeName, 'datatable') !== false) {
                    if ($e instanceOf AuthenticationException) {
                        $datatableData = [];
    
                        return datatables()
                            ->of($datatableData)
                            ->with('datatable', [
                                'message' => 'unauthenticated'
                            ])
                            ->toJson();
                    }
                }
            }
        }

        if ($e instanceOf HttpResponseException) {
            return $e->getResponse();
        } else if ($e instanceOf AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } else if ($e instanceOf ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $request->expectsJson()
                    ? $this->prepareJsonResponse($request, $e)
                    : $this->prepareResponse($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $domain = $request->getHost();
        $url = preg_replace("(^https?://)", "", url()->current());

        \Log::debug("[System] Check Unauthenticated Handler Method ~ App\Exceptions\Handler@unauthenticated", [
            'domain' => $domain,
            'url' => $url,
            'ua' => getUserAgent()
        ]);

        if($request->expectsJson()){
            return response()->json(['message' => $exception->getMessage()], 401);
        } else {
            if($domain == config('custom.admin.route') || strpos($url, config('custom.admin.route')) !== false){
                return redirect()->route(config('custom.admin.route').'.login');
            } else if($domain == config('custom.client.route') || strpos($url, config('custom.client.route')) !== false){
                return redirect()->route(config('custom.client.route').'.login');
            }
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
