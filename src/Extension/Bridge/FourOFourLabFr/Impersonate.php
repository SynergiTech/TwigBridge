<?php

namespace TwigBridge\Extension\Bridge\FourOFourLabFr;

use Twig_Extension;
use Twig_Function;

/**
 * @see https://github.com/404labfr/laravel-impersonate
 */
class Impersonate extends Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_FourOFourLabFr_Impersonate';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_Function('impersonating', function () {
                if (auth()->check()) {
                    return auth()->user()->isImpersonated();
                }
                return false;
            }),
            new Twig_Function('canImpersonate', function () {
                if (auth()->check()) {
                    return auth()->user()->canImpersonate();
                }
                return false;
            }),
            new Twig_Function('canBeImpersonated', function ($userid) {
                $model = config('auth.providers.users.model');

                $user = ($userid instanceof $model) ? $userid : call_user_func([
                    $model,
                    'findOrFail'
                ], $userid);

                if (auth()->check() && auth()->user()->id != $user->id) {
                    return $user->canBeImpersonated();
                }

                return false;
            }),
        ];
    }
}
