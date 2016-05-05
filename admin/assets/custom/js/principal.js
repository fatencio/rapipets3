

function loadController(route) {

	routeItems = route.split("/");	
	parameters = "";
	controller = routeItems[0];
	action = routeItems[1]; 
	
	for(i=2; i < routeItems.length; i++){
		parameters += routeItems[i] + "/"; 		
	}
	
	console.log(BASE_PATH + '/' + controller + "/" + action + "/" + parameters);

    var request = $.ajax({
        url: BASE_PATH + '/' + controller + "/" + action + "/" + parameters,
        type: "GET",
        dataType: "html"
    });


    request.done(function(result) {
        $('#main_container').html(result);		
		
		//establece los tabs por defecto
		//getDefaultTabs(action);			
		
    });
	
    request.fail(function(jqXHR, textStatus) {

       // alert("fail: " + jqXHR.responseText + "textStatus: " + textStatus);
        //$.notify("Ocurrió un error al cargar la página.", {globalPosition: 'top center', className: 'error'});

    });
};
