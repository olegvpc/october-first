<?php namespace Olegvpc\Contact;

use Backend;
use System\Classes\PluginBase;

/**
 * feeds Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Contact Form from Oleg',
            'description' => 'A simple contact form plug-in',
            'author'      => 'olegvpc',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Olegvpc\Contact\Components\ContactForm' => 'contactForm',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'olegvpc.contact.some_permission' => [
                'tab' => 'contact',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'feeds' => [
                'label'       => 'Contact',
                'url'         => Backend::url('olegvpc/contact/contacts'),
                'icon'        => 'icon-spinner',
                'permissions' => ['olegvpc.contact.*'],
                'order'       => 500,
            ],
        ];
    }
}
