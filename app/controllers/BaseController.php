<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: BaseController
 * 
 * Automatically generated via CLI.
 */
class BaseController extends Controller {
    public function __construct()
    {
        parent::__construct();
    }
    protected function model($model_name) {
        $variable_name = strtolower($model_name) . '_instance';
        // Note: Changed ->load->model() to ->call->model() based on framework code
        $this->$variable_name = $this->call->model($model_name);
        return $this->$variable_name;
    }

    protected function view($view_path, $data = []) {
         // Note: Changed ->load->view() to ->call->view() based on framework code
        $this->call->view($view_path, $data);
    }
}