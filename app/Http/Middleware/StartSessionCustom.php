<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class StartSessionCustom extends StartSession
{

    protected Request $request;

    public function handle($request, Closure $next)
    {
        $this->request = $request;
        if (! $this->sessionConfigured() || !$request->has('session_token')) {
            return $next($request);
        }

        $session = $this->getSession($request);

        if ($this->manager->shouldBlock() ||
            ($request->route() instanceof Route && $request->route()->locksFor())) {
            return $this->handleRequestWhileBlocking($request, $session, $next);
        }

        return $this->handleStatefulRequest($request, $session, $next);
    }

    protected function handleStatefulRequest(Request $request, $session, Closure $next)
    {
        // If a session driver has been configured, we will need to start the session here
        // so that the data is ready for an application. Note that the Laravel sessions
        // do not make use of PHP "native" sessions in any way since they are crappy.
        $request->setLaravelSession(
            $this->startSession($request, $session)
        );

        $this->collectGarbage($session);

        $response = $next($request);

        $this->storeCurrentUrl($request, $session);

        $this->addCookieToResponse($response,  $session);

        // Again, if the session has been configured we will need to close out the session
        // so that the attributes may be persisted to some storage medium. We will also
        // add the session identifier cookie to the application response headers now.
        $this->saveSession($request);

        return $response;

    }

    protected function addCookieToResponse(Response $response, Session $session)
    {
        $config = $this->manager->getSessionConfig();
        $cookie = new Cookie(
            $session->getName(), $this->request->get('session_token'), $this->getCookieExpirationDate(),
            $config['path'], $config['domain'], $config['secure'] ?? false,
            $config['http_only'] ?? true, false, $config['same_site'] ?? null
        );
        if ($this->sessionIsPersistent($config)) {
            $response->headers->setCookie($cookie);
        }
    }
}
