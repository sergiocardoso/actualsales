<?php

Class ProductController extends CI_Controller {

    protected $public_view = null;

    function __construct(){
        parent::__construct();
        $this->public_view = base_url().'/public';
    }

    function index(){

        $data = [];
        $data['public_view'] = $this->public_view;

        $this->load->view('product', $data);
    }
}