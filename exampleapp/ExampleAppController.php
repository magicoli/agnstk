<?php

namespace AGNSTK\Core\Http\Controllers;

class ExampleAppController extends Controller {
    
    protected $membershipService;
    
    public function __construct() {
    }
    
    public function show() {
        return sprintf(
            '<h1>%s</h1><p>%s</p><p>%s</p>',
            'Example App',
            'This is an example application for AGNSTK.',
            'It demonstrates how to create a simple controller and route in AGNSTK.'
        );  
    }
}
