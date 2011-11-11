<?php

require_once '/../control/controlProducto.php';
require_once '/../control/controlPedido.php';
require_once '/../control/carrito.php';
require_once '../control/controlUsuario.php';

class view {

    public function run() {
        $control = new controlProducto();
        $controlPedido = new controlPedido();
        $carro = new carrito();
        $usuario = new controlUsuario;
        if (!isset($_POST['iniciar'])) {
            if(isset($_POST['seleccionar'])){
            $id = $_POST['chompas'];
                     $item = $control->buscarById($id);
                     $cantidad = $_POST['cantidad'];
                    if ($cantidad == null) {
                        echo 'Ingresar una Cantidad';
                        } else {
                $cantidadActual = $item->get_stock();
                $nuevaCantidad = $cantidadActual - $cantidad;
                $control->modificar($id, $nuevaCantidad);
                if($control->fueraDeStock($id, $nuevaCantidad)){
                    $controlPedido->ingresar(2, '2011-11-11', $id);
                    $cantidadAPedir = $controlPedido->hacerPedido($id);
                    $cantidadActual = $item->get_stock();
                    $nuevaCantidad1 = $nuevaCantidad + $cantidadAPedir;
                    $control->modificar($id, $nuevaCantidad1);
                }else{
                   $cantidadAPedir = 0;
                }
                $pedidos = $controlPedido->listar();
                $carro->agregarItem($item, $cantidad);
                $items = $carro->getCarro();
                $cantidades = $carro->getCantidad();
                $this->_mostrarCarro($items, $cantidades, $pedidos, $cantidadAPedir);
            }
                }
                else{
            $this->_mostrarPrincipal();
                }
        }       else{
                $users = $_POST['usuario'];
                $contra = $_POST['contrasenha'];
                $user = $usuario->verificar($users, $contra);
                    $this->_mostrarAdmin();          
        }
    
        
        if(isset($_GET['op'])){    
            $op = $_GET['op'];
            switch($op){
                   case 'volver':
                       header('location:view.php');
                       break;
                   case'iniciarSession':
                    $this->_mostrarInicio();
                    break;
                    case 'prod':
                    $lista= $control->listar();
                    $this->_mostrarProductos($lista);
                    break;
                    case 'ped':
                        $lista= $controlPedido->listar();
                        $this->_mostrarPedidos($lista);
                        break;
            }
        }
    }
            
    private function _mostrarPedidos($lista){
        require_once 'pedidos.html';
    }
    private function _mostrarProductos($prod){
        require_once 'productos.html';
    }

    private function _mostrarInicio() {
        require_once 'IniciarSesion.html';
    }

    private function _mostrarAdmin() {
        require_once 'principalAdmin.html';
    }



private function _mostrarCarro($items, $cantidades, $pedidos, $cantidadAPedir) {
        require_once 'carrito.html';
    }

    private function _mostrarPrincipal() {
        require_once 'principal.html';
    }

}

$mi = new view();
$mi->run();
?>