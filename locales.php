<?php
    require 'config/config.php';
    include('includes/header.php');
?>

<div class="banner-form " id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de locales</h3>
      </div>
    </div>
</div>

<table class="table text-center fadeIn fast" id="tablaLocales">
    <thead class="thead-light">
      <tr>
        <th scope="col">Local</th>
        <th scope="col">Estado</th>
        <th scope="col">
            <?php if($_SESSION['admin'] == 1){?>
                <button class="btn btn-outline-info" id="botonAgregar">Nuevo local!</button> 
            <?php }; ?>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
            
    </tbody>
</table><!--tabla productos-->


<div class="modal fade" id="modal" style="overflow-y:scroll"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 50% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Local</h5>
                    <button type="button" class="close" onclick="document.getElementById('modal').style.display='none'"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form class="form-group" id="form-local">
                            <input type="hidden" id="idLocal" name="idLocal"/>
                            <div class="row my-4">
                                <div class="col-12 col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Local</div>
                                        </div>
                                        <input type="text" name="nombre" class="form-control" id="nombre"/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Estado</div>
                                        </div>
                                        <select class="form-control" name="estado" id="estado">
                                            <option value="1">Activo</option>
                                            <option value="0">Suspendido</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-info btn-block"/>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
</div>

<div id="loader" class="d-none container__slider">
    <div class="sk-chase">
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    <div class="sk-chase-dot"></div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="js/locales.js"></script>