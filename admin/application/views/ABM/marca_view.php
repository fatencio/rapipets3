<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Marcas
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>ABM</li>
                <li><a class="link-effect" href="javascript:void(0);" onclick="return loadController('Marca/index');">Marcas</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<div class="content">
    <div class="block">
        <div class="block-header">
            <button class="btn btn-success" onclick="add_marca()"><i class="glyphicon glyphicon-plus"></i> Nueva Marca</button>
            <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar</button>
        </div>

        <div class="block-content">
            <table id="table" class="table table-bordered table-striped js-dataTable-full"  cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Animal Asignado</th>
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
            "url": "<?php echo BASE_PATH ?>/Marca/ajax_list",
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



function add_marca()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Nueva Marca'); // Set Title to Bootstrap modal title
}

function edit_marca(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo BASE_PATH ?>/Marca/ajax_edit/" + id,        
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="nombre"]').val(data.nombre);

            $.each(data.animales_view.split(","), function(i,e){
                $("#animal_asignado option[value='" + e + "']").prop("selected", true);
            });

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Marca'); // Set title to Bootstrap modal title

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
         url = "<?php echo BASE_PATH ?>/Marca/ajax_add";
     } else {
         url = "<?php echo BASE_PATH ?>/Marca/ajax_update";
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


function delete_marca(id)
{
    if(confirm('¿Eliminar Marca?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo BASE_PATH ?>/Marca/ajax_delete/" + id,
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
                <h3 class="modal-title">Marca Form</h3>
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

                        <!-- ANIMALES --> 
                        <div class="form-group">
                            <label class="control-label col-md-3">Animales</label>
                            <div class="col-md-9">
                                <select name="animal_asignado[]" id= "animal_asignado" class="form-control" multiple>                       
                                    <option value="" >Seleccione...</option>
                                   
                                    <?php foreach($animales as $animal){ ?>
                                          <option value="<?php echo $animal->id ?>"><?php echo $animal->nombre ?></option> 
                                    <?php } ?>
                                </select>  
                                <span class="help-block"></span>
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