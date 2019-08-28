$(function () {
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();
});
//form them moi
function add_form()
{
    save_method = 'add';
    $('#title-form').text('Thêm cửa hàng');
    $('#modal_form').modal('show');
	$('#modal_form').trigger("reset");
}

//form sua
function edit_form(id) {
    // slug_disable = true;
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $.each(data, function (key, value) {
                $.each(value, function (k, v) {
                    if ($.inArray(k, ['language_code','name','address','slug','description','content','meta_title','meta_description','meta_keyword']) != -1) {
                        $('[name="'+k+'['+value.language_code+']"]').val(v);
                    }else{
                        $('[name="'+k+'"]').val(v);
                    }
                });
                $('[name="meta_keyword['+value.language_code+']"]').tagsinput('add', value.meta_keyword);
            });
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_edit']);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

//ajax luu form
function save()
{
    $('#btnSave').text(language['btn_saving']); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if (save_method == 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            // console.log(data);
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                })
                console.log('???')
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".modal-body").prepend(box_alert('alert-danger',language['error_try_again']));
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}
function show_modal_maps() {
  $('#autocomplete').val('');
  $('#modal_form_maps').fadeIn(200);
  $('.pac-container').css('z-index', 99999999);
}
var autocomplete, map;
var input = document.getElementById('autocomplete');

function geolocate() {
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
    var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
    };
        var circle = new google.maps.Circle({
            center: geolocation,
            radius: position.coords.accuracy
        });
    autocomplete.setBounds(circle.getBounds());
    });
    }
};

$(document).ready(function () {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 21.0277644, lng: 105.83415979999995},
        zoom: 16
    })
    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function () {
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
        window.alert("No details available for input: '" + place.name + "'");
        return;
    }

    if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
    } else {
        map.setCenter(place.geometry.location);
        map.setZoom(16);
    }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    });
    autocomplete.addListener('place_changed', suggestProject);

    window.onload = function () {
        var infoWindow = new google.maps.InfoWindow();
        var latlngbounds = new google.maps.LatLngBounds();
        google.maps.event.addListener(map, 'click', function (e) {
            deleteMarkers();
            $('.info_maps .gllpLatitude').val(e.latLng.lat()).trigger('change');
            $('.info_maps .gllpLongitude').val(e.latLng.lng()).trigger('change');
            addMarker(e.latLng);
        });
    }
});
// Adds a marker to the map and push to the array.
function addMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markers.push(marker);
}
// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}
// Sets the map on all markers in the array.
function setMapOnAll(map) {
    if (typeof markers != 'undefined') {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
    if (typeof marker != 'undefined') {
        for (var i = 0; i < marker.length; i++) {
            marker[i].setMap(map);
        }
    }
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

function suggestProject() {
    var place = autocomplete.getPlace();
    let lat = place.geometry.location.lat();
    let lng = place.geometry.location.lng();
    $('.info_maps .gllpLatitude').val(lat).trigger('change');
    $('.info_maps .gllpLongitude').val(lng).trigger('change');
}

function createMarker(latlng) {
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
    });
    return marker;
}