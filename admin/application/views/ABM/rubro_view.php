<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Rubros
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>ABM</li>
                <li><a class="link-effect" href="javascript:void(0);" onclick="return loadController('Rubro/index');">Rubros</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<div class="content">
    <div class="block">
        <div class="block-header">
            <button class="btn btn-success" onclick="add_rubro()"><i class="glyphicon glyphicon-plus"></i> Nuevo Rubro</button>
            <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>
        </div>

        <div class="block-content">
            <table id="table" class="table table-bordered table-striped js-dataTable-full"  cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Raza</th>
                        <th >Tamaño</th>     
                        <th>Presentación</th>
                        <th >Marca</th>    
                        <th>Edad</th>
                        <th >Medicados</th>                                                                       
                        <th style="width:70px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>   
    </div>         
</div>

<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo BASE_PATH ?>/Rubro/ajax_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });


    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_rubro()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nuevo Rubro'); // Set Title to Bootstrap modal title
}

function edit_rubro(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo BASE_PATH ?>/Rubro/ajax_edit/" + id,        
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="nombre"]').val(data.nombre);
           
            contamanios = (data.contamanios == '1' ? true : false);
            $('[name="contamanios"]').prop('checked', contamanios);

            conraza = (data.conraza == '1' ? true : false);     
            $('[name="conraza"]').prop('checked', conraza);   

            conpresentacion = (data.conpresentacion == '1' ? true : false);
            $('[name="conpresentacion"]').prop('checked', conpresentacion);

            conmarca = (data.conmarca == '1' ? true : false);     
            $('[name="conmarca"]').prop('checked', conmarca);   

            conedad = (data.conedad == '1' ? true : false);
            $('[name="conedad"]').prop('checked', conedad);

            conmedicados = (data.conmedicados == '1' ? true : false);     
            $('[name="conmedicados"]').prop('checked', conmedicados);   
                                    
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Rubro'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al cargar datos');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('Guardando...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;


    if(save_method == 'add') {
        url = "<?php echo BASE_PATH ?>/Rubro/ajax_add";
    } else {
        url = "<?php echo BASE_PATH ?>/Rubro/ajax_update";
    }


    // ajax adding data to database
    $.ajax({
        url : url ,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data ' + textStatus);
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_rubro(id)
{
    if(confirm('¿Eliminar Rubro?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo BASE_PATH ?>/Rubro/ajax_delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Rubro Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <!-- NOMBRE --> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Nombre</label>
                            <div class="col-md-9">
                                <input name="nombre" placeholder="Nombre" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>                    
                        <div class="row">
                            <div class="col-md-6">

                                <!-- CON RAZA --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Raza</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="conraza"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- CON TAMANIOS --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Tama&ntilde;o</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="contamanios"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- CON PRESENTACION --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Presentación</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="conpresentacion"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-md-6">

                                <!-- CON MARCA --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Marca</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="conmarca"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- CON EDAD --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Edad</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="conedad"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- CON MEDICADOS --> 
                                <div class="form-group">
                                    <label class="control-label col-md-6">Con Medicados</label>
                                    <div class="col-md-6">
                                        <label class="css-input switch switch-info">
                                            <input type="checkbox" name="conmedicados"><span></span>
                                        </label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>  
                            </div>
                        </div>                                             
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->    