<div class="content-wrapper">
  <section class="content-header">
    <h1>      
      Crear Recibo   
    </h1>
    <ol class="breadcrumb">      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>      
      <li class="active">Crear Recibo</li>   
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <!--=====================================
      EL FORMULARIO
      ======================================-->   
      <div class="col-lg-4 col-xs-12">        
        <div class="box box-success">          
          <div class="box-header with-border"></div>
          <form role="form" method="post" class="formularioVenta">
            <div class="box-body">  
              <div class="box">               

                <div class="form-group">                  
                  <div class="input-group">                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>                    
                    <select class="form-control select2" id="seleccionarCliente" name="seleccionarCliente" required>
                    <option value="">Seleccionar cliente</option>
                    <?php
                      $item = null;
                      $valor = null;
                      $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);
                       foreach ($categorias as $key => $value) {
                         echo '<option value="'.$value["idproveedor"].'">'.$value["codproveedor"].'-'.$value["razonsocial"].'</option>';
                          
                       }
                    ?>
                    </select>                    
                    <!-- <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar cliente</button></span>                   -->
                  </div>                
                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================--> 
                <div class="form-group row nuevoProducto">             
                </div>
                <input type="hidden" id="listaProductos" name="listaProductos">
                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->
                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>
                <hr>
                <div class="row">
                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->                  
                  <div class="col-xs-12 pull-left">                    
                    <table class="table">
                      <thead>
                        <tr>
                          <th>IVA</th>
                          <th>TOTAL</th>      
                        </tr>
                      </thead>
                      <tbody>                      
                        <tr>                          
                          <td style="width: 30%">                          
                            <div class="input-group">                           
                              <input type="number" class="form-control input-lg" min="0" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" value="16" readonly required>
                               <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto" required>
                               <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto" required>
                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>                       
                            </div>
                          </td>
                           <td style="width: 70%">                          
                            <div class="input-group">                           
                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                              <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="00000" readonly required>
                              <input type="hidden" name="totalVenta" id="totalVenta">                                                     
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr>

                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->

                <br>    
              </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Generar Recibo</button>
          </div>
        </form>
        <?php
          $guardarVenta = new ControladorVentas();
          $guardarVenta -> ctrCrearVenta();         
        ?>
        </div>          
      </div>

      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->
      <div class="col-lg-8 ">        
        <div class="box box-warning">
          <div class="box-header with-border"></div>
          <div class="box-body">           
            <table class="table table-bordered table-striped dt-responsive tablaVentas">              
               <thead>
                 <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 10px">No. Informe</th>
                  <th>Norma</th>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Precio</th>
                  <th style="width: 10px">Estatus</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>   
  </section>
</div>

