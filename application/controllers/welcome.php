<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends Private_Controller {
  public function index() {
    // Si no está logueado, redirigimos a login
    if (!@$this->user) {
      redirect ('welcome/login');
    }
    $data['titulo'] = 'Expedición - Sistema de Turnos';
    $this->load->view('cabecera', $data);
    $this->load->view('index');
  }
  
  public function login() {
    $data = array();
    // Añadimos las reglas necesarias.
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    // Generamos el mensaje de error personalizado para la acción 'required'
    $this->form_validation->set_message(
      'required',
      'El campo %s es requerido.'
     );
    // Si no esta vacio $_POST
    if (!empty($_POST)) {
      // Si las reglas se cumplen, entramos a la condicion.
      if ($this->form_validation->run() == TRUE) {
        // Obtenemos la informacion del usuario desde el modelo usuarios.
        $logged_user = $this->usuarios->get(
          $_POST['username'],
          $_POST['password']
        );
        // Si existe el usuario creamos la sesion y redirigimos al index.
        if ($logged_user) {
          $this->session->set_userdata('logged_user', $logged_user);
          redirect('welcome/index');
        } else {
          // De lo contrario se activa el error_login.
          $data['error_login'] = TRUE;
        }
      }
    }
    $data['titulo'] = 'Iniciar Sesión - Sistema de Turnos';
    $this->load->view('cabecera', $data);
    $this->load->view('login', $data);
  }
  
  public function logout() {
    $this->session->unset_userdata('logged_user');
    redirect('welcome/index');
  }

  public function pruebas() {
    $this->load->view('indexLight');
  }
}
