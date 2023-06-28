<?php namespace RainLab\User\Components;

use Auth;
use Lang;
use Event;
use Flash;
use Request;
use Redirect;
use PassageService;
use SystemException;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use KurtJensen\Passage\Models\Key;
use RainLab\User\Models\UserGroup;
use KurtJensen\Passage\Models\Variance;


/**
 * Session component
 *
 * This will inject the user object to every page and provide the ability for
 * the user to sign out. This can also be used to restrict access to pages.
 */
class Session extends ComponentBase
{
    const ALLOW_ALL = 'all';
    const ALLOW_GUEST = 'guest';
    const ALLOW_USER = 'user';

    public function componentDetails()
    {
        return [
            'name'        => 'rainlab.user::lang.session.session',
            'description' => 'rainlab.user::lang.session.session_desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'security' => [
                'title'       => 'rainlab.user::lang.session.security_title',
                'description' => 'rainlab.user::lang.session.security_desc',
                'type'        => 'dropdown',
                'default'     => 'all',
                'options'     => [
                    'all'   => 'rainlab.user::lang.session.all',
                    'user'  => 'rainlab.user::lang.session.users',
                    'guest' => 'rainlab.user::lang.session.guests'
                ]
            ],
            'allowedUserGroups' => [
                'title'       => 'rainlab.user::lang.session.allowed_groups_title',
                'description' => 'rainlab.user::lang.session.allowed_groups_description',
                'placeholder' => '*',
                'type'        => 'set',
                'default'     => []
            ],
            'allowedUserKeys' => [
                'title'       => 'rainlab.user::lang.session.allowed_keys_title',
                'description' => 'rainlab.user::lang.session.allowed_keys_description',
                'placeholder' => '*',
                'type'        => 'set',
                'default'     => ''
            ],
            'redirect' => [
                'title'       => 'rainlab.user::lang.session.redirect_title',
                'description' => 'rainlab.user::lang.session.redirect_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ]
        ];
    }

