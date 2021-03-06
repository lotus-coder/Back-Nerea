<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_devoluciones extends CI_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->model('m_libros');  //$this->m_libros disponible

        }
	
	public function index()
        {
            
            $this->load->view('v_cabecera');
            $this->load->view('v_login');
            $this->load->view('v_pie');
            
	}
        
        
        public function login(){
            
            if (!isset($_POST['submitlogin']))
                $this->index();
            else{
                $dni=$_POST['dni'];
                $pass=$_POST['pass'];
                
                $empleado=$this->m_libros->login($dni,$pass);
                //$empleado es null si login mal
                
                if ($empleado==null){
                    $this->session->set_flashdata('error',"Login erroneo!");
                    $this->index();                    
                }
                else{
                    $this->session->set_userdata('empleado',$empleado);
                    $this->prestamos();
                }
            }
        }
        
        function prestamos(){
            
             //echo "login ok" .$this->session->userdata('empleado')->nombre;
            if (! $this->session->userdata('idsprestamos'))
                $this->session->set_userdata('idsprestamos',array());
            
            $this->load->view('v_cabecera');     
            
            $datos['prestamos']=$this->m_libros->prestamos2();            
            $this->load->view('v_prestamos',$datos);        
            
            $this->load->view('v_pie');
        }
        
        function anotarDevolucion($idprestamo){
            
            $ids=$this->session->userdata('idsprestamos');
            if (!in_array($idprestamo,$ids))
                $ids[]=$idprestamo;
            $this->session->set_userdata('idsprestamos',$ids);
            
            $this->prestamos();
            
        }
        
        
        function grabarDevoluciones(){
            $devueltos=$this->m_libros->devolverPrestamos($this->session->userdata('idsprestamos'));
            $this->session->set_flashdata('rtdodevolucion',"$devueltos pr??stamos devueltos"); 
            $this->session->unset_userdata('idsprestamos');
            
            //$this->prestamos();
            redirect('c_devoluciones/prestamos');
            
        }
        
    
        
        
        
        
        
}