    /**
     * getRedirectOptions
     */
    public function getRedirectOptions()
    {
        return [''=>'- none -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * getAllowedUserGroupsOptions
     */
    public function getAllowedUserGroupsOptions()
    {
        return UserGroup::lists('name','code');
    }

    /**
     * Component is initialized.
     */
    public function init()
    {
        // Inject security logic pre-AJAX
        $this->controller->bindEvent('page.init', function() {
            if (Request::ajax() && ($redirect = $this->checkUserSecurityRedirect())) {
                return ['X_OCTOBER_REDIRECT' => $redirect->getTargetUrl()];
            }
        });
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        // dump($this->property('allowedUserGroups'));
        //exit;
        // $keys = Key::query()->get(['name'])->toJson();

        // $keys = Key::query()->groups->get(['name']);
        // dd($this->property('allowedUserGroups'), $keys);
        // foreach($keys as $key) {
        //     dump($key->name);
        //     // if($this->property('allowedUserKeys') in ) {

        //     //    dump($this->property('allowedUserKeys'), $key['name']);
        //     // }

        // }
        // exit;

        if ($redirect = $this->checkUserSecurityRedirect()) {
            return $redirect;
        }

        $this->page['user'] = $this->user();
    }

    /**
     * Returns the logged in user, if available, and touches
     * the last seen timestamp.
     * @return RainLab\User\Models\User
     */
    public function user()
    {
        if (!$user = Auth::getUser()) {
            return null;
        }

        if (!Auth::isImpersonator()) {
            $user->touchLastSeen();
        }

        return $user;
    }

    /**
     * Returns the previously signed in user when impersonating.
     */
    public function impersonator()
    {
        return Auth::getImpersonator();
    }

    /**
     * Log out the user
     *
     * Usage:
     *   <a data-request="onLogout">Sign out</a>
     *
     * With the optional redirect parameter:
     *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
     *
     */
    public function onLogout()
    {
        $user = Auth::getUser();

        Auth::logout();

        if ($user) {
            Event::fire('rainlab.user.logout', [$user]);
        }

        $url = post('redirect', Request::fullUrl());

        Flash::success(Lang::get('rainlab.user::lang.session.logout'));

        return Redirect::to($url);
    }

    /**
     * If impersonating, revert back to the previously signed in user.
     * @return Redirect
     */
    public function onStopImpersonating()
    {
        if (!Auth::isImpersonator()) {
            return $this->onLogout();
        }

        Auth::stopImpersonate();

        $url = post('redirect', Request::fullUrl());

        Flash::success(Lang::get('rainlab.user::lang.session.stop_impersonate_success'));

        return Redirect::to($url);
    }

    /**
     * checkUserSecurityRedirect will return a redirect if the user cannot access the page.
     */
    protected function checkUserSecurityRedirect()
    {
        // No security layer enabled
        if ($this->checkUserSecurity()) {
            return;
        }

        if (!$this->property('redirect')) {
            throw new SystemException('Redirect property is empty on Session component.');
        }

        $redirectUrl = $this->controller->pageUrl($this->property('redirect'));

        return Redirect::guest($redirectUrl);
    }

    /**
     * checkUserSecurity checks if the user can access this page based on the security rules.
     */
    protected function checkUserSecurity(): bool
    {
        $allowedGroup = $this->property('security', self::ALLOW_ALL);
        $allowedUserGroups = (array) $this->property('allowedUserGroups', []);
        $allowedUserKeys = $this->property('allowedUserKeys', '');

        $isAuthenticated = Auth::check();
        $userGroups = [];
        if($isAuthenticated) {
            $userGroups = Auth::getUser()->groups->lists('code');
        }

        // // Passage Service Methods can be accessed in one of two ways:

        $permission_keys_by_name = app('PassageService')::passageKeys(); // все ключи пользователя (из двух БД)
        $permission_groups_by_name = app('PassageService')::passageGroups(); // все группы с ключами в которые входит пользователь
        // $permission_keys_by_name = app('PassageService')::hasGroupName('managers-prod');
        $user_id = null;
        if($isAuthenticated){
            $user_id = Auth::getUser()->id;
        }
        $user_in_variance = Variance::query()->where('user_id', $user_id)->first();
        $user_id_in_variance = $user_in_variance ? $user_in_variance->toArray()['user_id'] : null;

        // dump($allowedGroup,
        //     $allowedUserGroups,
        //     $allowedUserKeys,
        //     $isAuthenticated,
        //     $userGroups,
        //     $permission_keys_by_name,
        //     $permission_groups_by_name,
        //     Auth::getUser()->id,
        //     // Variance::query()->where('user_id', Auth::getUser()->id,)->first()->toJson(),
        //     // "{"id":2,"user_id":3,"key_id":2,"grant":1,"check_one":0,"description":"personal token","created_at":...}"
        //     $user_in_variance, // 3
        //     $user_id_in_variance

        // );
        // // 'user', [0=> 0 => "managers-prod" 1 => "admins"], "salestoken", true
        // exit;


        if ($isAuthenticated) {
            if ($allowedGroup == self::ALLOW_GUEST) {
                return false;
            }


            if (!empty($allowedUserGroups)) {
                $userGroups = Auth::getUser()->groups->lists('code');
                if (!count(array_intersect($allowedUserGroups, $userGroups))) {

                    if ($allowedUserKeys != '') {
//                      $keys = app('PassageService')::passageKeys(); // Get all permision keys for the user in an array
                        $keys = PassageService::passageKeys(); // Using Alias Get all permision keys for the user in an array
                        // dump($keys);

                        if (in_array($allowedUserKeys, $keys) && $user_id === $user_id_in_variance) {
                            return true;
                        }
                    }
                    return false;
                }
            }

        }
        else {
            if ($allowedGroup == self::ALLOW_USER) {
                // dd('ELSE', self::ALLOW_USER);
                return false;
            }
        }

        return true;
    }
}
